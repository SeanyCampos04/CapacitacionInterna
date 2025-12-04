<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cursos inscritos') }}
        </h2>
    </x-slot>

    <style>
        .course-info-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin: 1rem;
            max-width: 400px;
            min-height: 500px;
            display: flex;
            flex-direction: column;
        }

        .course-info-card h5 {
            color: #1f2937;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            line-height: 1.3;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item:last-of-type {
            border-bottom: none;
            margin-bottom: 1rem;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            min-width: 100px;
            font-size: 0.9rem;
        }

        .info-value {
            color: #6b7280;
            text-align: right;
            flex: 1;
            font-size: 0.9rem;
            line-height: 1.4;
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
            font-size: 0.8rem;
            font-weight: 500;
        }

        .btn-exit {
            background: #ef4444;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
            margin-top: auto;
        }

        .btn-exit:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .courses-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            padding: 2rem 1rem;
        }
    </style>

    <div class="container mt-6 mx-auto">
        <div class="courses-container">
            @if ($cursosCursando->isEmpty())
                <div class="w-full text-center p-4">
                    <p class="text-lg font-semibold text-gray-500">No hay cursos disponibles en este momento.</p>
                </div>
            @else
                @foreach ($cursosCursando as $cursoCursando)
                    @if ($cursoCursando->curso->estatus == 1)
                        <div class="course-info-card">
                            <h5>{{ $cursoCursando->curso->nombre }}</h5>

                            <div class="info-item">
                                <span class="info-label">
                                    @if ($cursoCursando->curso->instructores->count() == 1)
                                        Instructor:
                                    @else
                                        Instructores:
                                    @endif
                                </span>
                                <div class="info-value">
                                    @if ($cursoCursando->curso->instructores->count() == 1)
                                        @foreach ($cursoCursando->curso->instructores as $instructor)
                                            {{ $instructor->user->datos_generales->nombre }}
                                            {{ $instructor->user->datos_generales->apellido_paterno }}
                                            {{ $instructor->user->datos_generales->apellido_materno }}
                                        @endforeach
                                    @else
                                        <div class="instructor-chips">
                                            @foreach ($cursoCursando->curso->instructores as $instructor)
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
                                <span class="info-value">{{ $cursoCursando->curso->departamento->nombre }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Fecha de inicio:</span>
                                <span class="info-value">{{ $cursoCursando->curso->fdi }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Fecha de terminación:</span>
                                <span class="info-value">{{ $cursoCursando->curso->fdf }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Periodo:</span>
                                <span class="info-value">{{ $cursoCursando->curso->periodo->periodo }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Duración:</span>
                                <span class="info-value">{{ $cursoCursando->curso->duracion }} horas</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Horario:</span>
                                <span class="info-value">{{ $cursoCursando->curso->horario }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Modalidad:</span>
                                <span class="info-value">{{ $cursoCursando->curso->modalidad }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Lugar:</span>
                                <span class="info-value">{{ $cursoCursando->curso->lugar }}</span>
                            </div>

                            <!-- Botón para salir del curso -->
                            <form action="{{ route('curso_participante.destroy', ['participanteInscrito' => $cursoCursando]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-exit"
                                        onclick="return confirm('¿Estás seguro de que deseas salir del curso?');">
                                    Salir del curso
                                </button>
                            </form>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>


</x-app-layout>
