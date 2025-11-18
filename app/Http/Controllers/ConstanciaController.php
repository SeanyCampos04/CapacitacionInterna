<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCapacitacionesExt;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class ConstanciaController extends Controller
{
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

        $tipoUsuario = $esInstructor ? 'Instructor' : 'Participante';

        // Generar el PDF con la vista y los datos
        $pdf = Pdf::loadView('externa.pdf.constancia', compact('capacitacion', 'tipoUsuario'));

        // Retornar el PDF para descargar o visualizar
        return $pdf->stream('constancia.pdf');
    }
}
