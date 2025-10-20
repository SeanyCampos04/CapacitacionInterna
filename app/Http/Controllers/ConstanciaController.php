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

        // Generar el PDF con la vista y los datos
        $pdf = Pdf::loadView('externa.pdf.constancia', compact('capacitacion'));

        // Retornar el PDF para descargar o visualizar
        return $pdf->stream('constancia.pdf');
    }
}
