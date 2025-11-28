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
    private function calcularNumeroDiplomado($diplomado)
    {
        if (!$diplomado->created_at instanceof Carbon) {
            $diplomado->created_at = Carbon::parse($diplomado->created_at);
        }

        $diplomadosDelAnio = Diplomado::whereYear('created_at', $diplomado->created_at->format('Y'))
            ->orderBy('created_at')
            ->get();

        $numeroDiplomado = $diplomadosDelAnio->search(function ($d) use ($diplomado) {
            return $d->id === $diplomado->id;
        });

        if ($numeroDiplomado === false) {
            throw new \Exception('No se pudo calcular el número del diplomado dentro del año.');
        }

        return $numeroDiplomado + 1;
    }

    private function generarNumeroRegistroDiplomado($diplomado, $tipoRegistro, $participante_id)
    {
        $numeroDiplomado = $this->calcularNumeroDiplomado($diplomado);
        $año = Carbon::parse($diplomado->created_at)->format('Y');

        if ($tipoRegistro === 'Instructor') {
            // Para instructores, obtener el número secuencial
            $solicitudesInstructores = solicitud_instructore::where('diplomado_id', $diplomado->id)
                ->where('estatus', 2)
                ->orderBy('id')
                ->get();

            $numeroInstructor = $solicitudesInstructores->search(function ($s) use ($participante_id) {
                return $s->id == $participante_id;
            });

            if ($numeroInstructor === false) {
                throw new \Exception('Instructor no encontrado entre los aprobados.');
            }

            return sprintf('TNM-169-%02d-%s/I-%02d',
                $numeroDiplomado,
                $año,
                $numeroInstructor + 1
            );
        } else {
            // Para participantes
            $solicitudesParticipantes = solicitud_docente::where('diplomado_id', $diplomado->id)
                ->where('estatus', 2)
                ->orderBy('id')
                ->get();

            $numeroParticipante = $solicitudesParticipantes->search(function ($s) use ($participante_id) {
                return $s->id == $participante_id;
            });

            if ($numeroParticipante === false) {
                throw new \Exception('Participante no encontrado entre los aprobados.');
            }

            return sprintf('TNM-169-%02d-%s/%02d',
                $numeroDiplomado,
                $año,
                $numeroParticipante + 1
            );
        }
    }
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

        // Generar número de registro
        $numeroRegistro = $this->generarNumeroRegistroDiplomado($diplomado, $tipoRegistro, $participante_id);

        // Preparar datos para la vista
        $tipoUsuario = $tipoRegistro; // Para compatibilidad con la plantilla
        $participante = $usuario->datos_generales; // Los datos del participante

        // Generar el PDF
        $pdf = PDF::loadView('vistas.diplomados.pdf.constancia', compact(
            'diplomado',
            'participante',
            'tipoUsuario',
            'duracionTotalHoras',
            'duracionDias',
            'fecha_actual',
            'numeroRegistro'
        ));

        // Nombre del archivo
        $nombreArchivo = 'Constancia_Diplomado_' . str_replace(' ', '_', $diplomado->nombre) . '_' .
                        str_replace(' ', '_', $usuario->datos_generales->nombre . '_' .
                        $usuario->datos_generales->apellido_paterno) . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
}
