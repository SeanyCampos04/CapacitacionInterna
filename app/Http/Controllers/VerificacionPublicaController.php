<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\cursos_participante;
use App\Models\RegistroCapacitacionesExt;
use App\Models\Diplomado;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerificacionPublicaController extends Controller
{
    /**
     * Verificar constancia de participante (cursos internos)
     */
    public function verificarConstancia($numeroRegistro)
    {
        // Buscar en capacitaciones externas primero
        $capacitacionExterna = RegistroCapacitacionesExt::where('folio', $numeroRegistro)->first();

        if ($capacitacionExterna) {
            $documento = [
                'tipo_documento' => 'Constancia de Capacitación Externa',
                'nombre_completo' => $capacitacionExterna->nombre . ' ' . $capacitacionExterna->apellido_paterno . ' ' . $capacitacionExterna->apellido_materno,
                'nombre_programa' => $capacitacionExterna->nombre_capacitacion,
                'descripcion' => 'Capacitación externa de tipo: ' . $capacitacionExterna->tipo_capacitacion,
                'horas' => $capacitacionExterna->horas,
                'modalidad' => 'Externa',
                'organismo' => $capacitacionExterna->organismo,
                'fecha_emision' => $capacitacionExterna->created_at,
                'anio' => $capacitacionExterna->anio,
                'fecha_inicio' => $capacitacionExterna->fecha_inicio,
                'fecha_termino' => $capacitacionExterna->fecha_termino,
            ];

            return view('verificacion.documento', [
                'documento' => $documento,
                'numeroRegistro' => $numeroRegistro,
                'estado' => 'VÁLIDO',
                'modulo' => 'Externa'
            ]);
        }

        // Buscar en cursos internos
        $participante = $this->buscarEnCursosInternos($numeroRegistro);

        if ($participante) {
            // Obtener datos del participante
            $datosParticipante = DB::table('participantes')
                ->join('users', 'participantes.user_id', '=', 'users.id')
                ->join('datos_generales', 'users.id', '=', 'datos_generales.user_id')
                ->where('participantes.id', $participante->participante_id)
                ->select('datos_generales.nombre', 'datos_generales.apellido_paterno', 'datos_generales.apellido_materno')
                ->first();
            
            $nombreCompleto = 'Sin datos';
            if ($datosParticipante) {
                $nombreCompleto = trim($datosParticipante->nombre . ' ' . $datosParticipante->apellido_paterno . ' ' . $datosParticipante->apellido_materno);
            }
            
            // Obtener datos de instructores
            $instructores = DB::table('cursos_instructores')
                ->join('instructores', 'cursos_instructores.instructore_id', '=', 'instructores.id')
                ->join('users', 'instructores.user_id', '=', 'users.id')
                ->join('datos_generales', 'users.id', '=', 'datos_generales.user_id')
                ->where('cursos_instructores.curso_id', $participante->curso->id)
                ->select('datos_generales.nombre', 'datos_generales.apellido_paterno', 'datos_generales.apellido_materno')
                ->get();
            
            $nombresInstructores = $instructores->map(function($instructor) {
                return trim($instructor->nombre . ' ' . $instructor->apellido_paterno . ' ' . $instructor->apellido_materno);
            })->implode(', ');
            
            if (empty($nombresInstructores)) {
                $nombresInstructores = 'Información de instructores disponible';
            }
            
            // Generar descripción completa como en la constancia
            $fechaInicio = Carbon::parse($participante->curso->fdi);
            $fechaFin = Carbon::parse($participante->curso->fdf);
            
            $descripcionCompleta = "POR PARTICIPAR Y ACREDITAR SATISFACTORIAMENTE EL CURSO DE CAPACITACIÓN \"" . 
                strtoupper($participante->curso->nombre) . "\" IMPARTIDO POR " . 
                strtoupper($nombresInstructores) . " DEL " . 
                $fechaInicio->format('d') . " DE " . strtoupper($fechaInicio->translatedFormat('F')) . 
                " AL " . $fechaFin->format('d') . " DE " . strtoupper($fechaFin->translatedFormat('F')) . 
                " DEL " . $fechaInicio->format('Y') . ", CON UNA DURACIÓN DE " . 
                $participante->curso->duracion . " HORAS, CON LA MODALIDAD " . 
                strtoupper($participante->curso->modalidad) . ", REALIZADO EN " . 
                strtoupper($participante->curso->lugar) . ", EN EL DEPARTAMENTO DE " . 
                strtoupper($participante->curso->departamento->nombre ?? 'SIN DEPARTAMENTO') . 
                ", CON LA CALIFICACIÓN OBTENIDA DE " . ($participante->calificacion ?? '100') . ".";
            
            $documento = [
                'tipo_documento' => 'Constancia de Curso',
                'nombre_completo' => $nombreCompleto,
                'nombre_programa' => $participante->curso->nombre,
                'descripcion' => $descripcionCompleta,
                'horas' => $participante->curso->duracion,
                'modalidad' => $participante->curso->modalidad,
                'lugar' => $participante->curso->lugar,
                'fecha_emision' => Carbon::now(),
                'departamento' => $participante->curso->departamento->nombre ?? 'Sin departamento',
                'fecha_inicio' => $participante->curso->fdi,
                'fecha_termino' => $participante->curso->fdf,
                'calificacion' => $participante->calificacion,
                'instructores' => $nombresInstructores
            ];            return view('verificacion.documento', [
                'documento' => $documento,
                'numeroRegistro' => $numeroRegistro,
                'estado' => 'VÁLIDO',
                'modulo' => 'Interna'
            ]);
        }

        // No encontrado
        return view('verificacion.documento', [
            'documento' => null,
            'numeroRegistro' => $numeroRegistro,
            'estado' => 'INVÁLIDO',
            'modulo' => 'No identificado'
        ]);
    }

    /**
     * Verificar reconocimiento de instructor
     */
    public function verificarReconocimiento($numeroRegistro)
    {
        // Buscar instructor en cursos internos
        $instructor = $this->buscarInstructorEnCursosInternos($numeroRegistro);

        if ($instructor) {
            $nombreInstructor = 'Sin datos';
            if ($instructor['instructor_datos']) {
                $nombreInstructor = trim($instructor['instructor_datos']->nombre . ' ' .
                                       $instructor['instructor_datos']->apellido_paterno . ' ' .
                                       $instructor['instructor_datos']->apellido_materno);
            }

            $documento = [
                'tipo_documento' => 'Reconocimiento de Instructor',
                'nombre_completo' => $nombreInstructor,
                'nombre_programa' => $instructor['curso']->nombre,
                'descripcion' => 'Reconocimiento por impartir curso de capacitación',
                'horas' => $instructor['curso']->duracion,
                'modalidad' => $instructor['curso']->modalidad,
                'lugar' => $instructor['curso']->lugar,
                'fecha_emision' => Carbon::now(),
                'departamento' => $instructor['curso']->departamento->nombre ?? 'Sin departamento',
                'fecha_inicio' => $instructor['curso']->fdi,
                'fecha_termino' => $instructor['curso']->fdf,
            ];

            return view('verificacion.documento', [
                'documento' => $documento,
                'numeroRegistro' => $numeroRegistro,
                'estado' => 'VÁLIDO',
                'modulo' => 'Interna'
            ]);
        }

        // No encontrado
        return view('verificacion.documento', [
            'documento' => null,
            'numeroRegistro' => $numeroRegistro,
            'estado' => 'INVÁLIDO',
            'modulo' => 'No identificado'
        ]);
    }

    /**
     * Verificar diploma (preparado para futuro)
     */
    public function verificarDiploma($numeroRegistro)
    {
        // TODO: Implementar cuando el módulo de diplomados tenga números de registro
        return view('verificacion.documento', [
            'documento' => null,
            'numeroRegistro' => $numeroRegistro,
            'estado' => 'NO IMPLEMENTADO',
            'modulo' => 'Diplomados',
            'mensaje' => 'El módulo de diplomados aún no tiene implementada la numeración de registro.'
        ]);
    }

    /**
     * Verificación general que detecta automáticamente el tipo
     */
    public function verificarDocumento($numeroRegistro)
    {
        // Detectar si es de instructor por el formato /I-
        if (strpos($numeroRegistro, '/I-') !== false) {
            return $this->verificarReconocimiento($numeroRegistro);
        }

        // Si no, es constancia
        return $this->verificarConstancia($numeroRegistro);
    }

    /**
     * Buscar participante en cursos internos
     */
    private function buscarEnCursosInternos($numeroRegistro)
    {
        // El número tiene formato: TNM-169-XX-YYYY/ZZ
        if (!preg_match('/TNM-169-(\d+)-(\d{4})\/(\d+)/', $numeroRegistro, $matches)) {
            return null;
        }

        $numeroCurso = (int)$matches[1];
        $anio = (int)$matches[2];
        $numeroParticipante = (int)$matches[3];

        // Buscar curso por número y año
        $cursosDelAnio = Curso::whereYear('created_at', $anio)
            ->orderBy('created_at')
            ->get();

        if ($cursosDelAnio->count() < $numeroCurso) {
            return null;
        }

        $curso = $cursosDelAnio[$numeroCurso - 1];

        // Buscar participante por número de registro directo
        $participante = cursos_participante::with([
            'curso.departamento'
        ])->where('numero_registro', $numeroRegistro)
          ->where('acreditado', 2)
          ->first();

        return $participante;
    }

    /**
     * Buscar instructor en cursos internos
     */
    private function buscarInstructorEnCursosInternos($numeroRegistro)
    {
        // El número tiene formato: TNM-169-XX-YYYY/I-ZZ
        if (!preg_match('/TNM-169-(\d+)-(\d{4})\/I-(\d+)/', $numeroRegistro, $matches)) {
            return null;
        }

        $numeroCurso = (int)$matches[1];
        $anio = (int)$matches[2];
        $numeroInstructor = (int)$matches[3];

        // Buscar curso por número y año
        $cursosDelAnio = Curso::whereYear('created_at', $anio)
            ->orderBy('created_at')
            ->get();

        if ($cursosDelAnio->count() < $numeroCurso) {
            return null;
        }

        $curso = Curso::with('departamento')->find($cursosDelAnio[$numeroCurso - 1]->id);

        // Buscar instructor en la tabla cursos_instructores
        $instructorRelacion = DB::table('cursos_instructores')
            ->where('curso_id', $curso->id)
            ->first();

        if (!$instructorRelacion) {
            return null;
        }

        // Obtener datos del instructor
        $datosInstructor = DB::table('instructores')
            ->join('users', 'instructores.user_id', '=', 'users.id')
            ->join('datos_generales', 'users.id', '=', 'datos_generales.user_id')
            ->where('instructores.id', $instructorRelacion->instructore_id)
            ->select('datos_generales.nombre', 'datos_generales.apellido_paterno', 'datos_generales.apellido_materno')
            ->first();

        return [
            'curso' => $curso,
            'instructor_datos' => $datosInstructor
        ];
    }
}
