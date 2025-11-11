<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\cursos_participante;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ConstanciaCursoController extends Controller
{
    public function generarPDF($curso_id, $participante_id)
    {
        // Recuperar los datos del curso
        $curso = Curso::with([
            'instructores.user.datos_generales',
            'departamento',
            'periodo'
        ])->findOrFail($curso_id);

        // Recuperar los datos del participante inscrito
        $participanteInscrito = cursos_participante::with([
            'participante.user.datos_generales'
        ])->where('curso_id', $curso_id)
          ->where('id', $participante_id)
          ->where('acreditado', 2) // Solo participantes acreditados
          ->firstOrFail();

        // Datos para la constancia
        $datos = [
            'curso' => $curso,
            'participante' => $participanteInscrito->participante,
            'calificacion' => $participanteInscrito->calificacion,
            'fecha_actual' => Carbon::now()
        ];

        // Generar el PDF con la vista y los datos
        $pdf = Pdf::loadView('vistas.cursos.pdf.constancia', $datos);

        // Nombre del archivo
        $nombreArchivo = 'Constancia_' . 
            str_replace(' ', '_', $curso->nombre) . '_' . 
            str_replace(' ', '_', $participanteInscrito->participante->user->datos_generales->nombre) . '.pdf';

        // Retornar el PDF para descargar o visualizar
        return $pdf->stream($nombreArchivo);
    }
}