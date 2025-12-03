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
        // Buscar en capacitaciones externas primero (exacto)
        $capacitacionExterna = RegistroCapacitacionesExt::where('folio', $numeroRegistro)->first();

        // Si no se encuentra exacto, buscar por LIKE
        if (!$capacitacionExterna) {
            $capacitacionExterna = RegistroCapacitacionesExt::where('folio', 'LIKE', "%{$numeroRegistro}%")->first();
        }

        // Si aún no se encuentra y el número tiene el formato TNM-169-, buscar solo la parte final
        if (!$capacitacionExterna && strpos($numeroRegistro, 'TNM-169-') === 0) {
            $parteNumero = str_replace('TNM-169-', '', $numeroRegistro);
            $capacitacionExterna = RegistroCapacitacionesExt::where('folio', $parteNumero)->first();

            // También buscar por LIKE con la parte del número
            if (!$capacitacionExterna) {
                $capacitacionExterna = RegistroCapacitacionesExt::where('folio', 'LIKE', "%{$parteNumero}%")->first();
            }
        }

        if ($capacitacionExterna) {
            // Generar descripción completa como en la constancia
            $fechaInicio = \Carbon\Carbon::parse($capacitacionExterna->fecha_inicio);
            $fechaFin = \Carbon\Carbon::parse($capacitacionExterna->fecha_termino);

            $descripcionCompleta = "POR PARTICIPAR EN LA CAPACITACIÓN EXTERNA \"" .
                strtoupper($capacitacionExterna->nombre_capacitacion) . "\" DE TIPO " .
                strtoupper($capacitacionExterna->tipo_capacitacion) . " IMPARTIDA POR " .
                strtoupper($capacitacionExterna->organismo) . " DEL " .
                $fechaInicio->format('d') . " DE " . strtoupper($fechaInicio->translatedFormat('F')) .
                " AL " . $fechaFin->format('d') . " DE " . strtoupper($fechaFin->translatedFormat('F')) .
                " DEL " . $fechaInicio->format('Y') . ", CON UNA DURACIÓN DE " .
                $capacitacionExterna->horas . " HORAS.";

            $documento = [
                'tipo_documento' => 'Constancia de Capacitación Externa',
                'nombre_completo' => $capacitacionExterna->nombre . ' ' . $capacitacionExterna->apellido_paterno . ' ' . $capacitacionExterna->apellido_materno,
                'nombre_programa' => $capacitacionExterna->nombre_capacitacion,
                'descripcion' => $descripcionCompleta,
                'horas' => $capacitacionExterna->horas,
                'modalidad' => 'Externa',
                'organismo' => $capacitacionExterna->organismo,
                'fecha_emision' => $capacitacionExterna->created_at,
                'anio' => $capacitacionExterna->anio,
                'fecha_inicio' => $capacitacionExterna->fecha_inicio,
                'fecha_termino' => $capacitacionExterna->fecha_termino,
                'tipo_capacitacion' => $capacitacionExterna->tipo_capacitacion
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
     * Verificar diploma/reconocimiento de diplomados
     */
    public function verificarDiploma($numeroRegistro)
    {
        // PASO 1: Buscar directamente en solicitud_docentes sin filtros complejos
        $solicitudBasica = DB::table('solicitud_docentes')
            ->where('numero_registro', $numeroRegistro)
            ->first();

        if (!$solicitudBasica) {
            // Buscar con LIKE
            $solicitudBasica = DB::table('solicitud_docentes')
                ->where('numero_registro', 'LIKE', "%{$numeroRegistro}%")
                ->first();
        }

        if ($solicitudBasica) {
            // PASO 2: Solo si encuentra el registro básico, hacer los JOINs
            $solicitudParticipante = DB::table('solicitud_docentes')
                ->join('diplomados', 'solicitud_docentes.diplomado_id', '=', 'diplomados.id')
                ->leftJoin('participantes', 'solicitud_docentes.participante_id', '=', 'participantes.id')
                ->leftJoin('users', 'participantes.user_id', '=', 'users.id')
                ->leftJoin('datos_generales', 'users.id', '=', 'datos_generales.user_id')
                ->where('solicitud_docentes.numero_registro', $numeroRegistro)
                // ->where('solicitud_docentes.estatus', 2) // Temporalmente removido para pruebas
                ->select(
                    'diplomados.*',
                    'datos_generales.nombre',
                    'datos_generales.apellido_paterno',
                    'datos_generales.apellido_materno',
                    'solicitud_docentes.created_at as fecha_solicitud',
                    'solicitud_docentes.estatus'
                )
                ->first();
        } else {
            $solicitudParticipante = null;
        }

        if ($solicitudParticipante) {
            $nombreCompleto = trim($solicitudParticipante->nombre . ' ' .
                                $solicitudParticipante->apellido_paterno . ' ' .
                                $solicitudParticipante->apellido_materno);

            // Calcular duración en días
            $duracionDias = \Carbon\Carbon::parse($solicitudParticipante->inicio_realizacion)
                ->diffInDays(\Carbon\Carbon::parse($solicitudParticipante->termino_realizacion)) + 1;

            $documento = [
                'tipo_documento' => 'Diploma',
                'nombre_completo' => $nombreCompleto,
                'nombre_programa' => $solicitudParticipante->nombre,
                'descripcion' => 'Por participar y acreditar satisfactoriamente en el diplomado "' .
                               strtoupper($solicitudParticipante->nombre) . '" realizado del ' .
                               \Carbon\Carbon::parse($solicitudParticipante->inicio_realizacion)->format('d/m/Y') . ' al ' .
                               \Carbon\Carbon::parse($solicitudParticipante->termino_realizacion)->format('d/m/Y') .
                               ', con una duración de ' . $duracionDias . ' días, de tipo ' .
                               strtoupper($solicitudParticipante->tipo) . ', realizado en ' .
                               strtoupper($solicitudParticipante->sede) . '.',
                'horas' => $duracionDias * 8, // Estimación de horas
                'modalidad' => 'Presencial',
                'lugar' => $solicitudParticipante->sede,
                'fecha_emision' => $solicitudParticipante->fecha_solicitud,
                'fecha_inicio' => $solicitudParticipante->inicio_realizacion,
                'fecha_termino' => $solicitudParticipante->termino_realizacion,
                'anio' => \Carbon\Carbon::parse($solicitudParticipante->inicio_realizacion)->format('Y')
            ];

            return view('verificacion.documento', [
                'documento' => $documento,
                'numeroRegistro' => $numeroRegistro,
                'estado' => 'VÁLIDO',
                'modulo' => 'Diplomados'
            ]);
        }

        // PASO 3: Buscar en instructores si no encontró en participantes
        $solicitudInstructorBasica = DB::table('solicitud_instructores')
            ->where('numero_registro', $numeroRegistro)
            ->first();

        if (!$solicitudInstructorBasica) {
            $solicitudInstructorBasica = DB::table('solicitud_instructores')
                ->where('numero_registro', 'LIKE', "%{$numeroRegistro}%")
                ->first();
        }

        if ($solicitudInstructorBasica) {
            $solicitudInstructor = DB::table('solicitud_instructores')
                ->join('diplomados', 'solicitud_instructores.diplomado_id', '=', 'diplomados.id')
                ->leftJoin('instructores', 'solicitud_instructores.instructore_id', '=', 'instructores.id')
                ->leftJoin('users', 'instructores.user_id', '=', 'users.id')
                ->leftJoin('datos_generales', 'users.id', '=', 'datos_generales.user_id')
                ->where('solicitud_instructores.numero_registro', $numeroRegistro)
                // ->where('solicitud_instructores.estatus', 2) // Temporalmente removido
                ->select(
                    'diplomados.*',
                    'datos_generales.nombre',
                    'datos_generales.apellido_paterno',
                    'datos_generales.apellido_materno',
                    'solicitud_instructores.created_at as fecha_solicitud',
                    'solicitud_instructores.estatus'
                )
                ->first();
        } else {
            $solicitudInstructor = null;
        }

        if ($solicitudInstructor) {
            $nombreCompleto = trim($solicitudInstructor->nombre . ' ' .
                                $solicitudInstructor->apellido_paterno . ' ' .
                                $solicitudInstructor->apellido_materno);

            $duracionDias = \Carbon\Carbon::parse($solicitudInstructor->inicio_realizacion)
                ->diffInDays(\Carbon\Carbon::parse($solicitudInstructor->termino_realizacion)) + 1;

            $documento = [
                'tipo_documento' => 'Reconocimiento de Instructor de Diplomado',
                'nombre_completo' => $nombreCompleto,
                'nombre_programa' => $solicitudInstructor->nombre,
                'descripcion' => 'Por impartir como instructor el diplomado "' .
                               strtoupper($solicitudInstructor->nombre) . '" realizado del ' .
                               \Carbon\Carbon::parse($solicitudInstructor->inicio_realizacion)->format('d/m/Y') . ' al ' .
                               \Carbon\Carbon::parse($solicitudInstructor->termino_realizacion)->format('d/m/Y') .
                               ', con una duración de ' . $duracionDias . ' días, de tipo ' .
                               strtoupper($solicitudInstructor->tipo) . ', realizado en ' .
                               strtoupper($solicitudInstructor->sede) . '.',
                'horas' => $duracionDias * 8,
                'modalidad' => 'Presencial',
                'lugar' => $solicitudInstructor->sede,
                'fecha_emision' => $solicitudInstructor->fecha_solicitud,
                'fecha_inicio' => $solicitudInstructor->inicio_realizacion,
                'fecha_termino' => $solicitudInstructor->termino_realizacion,
                'anio' => \Carbon\Carbon::parse($solicitudInstructor->inicio_realizacion)->format('Y')
            ];

            return view('verificacion.documento', [
                'documento' => $documento,
                'numeroRegistro' => $numeroRegistro,
                'estado' => 'VÁLIDO',
                'modulo' => 'Diplomados'
            ]);
        }

        // PASO 4: Búsquedas adicionales con más flexibilidad

        // Buscar solo con la parte numérica si el número empieza con TNM-169-
        if (strpos($numeroRegistro, 'TNM-169-') === 0) {
            $parteNumero = str_replace('TNM-169-', '', $numeroRegistro);

            // Buscar en participantes con la parte del número
            $solicitudParticipanteParte = DB::table('solicitud_docentes')
                ->leftJoin('diplomados', 'solicitud_docentes.diplomado_id', '=', 'diplomados.id')
                ->where('solicitud_docentes.numero_registro', $parteNumero)
                ->orWhere('solicitud_docentes.numero_registro', 'LIKE', "%{$parteNumero}%")
                ->select(
                    'diplomados.*',
                    'solicitud_docentes.created_at as fecha_solicitud',
                    'solicitud_docentes.estatus',
                    'solicitud_docentes.participante_id'
                )
                ->first();

            if ($solicitudParticipanteParte) {
                $documento = [
                    'tipo_documento' => 'Diploma',
                    'nombre_completo' => 'Participante de Diplomado',
                    'nombre_programa' => $solicitudParticipanteParte->nombre ?? 'Diplomado',
                    'descripcion' => 'Diploma de participación en diplomado.',
                    'horas' => 40,
                    'modalidad' => 'Presencial',
                    'lugar' => $solicitudParticipanteParte->sede ?? 'Sede del diplomado',
                    'fecha_emision' => $solicitudParticipanteParte->fecha_solicitud,
                    'fecha_inicio' => $solicitudParticipanteParte->inicio_realizacion ?? '2025-01-01',
                    'fecha_termino' => $solicitudParticipanteParte->termino_realizacion ?? '2025-12-31',
                    'anio' => '2025'
                ];

                return view('verificacion.documento', [
                    'documento' => $documento,
                    'numeroRegistro' => $numeroRegistro,
                    'estado' => 'VÁLIDO',
                    'modulo' => 'Diplomados'
                ]);
            }

            // Buscar en instructores con la parte del número
            $solicitudInstructorParte = DB::table('solicitud_instructores')
                ->leftJoin('diplomados', 'solicitud_instructores.diplomado_id', '=', 'diplomados.id')
                ->where('solicitud_instructores.numero_registro', $parteNumero)
                ->orWhere('solicitud_instructores.numero_registro', 'LIKE', "%{$parteNumero}%")
                ->select(
                    'diplomados.*',
                    'solicitud_instructores.created_at as fecha_solicitud',
                    'solicitud_instructores.estatus'
                )
                ->first();

            if ($solicitudInstructorParte) {
                $documento = [
                    'tipo_documento' => 'Reconocimiento de Instructor de Diplomado',
                    'nombre_completo' => 'Instructor de Diplomado',
                    'nombre_programa' => $solicitudInstructorParte->nombre ?? 'Diplomado',
                    'descripcion' => 'Reconocimiento por impartir diplomado.',
                    'horas' => 40,
                    'modalidad' => 'Presencial',
                    'lugar' => $solicitudInstructorParte->sede ?? 'Sede del diplomado',
                    'fecha_emision' => $solicitudInstructorParte->fecha_solicitud,
                    'fecha_inicio' => $solicitudInstructorParte->inicio_realizacion ?? '2025-01-01',
                    'fecha_termino' => $solicitudInstructorParte->termino_realizacion ?? '2025-12-31',
                    'anio' => '2025'
                ];

                return view('verificacion.documento', [
                    'documento' => $documento,
                    'numeroRegistro' => $numeroRegistro,
                    'estado' => 'VÁLIDO',
                    'modulo' => 'Diplomados'
                ]);
            }
        }

        // No encontrado
        return view('verificacion.documento', [
            'documento' => null,
            'numeroRegistro' => $numeroRegistro,
            'estado' => 'INVÁLIDO',
            'modulo' => 'Diplomados'
        ]);
    }

    /**
     * Verificación general que detecta automáticamente el tipo
     */
    public function verificarDocumento($numeroRegistro)
    {
        // Limpiar el número de espacios y caracteres extraños
        $numeroRegistro = trim($numeroRegistro);

        // Detectar tipo por formato del número de registro
        if (strpos($numeroRegistro, '/I-') !== false) {
            // Es un reconocimiento de instructor interno
            return $this->verificarReconocimiento($numeroRegistro);
        }

        if (preg_match('/TNM-169-(\d+)-(\d{4})\/(\d+)$/', $numeroRegistro)) {
            // Es formato de curso interno: TNM-169-XX-YYYY/ZZ - PERO verificar si realmente existe
            $participanteInterno = $this->buscarEnCursosInternos($numeroRegistro);
            if ($participanteInterno) {
                return $this->verificarConstancia($numeroRegistro);
            }
            // Si no existe en internos, continuar buscando en otros módulos
        }

        // Buscar en capacitaciones externas por folio exacto
        $capacitacionExterna = RegistroCapacitacionesExt::where('folio', $numeroRegistro)->first();
        if ($capacitacionExterna) {
            return $this->verificarConstancia($numeroRegistro);
        }

        // Búsqueda alternativa en externa: por LIKE
        $capacitacionExternaLike = RegistroCapacitacionesExt::where('folio', 'LIKE', "%{$numeroRegistro}%")->first();
        if ($capacitacionExternaLike) {
            return $this->verificarConstancia($numeroRegistro);
        }

        // Si tiene formato TNM-169- pero no se encontró en externa, buscar solo la parte del número
        if (strpos($numeroRegistro, 'TNM-169-') === 0) {
            $parteNumero = str_replace('TNM-169-', '', $numeroRegistro);
            $capacitacionExternaParcial = RegistroCapacitacionesExt::where('folio', $parteNumero)
                ->orWhere('folio', 'LIKE', "%{$parteNumero}%")
                ->first();
            if ($capacitacionExternaParcial) {
                return $this->verificarConstancia($numeroRegistro);
            }
        }

        // Buscar en diplomados
        return $this->verificarDiploma($numeroRegistro);

        // No encontrado en ningún módulo
        return view('verificacion.documento', [
            'documento' => null,
            'numeroRegistro' => $numeroRegistro,
            'estado' => 'INVÁLIDO',
            'modulo' => 'No identificado'
        ]);
    }

    /**
     * Buscar participante en cursos internos
     */
    private function buscarEnCursosInternos($numeroRegistro)
    {
        // Verificar formato básico TNM-169-XX-YYYY/ZZ
        if (!preg_match('/TNM-169-(\d+)-(\d{4})\/(\d+)/', $numeroRegistro, $matches)) {
            return null;
        }

        // Buscar directamente por número de registro exacto
        $participante = cursos_participante::with([
            'curso.departamento'
        ])->where('numero_registro', $numeroRegistro)
          ->where('acreditado', 2)
          ->first();

        // Si no se encuentra exacto, buscar por LIKE
        if (!$participante) {
            $participante = cursos_participante::with([
                'curso.departamento'
            ])->where('numero_registro', 'LIKE', "%{$numeroRegistro}%")
              ->where('acreditado', 2)
              ->first();
        }

        return $participante;
    }

    /**
     * Buscar instructor en cursos internos
     */
    private function buscarInstructorEnCursosInternos($numeroRegistro)
    {
        // Verificar formato básico TNM-169-XX-YYYY/I-ZZ
        if (!preg_match('/TNM-169-(\d+)-(\d{4})\/I-(\d+)/', $numeroRegistro, $matches)) {
            return null;
        }

        // Buscar directamente en la tabla de instructores por número de registro
        // Primero necesitamos encontrar si existe un sistema de registro para instructores
        // Por ahora, vamos a buscar por el patrón del número de registro

        $numeroCurso = (int)$matches[1];
        $anio = (int)$matches[2];
        $numeroInstructor = (int)$matches[3];

        // Buscar curso por número y año (mantenemos esta lógica para instructores)
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
