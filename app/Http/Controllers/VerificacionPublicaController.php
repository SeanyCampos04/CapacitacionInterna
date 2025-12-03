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
                'tipo_documento' => 'Constancia de Capacitación',
                'nombre_completo' => $capacitacionExterna->nombre . ' ' . $capacitacionExterna->apellido_paterno . ' ' . $capacitacionExterna->apellido_materno,
                'nombre_programa' => $capacitacionExterna->nombre_capacitacion,
                'descripcion' => $descripcionCompleta,
                'horas' => $capacitacionExterna->horas,
                'modalidad' => $capacitacionExterna->tipo_capacitacion,
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
            // Obtener nombre del participante directamente desde la base de datos por seguridad
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

            // Obtener datos del curso directamente
            $curso = Curso::find($participante->curso_id);
            if (!$curso) {
                return view('verificacion.documento', [
                    'documento' => null,
                    'numeroRegistro' => $numeroRegistro,
                    'estado' => 'INVÁLIDO',
                    'modulo' => 'Curso no encontrado'
                ]);
            }

            // Obtener datos de instructores
            $instructores = DB::table('cursos_instructores')
                ->join('instructores', 'cursos_instructores.instructore_id', '=', 'instructores.id')
                ->join('users', 'instructores.user_id', '=', 'users.id')
                ->join('datos_generales', 'users.id', '=', 'datos_generales.user_id')
                ->where('cursos_instructores.curso_id', $curso->id)
                ->select('datos_generales.nombre', 'datos_generales.apellido_paterno', 'datos_generales.apellido_materno')
                ->get();

            $nombresInstructores = $instructores->map(function($instructor) {
                return trim($instructor->nombre . ' ' . $instructor->apellido_paterno . ' ' . $instructor->apellido_materno);
            })->implode(', ');

            if (empty($nombresInstructores)) {
                $nombresInstructores = 'Información de instructores disponible';
            }

            // Generar descripción completa como en la constancia
            $fechaInicio = Carbon::parse($curso->fdi);
            $fechaFin = Carbon::parse($curso->fdf);

            $descripcionCompleta = "POR PARTICIPAR Y ACREDITAR SATISFACTORIAMENTE EL CURSO DE CAPACITACIÓN \"" .
                strtoupper($curso->nombre) . "\" IMPARTIDO POR " .
                strtoupper($nombresInstructores) . " DEL " .
                $fechaInicio->format('d') . " DE " . strtoupper($fechaInicio->translatedFormat('F')) .
                " AL " . $fechaFin->format('d') . " DE " . strtoupper($fechaFin->translatedFormat('F')) .
                " DEL " . $fechaInicio->format('Y') . ", CON UNA DURACIÓN DE " .
                $curso->duracion . " HORAS, CON LA MODALIDAD " .
                strtoupper($curso->modalidad) . ", REALIZADO EN " .
                strtoupper($curso->lugar) . ", EN EL DEPARTAMENTO DE " .
                strtoupper($curso->departamento->nombre ?? 'SIN DEPARTAMENTO') .
                ", CON LA CALIFICACIÓN OBTENIDA DE " . ($participante->calificacion ?? '100') . ".";

            $documento = [
                'tipo_documento' => 'Constancia de Curso',
                'nombre_completo' => $nombreCompleto,
                'nombre_programa' => $curso->nombre,
                'descripcion' => $descripcionCompleta,
                'horas' => $curso->duracion ?? 0,
                'modalidad' => $curso->modalidad ?? 'Sin modalidad',
                'lugar' => $curso->lugar ?? 'Sin lugar',
                'fecha_emision' => Carbon::now(),
                'departamento' => $curso->departamento->nombre ?? 'Sin departamento',
                'fecha_inicio' => $curso->fdi ?? null,
                'fecha_termino' => $curso->fdf ?? null,
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
        // Primero buscar instructor en cursos internos
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
                'descripcion' => 'Por impartir el curso de capacitación "' . strtoupper($instructor['curso']->nombre) . '" del ' .
                               \Carbon\Carbon::parse($instructor['curso']->fdi)->format('d') . ' de ' . \Carbon\Carbon::parse($instructor['curso']->fdi)->translatedFormat('F') .
                               ' al ' . \Carbon\Carbon::parse($instructor['curso']->fdf)->format('d') . ' de ' . \Carbon\Carbon::parse($instructor['curso']->fdf)->translatedFormat('F') .
                               ' del ' . \Carbon\Carbon::parse($instructor['curso']->fdi)->format('Y') . ', con una duración de ' . $instructor['curso']->duracion . ' horas.',
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

        // Si no se encuentra en cursos internos, buscar en diplomados
        $instructorDiplomado = $this->buscarInstructorEnDiplomados($numeroRegistro);

        if ($instructorDiplomado) {
            $documento = [
                'tipo_documento' => 'Reconocimiento de Instructor de Diplomado',
                'nombre_completo' => $instructorDiplomado['nombre'],
                'nombre_programa' => $instructorDiplomado['diplomado_nombre'],
                'descripcion' => 'Por impartir como instructor el diplomado "' . strtoupper($instructorDiplomado['diplomado_nombre']) . '" realizado del ' .
                               \Carbon\Carbon::parse($instructorDiplomado['fecha_inicio'])->format('d/m/Y') . ' al ' .
                               \Carbon\Carbon::parse($instructorDiplomado['fecha_termino'])->format('d/m/Y') . '.',
                'horas' => $instructorDiplomado['duracion_dias'] * 8, // Estimación de horas
                'modalidad' => 'Presencial',
                'lugar' => $instructorDiplomado['sede'],
                'fecha_emision' => $instructorDiplomado['fecha_solicitud'],
                'fecha_inicio' => $instructorDiplomado['fecha_inicio'],
                'fecha_termino' => $instructorDiplomado['fecha_termino'],
                'anio' => \Carbon\Carbon::parse($instructorDiplomado['fecha_inicio'])->format('Y')
            ];

            return view('verificacion.documento', [
                'documento' => $documento,
                'numeroRegistro' => $numeroRegistro,
                'estado' => 'VÁLIDO',
                'modulo' => 'Diplomados'
            ]);
        }

        // No encontrado en ningún lado
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
                    'diplomados.nombre as diplomado_nombre',
                    'diplomados.tipo',
                    'diplomados.sede',
                    'diplomados.inicio_realizacion',
                    'diplomados.termino_realizacion',
                    'datos_generales.nombre as participante_nombre',
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
            $nombreCompleto = trim($solicitudParticipante->participante_nombre . ' ' .
                                $solicitudParticipante->apellido_paterno . ' ' .
                                $solicitudParticipante->apellido_materno);

            // Calcular duración en días
            $duracionDias = \Carbon\Carbon::parse($solicitudParticipante->inicio_realizacion)
                ->diffInDays(\Carbon\Carbon::parse($solicitudParticipante->termino_realizacion)) + 1;

            $documento = [
                'tipo_documento' => 'Diploma',
                'nombre_completo' => $nombreCompleto,
                'nombre_programa' => $solicitudParticipante->diplomado_nombre,
                'descripcion' => 'Por participar y acreditar satisfactoriamente en el diplomado "' .
                               strtoupper($solicitudParticipante->diplomado_nombre) . '" realizado del ' .
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
                    'diplomados.nombre as diplomado_nombre',
                    'diplomados.tipo',
                    'diplomados.sede',
                    'diplomados.inicio_realizacion',
                    'diplomados.termino_realizacion',
                    'datos_generales.nombre as instructor_nombre',
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
            $nombreCompleto = trim($solicitudInstructor->instructor_nombre . ' ' .
                                $solicitudInstructor->apellido_paterno . ' ' .
                                $solicitudInstructor->apellido_materno);

            $duracionDias = \Carbon\Carbon::parse($solicitudInstructor->inicio_realizacion)
                ->diffInDays(\Carbon\Carbon::parse($solicitudInstructor->termino_realizacion)) + 1;

            $documento = [
                'tipo_documento' => 'Reconocimiento de Instructor de Diplomado',
                'nombre_completo' => $nombreCompleto,
                'nombre_programa' => $solicitudInstructor->diplomado_nombre,
                'descripcion' => 'Por impartir como instructor el diplomado "' .
                               strtoupper($solicitudInstructor->diplomado_nombre) . '" realizado del ' .
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
        // Verificar formato básico TNM-169-XX-YYYY/ZZ (con o sin ceros)
        if (!preg_match('/TNM-169-(\d+)-(\d{4})\/(\d+)/', $numeroRegistro, $matches)) {
            return null;
        }

        // Buscar directamente por número de registro exacto
        $participante = cursos_participante::with([
            'curso.departamento',
            'participante.user.datos_generales'
        ])->where('numero_registro', $numeroRegistro)
          ->where('acreditado', 2)
          ->first();

        // Si no se encuentra exacto, buscar por LIKE
        if (!$participante) {
            $participante = cursos_participante::with([
                'curso.departamento',
                'participante.user.datos_generales'
            ])->where('numero_registro', 'LIKE', "%{$numeroRegistro}%")
              ->where('acreditado', 2)
              ->first();
        }

        // Si aún no se encuentra, intentar con variaciones de formato (con/sin ceros)
        if (!$participante) {
            $numeroCurso = intval($matches[1]);
            $anio = $matches[2];
            $numeroParticipante = intval($matches[3]);

            // Generar posibles variaciones del formato
            $variaciones = [
                sprintf('TNM-169-%d-%s/%d', $numeroCurso, $anio, $numeroParticipante),
                sprintf('TNM-169-%02d-%s/%02d', $numeroCurso, $anio, $numeroParticipante),
                sprintf('TNM-169-%02d-%s/%03d', $numeroCurso, $anio, $numeroParticipante),
                sprintf('TNM-169-%d-%s/%02d', $numeroCurso, $anio, $numeroParticipante),
                sprintf('TNM-169-%d-%s/%03d', $numeroCurso, $anio, $numeroParticipante)
            ];

            foreach ($variaciones as $variacion) {
                $participante = cursos_participante::with([
                    'curso.departamento',
                    'participante.user.datos_generales'
                ])->where('numero_registro', $variacion)
                  ->where('acreditado', 2)
                  ->first();

                if ($participante) {
                    break;
                }
            }
        }

        return $participante;
    }

    /**
     * Buscar instructor en cursos internos
     */
    private function buscarInstructorEnCursosInternos($numeroRegistro)
    {
        // Verificar formato básico TNM-169-XX-YYYY/I-ZZ (con o sin ceros)
        if (!preg_match('/TNM-169-(\d+)-(\d{4})\/I-(\d+)/', $numeroRegistro, $matches)) {
            return null;
        }

        // Buscar en la tabla cursos_instructores por número de registro
        $cursoInstructor = DB::table('cursos_instructores')
            ->join('cursos', 'cursos_instructores.curso_id', '=', 'cursos.id')
            ->join('instructores', 'cursos_instructores.instructore_id', '=', 'instructores.id')
            ->join('users', 'instructores.user_id', '=', 'users.id')
            ->join('datos_generales', 'users.id', '=', 'datos_generales.user_id')
            ->leftJoin('departamentos', 'cursos.departamento_id', '=', 'departamentos.id')
            ->where('cursos_instructores.numero_registro', $numeroRegistro)
            ->select(
                'cursos.id',
                'cursos.nombre as curso_nombre',
                'cursos.duracion',
                'cursos.modalidad',
                'cursos.lugar',
                'cursos.fdi',
                'cursos.fdf',
                'datos_generales.nombre as instructor_nombre',
                'datos_generales.apellido_paterno',
                'datos_generales.apellido_materno',
                'departamentos.nombre as departamento_nombre'
            )
            ->first();

        if ($cursoInstructor) {
            // Crear objeto curso simulado
            $curso = (object) [
                'id' => $cursoInstructor->id,
                'nombre' => $cursoInstructor->curso_nombre,
                'duracion' => $cursoInstructor->duracion,
                'modalidad' => $cursoInstructor->modalidad,
                'lugar' => $cursoInstructor->lugar,
                'fdi' => $cursoInstructor->fdi,
                'fdf' => $cursoInstructor->fdf,
                'departamento' => (object) [
                    'nombre' => $cursoInstructor->departamento_nombre
                ]
            ];

            $datosInstructor = (object) [
                'nombre' => $cursoInstructor->instructor_nombre,
                'apellido_paterno' => $cursoInstructor->apellido_paterno,
                'apellido_materno' => $cursoInstructor->apellido_materno
            ];

            return [
                'curso' => $curso,
                'instructor_datos' => $datosInstructor
            ];
        }

        // Si no se encuentra exacto, probar con variaciones de formato
        $numeroCurso = (int)$matches[1];
        $anio = (int)$matches[2];
        $numeroInstructor = (int)$matches[3];

        $variaciones = [
            sprintf('TNM-169-%d-%s/I-%d', $numeroCurso, $anio, $numeroInstructor),
            sprintf('TNM-169-%02d-%s/I-%02d', $numeroCurso, $anio, $numeroInstructor),
            sprintf('TNM-169-%02d-%s/I-%03d', $numeroCurso, $anio, $numeroInstructor),
            sprintf('TNM-169-%d-%s/I-%02d', $numeroCurso, $anio, $numeroInstructor),
            sprintf('TNM-169-%d-%s/I-%03d', $numeroCurso, $anio, $numeroInstructor)
        ];

        foreach ($variaciones as $variacion) {
            $cursoInstructor = DB::table('cursos_instructores')
                ->join('cursos', 'cursos_instructores.curso_id', '=', 'cursos.id')
                ->join('instructores', 'cursos_instructores.instructore_id', '=', 'instructores.id')
                ->join('users', 'instructores.user_id', '=', 'users.id')
                ->join('datos_generales', 'users.id', '=', 'datos_generales.user_id')
                ->leftJoin('departamentos', 'cursos.departamento_id', '=', 'departamentos.id')
                ->where('cursos_instructores.numero_registro', $variacion)
                ->select(
                    'cursos.id',
                    'cursos.nombre as curso_nombre',
                    'cursos.duracion',
                    'cursos.modalidad',
                    'cursos.lugar',
                    'cursos.fdi',
                    'cursos.fdf',
                    'datos_generales.nombre as instructor_nombre',
                    'datos_generales.apellido_paterno',
                    'datos_generales.apellido_materno',
                    'departamentos.nombre as departamento_nombre'
                )
                ->first();

            if ($cursoInstructor) {
                $curso = (object) [
                    'id' => $cursoInstructor->id,
                    'nombre' => $cursoInstructor->curso_nombre,
                    'duracion' => $cursoInstructor->duracion,
                    'modalidad' => $cursoInstructor->modalidad,
                    'lugar' => $cursoInstructor->lugar,
                    'fdi' => $cursoInstructor->fdi,
                    'fdf' => $cursoInstructor->fdf,
                    'departamento' => (object) [
                        'nombre' => $cursoInstructor->departamento_nombre
                    ]
                ];

                $datosInstructor = (object) [
                    'nombre' => $cursoInstructor->instructor_nombre,
                    'apellido_paterno' => $cursoInstructor->apellido_paterno,
                    'apellido_materno' => $cursoInstructor->apellido_materno
                ];

                return [
                    'curso' => $curso,
                    'instructor_datos' => $datosInstructor
                ];
            }
        }

        return null;
    }

    /**
     * Buscar instructor en diplomados
     */
    private function buscarInstructorEnDiplomados($numeroRegistro)
    {
        // Buscar directamente en solicitud_instructores
        $solicitudInstructor = DB::table('solicitud_instructores')
            ->join('diplomados', 'solicitud_instructores.diplomado_id', '=', 'diplomados.id')
            ->leftJoin('instructores', 'solicitud_instructores.instructore_id', '=', 'instructores.id')
            ->leftJoin('users', 'instructores.user_id', '=', 'users.id')
            ->leftJoin('datos_generales', 'users.id', '=', 'datos_generales.user_id')
            ->where('solicitud_instructores.numero_registro', $numeroRegistro)
            ->where('solicitud_instructores.estatus', 2) // Solo aprobados
            ->select(
                'diplomados.nombre as diplomado_nombre',
                'diplomados.sede',
                'diplomados.inicio_realizacion as fecha_inicio',
                'diplomados.termino_realizacion as fecha_termino',
                'datos_generales.nombre',
                'datos_generales.apellido_paterno',
                'datos_generales.apellido_materno',
                'solicitud_instructores.created_at as fecha_solicitud'
            )
            ->first();

        if ($solicitudInstructor) {
            $nombreCompleto = trim($solicitudInstructor->nombre . ' ' .
                                $solicitudInstructor->apellido_paterno . ' ' .
                                $solicitudInstructor->apellido_materno);

            $duracionDias = \Carbon\Carbon::parse($solicitudInstructor->fecha_inicio)
                ->diffInDays(\Carbon\Carbon::parse($solicitudInstructor->fecha_termino)) + 1;

            return [
                'nombre' => $nombreCompleto,
                'diplomado_nombre' => $solicitudInstructor->diplomado_nombre,
                'sede' => $solicitudInstructor->sede,
                'fecha_inicio' => $solicitudInstructor->fecha_inicio,
                'fecha_termino' => $solicitudInstructor->fecha_termino,
                'fecha_solicitud' => $solicitudInstructor->fecha_solicitud,
                'duracion_dias' => $duracionDias
            ];
        }

        // Intentar búsqueda con LIKE si no encuentra exacto
        $solicitudInstructorLike = DB::table('solicitud_instructores')
            ->join('diplomados', 'solicitud_instructores.diplomado_id', '=', 'diplomados.id')
            ->leftJoin('instructores', 'solicitud_instructores.instructore_id', '=', 'instructores.id')
            ->leftJoin('users', 'instructores.user_id', '=', 'users.id')
            ->leftJoin('datos_generales', 'users.id', '=', 'datos_generales.user_id')
            ->where('solicitud_instructores.numero_registro', 'LIKE', "%{$numeroRegistro}%")
            ->where('solicitud_instructores.estatus', 2)
            ->select(
                'diplomados.nombre as diplomado_nombre',
                'diplomados.sede',
                'diplomados.inicio_realizacion as fecha_inicio',
                'diplomados.termino_realizacion as fecha_termino',
                'datos_generales.nombre',
                'datos_generales.apellido_paterno',
                'datos_generales.apellido_materno',
                'solicitud_instructores.created_at as fecha_solicitud'
            )
            ->first();

        if ($solicitudInstructorLike) {
            $nombreCompleto = trim($solicitudInstructorLike->nombre . ' ' .
                                $solicitudInstructorLike->apellido_paterno . ' ' .
                                $solicitudInstructorLike->apellido_materno);

            $duracionDias = \Carbon\Carbon::parse($solicitudInstructorLike->fecha_inicio)
                ->diffInDays(\Carbon\Carbon::parse($solicitudInstructorLike->fecha_termino)) + 1;

            return [
                'nombre' => $nombreCompleto,
                'diplomado_nombre' => $solicitudInstructorLike->diplomado_nombre,
                'sede' => $solicitudInstructorLike->sede,
                'fecha_inicio' => $solicitudInstructorLike->fecha_inicio,
                'fecha_termino' => $solicitudInstructorLike->fecha_termino,
                'fecha_solicitud' => $solicitudInstructorLike->fecha_solicitud,
                'duracion_dias' => $duracionDias
            ];
        }

        return null;
    }
}
