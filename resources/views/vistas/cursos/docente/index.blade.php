<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cursos disponibles') }}
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

        .btn-register {
            background: #4f46e5;
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

        .btn-register:hover {
            background: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-disabled {
            background: #9ca3af;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            cursor: not-allowed;
            width: 100%;
            margin-top: auto;
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
            @if ($cursos->isEmpty())
                <div class="w-full text-center p-4">
                    <p class="text-lg font-semibold text-gray-500">No hay cursos disponibles en este momento.</p>
                </div>
            @else
                @foreach ($cursos as $curso)
                    <div class="course-info-card">
                        <h5>{{ $curso->nombre }}</h5>

                        <div class="info-item">
                            <span class="info-label">
                                @if ($curso->instructores->count() == 1)
                                    Instructor:
                                @else
                                    Instructores:
                                @endif
                            </span>
                            <div class="info-value">
                                @if ($curso->instructores->count() == 1)
                                    @foreach ($curso->instructores as $instructor)
                                        {{ $instructor->user->datos_generales->nombre }}
                                        {{ $instructor->user->datos_generales->apellido_paterno }}
                                        {{ $instructor->user->datos_generales->apellido_materno }}
                                    @endforeach
                                @else
                                    <div class="instructor-chips">
                                        @foreach ($curso->instructores as $instructor)
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
                            <span class="info-value">{{ $curso->departamento->nombre }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Periodo:</span>
                            <span class="info-value">{{ $curso->periodo->periodo }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Fecha de inicio:</span>
                            <span class="info-value">{{ $curso->fdi }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Fecha de terminación:</span>
                            <span class="info-value">{{ $curso->fdf }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Duración:</span>
                            <span class="info-value">{{ $curso->duracion }} horas</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Horario:</span>
                            <span class="info-value">{{ $curso->horario }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Modalidad:</span>
                            <span class="info-value">{{ $curso->modalidad }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Lugar:</span>
                            <span class="info-value">{{ $curso->lugar }}</span>
                        </div>

                        @php
                            // Verificar si el usuario actual es instructor de este curso
                            $user = auth()->user();
                            $esInstructorDelCurso = false;

                            // Verificar si el usuario tiene rol de instructor usando la tabla user_roles directamente
                            $user_role_ids = \Illuminate\Support\Facades\DB::table('user_roles')
                                ->where('user_id', $user->id)
                                ->pluck('role_id')
                                ->toArray();

                            $is_instructor = in_array(5, $user_role_ids); // 5 es el ID del rol Instructor

                            if ($is_instructor) {
                                $esInstructorDelCurso = $curso->instructores()
                                    ->whereHas('user', function ($query) use ($user) {
                                        $query->where('id', $user->id);
                                    })
                                    ->exists();
                            }
                        @endphp

                        @if($esInstructorDelCurso)
                            <button type="button" class="btn-disabled" disabled title="No puedes inscribirte a un curso que tú impartes">
                                Eres instructor de este curso
                            </button>
                        @else
                            <form action="{{ route('curso_participante.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                <button type="submit" class="btn-register">
                                    Registrar
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>


</x-app-layout>
