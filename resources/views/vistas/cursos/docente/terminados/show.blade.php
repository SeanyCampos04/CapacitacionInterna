<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Detalles del curso: ') }} {{ $curso_participante->curso->nombre }}
        </h2>
    </x-slot>

    <style>
        .course-info-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 600px;
        }

        .course-info-card h5 {
            color: #1f2937;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            min-width: 140px;
        }

        .info-value {
            color: #6b7280;
            text-align: right;
            flex: 1;
        }

        .instructor-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .instructor-chip {
            background-color: #e0f2fe;
            color: #0277bd;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .btn-survey {
            background: #4f46e5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            display: inline-block;
            transition: all 0.2s ease;
            margin-top: 1.5rem;
        }

        .btn-survey:hover {
            background: #4338ca;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .survey-completed {
            background: #10b981;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            text-align: center;
            margin-top: 1.5rem;
        }
    </style>

    <div class="container mt-6 mx-auto">
        <div class="course-info-card">
            <h5>{{ $curso_participante->curso->nombre }}</h5>

            <div class="info-item">
                <span class="info-label">
                    @if ($curso_participante->curso->instructores->count() == 1)
                        Instructor:
                    @else
                        Instructores:
                    @endif
                </span>
                <div class="info-value">
                    @if ($curso_participante->curso->instructores->count() == 1)
                        @foreach ($curso_participante->curso->instructores as $instructor)
                            {{ $instructor->user->datos_generales->nombre }}
                            {{ $instructor->user->datos_generales->apellido_paterno }}
                            {{ $instructor->user->datos_generales->apellido_materno }}
                        @endforeach
                    @else
                        <div class="instructor-chips">
                            @foreach ($curso_participante->curso->instructores as $instructor)
                                <span class="instructor-chip">
                                    {{ $instructor->user->datos_generales->nombre }}
                                    {{ $instructor->user->datos_generales->apellido_paterno }}
                                    {{ $instructor->user->datos_generales->apellido_materno }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="info-item">
                <span class="info-label">Departamento:</span>
                <span class="info-value">{{ $curso_participante->curso->departamento->nombre }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Periodo:</span>
                <span class="info-value">{{ $curso_participante->curso->periodo->periodo }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Fecha de inicio:</span>
                <span class="info-value">{{ $curso_participante->curso->fdi }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Fecha de terminación:</span>
                <span class="info-value">{{ $curso_participante->curso->fdf }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Duración:</span>
                <span class="info-value">{{ $curso_participante->curso->duracion }} horas</span>
            </div>

            <div class="info-item">
                <span class="info-label">Horario:</span>
                <span class="info-value">{{ $curso_participante->curso->horario }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Modalidad:</span>
                <span class="info-value">{{ $curso_participante->curso->modalidad }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Lugar:</span>
                <span class="info-value">{{ $curso_participante->curso->lugar }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Inscritos:</span>
                <span class="info-value">
                    {{ $curso_participante->curso->cursos_participantes->count() }}/{{ $curso_participante->curso->limite_participantes }}
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    @if ($curso_participante->curso->estatus == 0)
                        <span class="text-red-600 font-semibold">Terminado</span>
                    @else
                        <span class="text-green-600 font-semibold">Activo</span>
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Calificación:</span>
                <span class="info-value">
                    @if ($curso_participante->curso->estado_calificacion == 2)
                        <span class="font-semibold">{{ $curso_participante->calificacion }}</span>
                    @else
                        <span class="text-gray-500">---</span>
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Estatus:</span>
                <span class="info-value">
                    @if ($curso_participante->curso->estado_calificacion == 2)
                        @if ($curso_participante->acreditado == 2)
                            <span class="text-green-600 font-semibold">Acreditado</span>
                        @elseif ($curso_participante->acreditado == 1)
                            <span class="text-red-600 font-semibold">No Acreditado</span>
                        @else
                            <span class="text-blue-600 font-semibold">Sin Calificar</span>
                        @endif
                    @else
                        <span class="text-blue-600 font-semibold">Sin Calificar</span>
                    @endif
                </span>
            </div>

            <!-- Botón para contestar la encuesta -->
            <div class="text-center">
                @if (!$encuesta)
                    <a href="{{ route('encuesta.formulario', ['curso_id' => $curso_participante->curso->id]) }}"
                       class="btn-survey">
                        Contestar Encuesta
                    </a>
                @else
                    <div class="survey-completed">
                        Encuesta contestada
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
