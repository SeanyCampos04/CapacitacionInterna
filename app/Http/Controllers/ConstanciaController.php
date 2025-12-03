<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCapacitacionesExt;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ConstanciaController extends Controller
{
    private function generarNumeroRegistroExterno($capacitacion, $tipoUsuario)
    {
        // Obtener capacitaciones del mismo año ordenadas por ID
        $capacitacionesDelAnio = RegistroCapacitacionesExt::whereYear('created_at', Carbon::parse($capacitacion->created_at)->format('Y'))
            ->orderBy('id')
            ->get();

        $numeroCapacitacion = $capacitacionesDelAnio->search(function ($c) use ($capacitacion) {
            return $c->id === $capacitacion->id;
        });

        if ($numeroCapacitacion === false) {
            throw new \Exception('No se pudo calcular el número de la capacitación dentro del año.');
        }

        $numeroCapacitacion += 1;
        $año = Carbon::parse($capacitacion->created_at)->format('Y');

        if ($tipoUsuario === 'Instructor') {
            return sprintf('TNM-169-%02d-%s/I-%02d', $numeroCapacitacion, $año, 1);
        } else {
            return sprintf('TNM-169-%02d-%s/%02d', $numeroCapacitacion, $año, 1);
        }
    }
    public function generarPDF($id)
    {
        // Recuperar los datos de la capacitación específica
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);

        // Determinar tipo de usuario basado en el rol del usuario que registró la capacitación
        $user = User::where('email', $capacitacion->correo)->first();
        $esInstructor = false;

        if ($user && $user->roles) {
            $rolesUsuario = $user->roles->pluck('nombre')->toArray();
            $esInstructor = in_array('Instructor', $rolesUsuario);
        }

        // En capacitaciones externas SIEMPRE es Participante (genera Constancia)
        // No importa si el usuario es instructor en el sistema
        $tipoUsuario = 'Participante';

        // Generar número de registro sistemático
        $numeroRegistro = $capacitacion->folio ?: $this->generarNumeroRegistroExterno($capacitacion, $tipoUsuario);

        // Actualizar folio si no existe
        if (!$capacitacion->folio && $numeroRegistro) {
            $capacitacion->folio = $numeroRegistro;
            $capacitacion->save();
        }

        // Generar código QR usando el número de registro - usando API externa para compatibilidad PDF
        $codigoQR = null;
        if ($numeroRegistro && $numeroRegistro !== 'Rechazado') {
            $urlVerificacion = route('verificacion.general', $numeroRegistro);
            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($urlVerificacion);

            $qrImageData = @file_get_contents($qrApiUrl);
            if ($qrImageData !== false) {
                $codigoQR = 'data:image/png;base64,' . base64_encode($qrImageData);
            }
        }

        // Obtener imagen de fondo del periodo más reciente (último registrado)
        $imagenFondo = null;
        $periodoReciente = \App\Models\Periodo::orderBy('id', 'desc')->first();
        if ($periodoReciente && $periodoReciente->archivo_fondo) {
            $imagenFondo = public_path('storage/' . $periodoReciente->archivo_fondo);
        }

        // Generar el PDF con la vista y los datos
        $pdf = Pdf::loadView('externa.pdf.constancia', compact('capacitacion', 'tipoUsuario', 'codigoQR', 'imagenFondo', 'numeroRegistro'));

        // Retornar el PDF para descargar o visualizar
        return $pdf->stream('constancia.pdf');
    }
}
