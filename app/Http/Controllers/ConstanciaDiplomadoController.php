<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diplomado;
use App\Models\solicitud_docente;
use App\Models\solicitud_instructore;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ConstanciaDiplomadoController extends Controller
{
    public function generarPDF($diplomado_id, $participante_id, $tipo)
    {
        // Obtener el diplomado
        $diplomado = Diplomado::with(['modulos'])->findOrFail($diplomado_id);

        // Determinar si es participante o instructor y obtener los datos
        if ($tipo === 'participante') {
            $solicitud = solicitud_docente::with(['participante.user.datos_generales'])
                ->where('diplomado_id', $diplomado_id)
                ->where('id', $participante_id)
                ->where('estatus', 2)
                ->firstOrFail();


            $usuario = $solicitud->participante->user;
            $tipoRegistro = 'Participante';
        } elseif ($tipo === 'instructor') {
            $solicitud = solicitud_instructore::with(['instructore.user.datos_generales'])
                ->where('diplomado_id', $diplomado_id)
                ->where('id', $participante_id)
                ->where('estatus', 2)
                ->firstOrFail();

            $usuario = $solicitud->instructore->user;
            $tipoRegistro = 'Instructor';
        } else {
            abort(400, 'Tipo de constancia no válido');
        }

        // Calcular duración total del diplomado en días
        $duracionTotalHoras = $diplomado->modulos->sum('duracion');
        $duracionDias = \Carbon\Carbon::parse($diplomado->inicio_realizacion)
            ->diffInDays(\Carbon\Carbon::parse($diplomado->termino_realizacion)) + 1;

        // Fecha actual
        $fecha_actual = Carbon::now();

        // Generar el PDF
        $pdf = PDF::loadView('vistas.diplomados.pdf.constancia', compact(
            'diplomado',
            'usuario',
            'tipoRegistro',
            'duracionTotalHoras',
            'duracionDias',
            'fecha_actual'
        ));

        // Nombre del archivo
        $nombreArchivo = 'Constancia_Diplomado_' . str_replace(' ', '_', $diplomado->nombre) . '_' .
                        str_replace(' ', '_', $usuario->datos_generales->nombre . '_' .
                        $usuario->datos_generales->apellido_paterno) . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
}
