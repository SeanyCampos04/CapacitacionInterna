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

        // Generar código QR si tiene folio asignado
        $codigoQR = null;
        if ($capacitacion->folio && $capacitacion->folio !== 'Rechazado') {
            $urlVerificacion = route('verificacion.constancia', $capacitacion->folio);
            $codigoQR = QrCode::format('svg')->size(200)->generate($urlVerificacion);
        }

        // Obtener imagen de fondo del periodo más reciente (último registrado)
        $imagenFondo = null;
        $periodoReciente = \App\Models\Periodo::orderBy('id', 'desc')->first();
        if ($periodoReciente && $periodoReciente->archivo_fondo) {
            $imagenFondo = public_path('storage/' . $periodoReciente->archivo_fondo);
        }

        // Generar número de registro - DESACTIVADO TEMPORALMENTE
        // $numeroRegistro = $this->generarNumeroRegistroExterno($capacitacion, $tipoUsuario);

        // Generar el PDF con la vista y los datos
        $pdf = Pdf::loadView('externa.pdf.constancia', compact('capacitacion', 'tipoUsuario', 'codigoQR', 'imagenFondo'));

        // Retornar el PDF para descargar o visualizar
        return $pdf->stream('constancia.pdf');
    }
}
