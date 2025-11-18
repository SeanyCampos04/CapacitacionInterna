<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCapacitacionesExt;
use Barryvdh\DomPDF\Facade\Pdf;

class ConstanciaController extends Controller
{
    public function generarPDF($id)
    {
        // Recuperar los datos de la capacitación específica
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);

        // Determinar tipo de usuario basado en el tipo de capacitación
        // Si contiene "instructor" o "docente", es reconocimiento, si no, constancia
        $tipoCapacitacion = strtolower($capacitacion->tipo_capacitacion);
        $tipoUsuario = (strpos($tipoCapacitacion, 'instructor') !== false ||
                       strpos($tipoCapacitacion, 'docente') !== false) ?
                       'Instructor' : 'Participante';

        // Generar el PDF con la vista y los datos
        $pdf = Pdf::loadView('externa.pdf.constancia', compact('capacitacion', 'tipoUsuario'));

        // Retornar el PDF para descargar o visualizar
        return $pdf->stream('constancia.pdf');
    }
}
