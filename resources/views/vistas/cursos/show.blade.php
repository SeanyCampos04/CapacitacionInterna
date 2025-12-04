<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Detalles del curso: ') }} {{ $curso->nombre }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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

        .ficha-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .ficha-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .actions-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e5e7eb;
        }

        .btn-action {
            width: 40px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            margin: 0 3px;
            text-decoration: none;
            font-size: 1rem;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .btn-view { background: #3b82f6; color: white; }
        .btn-view:hover { background: #2563eb; color: white; }

        .btn-edit { background: #10b981; color: white; }
        .btn-edit:hover { background: #059669; color: white; }

        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; color: white; }

        .btn-indigo { background: #4f46e5; color: white; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .btn-indigo:hover { background: #4338ca; color: white; }

        .btn-green { background: #10b981; color: white; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .btn-green:hover { background: #059669; color: white; }

        .btn-red { background: #ef4444; color: white; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .btn-red:hover { background: #dc2626; color: white; }

        .btn-yellow { background: #f59e0b; color: white; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .btn-yellow:hover { background: #d97706; color: white; }

        .table-instructores th {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        .table-participantes th {
            background-color: #10b981 !important;
            color: white !important;
        }
    </style>

    <div class="container mt-6 mx-auto">
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

            <div class="info-item">
                <span class="info-label">Inscritos:</span>
                <span class="info-value">{{ $curso->cursos_participantes->count() }}/{{ $curso->limite_participantes }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    @if ($curso->estatus == 1)
                        <span class="text-green-600 font-semibold">Disponible</span>
                    @else
                        <span class="text-red-600 font-semibold">Terminado</span>
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Ficha Técnica Actual:</span>
                <span class="info-value">
                    @if ($curso->ficha_tecnica)
                        <a href="{{ asset('uploads/' . $curso->ficha_tecnica) }}"
                           target="_blank"
                           class="ficha-link">
                           Ver ficha técnica actual (PDF)
                        </a>
                    @else
                        <span class="text-gray-500">No disponible</span>
                    @endif
                </span>
            </div>

            @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
            <div class="actions-container">
                                <form action="{{ route('cursos.edit', $curso->id) }}" method="GET" class="">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" class="btn btn-indigo py-2 px-4 rounded text-nowrap" style="font-size: 1.1rem;">Editar</button>
                                </form>
                                @if ($curso->estatus == 0)
                                    <form action="{{ route('iniciar_cursos.update', ['curso' => $curso]) }}"
                                        method="POST" class="">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-indigo py-2 px-4 rounded text-nowrap" style="font-size: 1.1rem;">Iniciar curso</button>
                                    </form>
                                @endif

                                @if ($curso->estatus == 1)
                                    <form action="{{ route('terminar_cursos.update', ['curso' => $curso]) }}"
                                        method="POST" class="">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-indigo py-2 px-4 rounded text-nowrap" style="font-size: 1.1rem;"
                                            onclick="return confirm('¿Estás seguro de que quieres terminar este curso?');">Terminar curso</button>
                                    </form>
                                @endif

                                @if ($curso->estatus == 0)
                                    <form action="{{ route('encuesta.resultados', $curso->id) }}" method="GET"
                                        class="">
                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="btn btn-indigo py-2 px-4 rounded text-nowrap" style="font-size: 1.1rem;">Ver resultados</button>
                                    </form>
                                @endif

                                @if ($curso->estado_calificacion == 0 or $curso->estatus == 1)
                                    <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST"
                                        class="">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-red py-2 px-4 rounded text-nowrap" style="font-size: 1.1rem;"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este curso?');">Eliminar</button>
                                    </form>
                                @endif

                                <form action="{{ route('curso.pdf', $curso->id) }}" method="get" class="">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" class="btn btn-green py-2 px-4 rounded text-nowrap" style="font-size: 1.1rem;">Generar pdf</button>
                                </form>
                            </div>
                        @endif
        </div>
    </div>

    <!-- Tabla de Instructores -->
    @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
        <div class="container mt-6 mx-auto px-4">
            <h1 class="text-center text-xl mb-4"><strong>Instructor del curso</strong></h1>
            <div class="bg-white shadow-lg rounded-lg p-4">
                <div class="table-responsive">
                    <table class="table table-hover table-instructores">
                        <thead>
                            <tr>
                                <th class="text-center">Correo</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Departamento</th>
                                <th class="text-center">Reconocimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($curso->instructores as $instructor)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $instructor->user->email }}</td>
                                    <td class="text-center">
                                        {{ $instructor->user->datos_generales->nombre }}
                                        {{ $instructor->user->datos_generales->apellido_paterno }}
                                        {{ $instructor->user->datos_generales->apellido_materno }}
                                    </td>
                                    <td class="text-center">
                                        {{ $instructor->user->datos_generales->departamento->nombre }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('curso.reconocimiento.instructor', ['curso_id' => $curso->id, 'instructor_id' => $instructor->id]) }}"
                                           class="btn-action btn-view" target="_blank" title="Reconocimiento">
                                            <i class="fas fa-trophy"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="container mt-6 mx-auto px-6" style="max-width: 90%; margin-bottom: 2rem;">
        <h1 class="text-center text-xl mb-4"><strong>Docentes inscritos</strong></h1>
        <div class="bg-white shadow-lg rounded-lg p-4 mx-3" style="min-height: 200px; margin: 0 1rem;">
            <div class="table-responsive">
                <table class="table table-hover table-participantes">
                    <thead>
                        <tr>
                            <th class="text-center">Correo</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Departamento</th>
                            <th class="text-center">Calificación</th>
                            <th class="text-center">Estatus</th>
                            @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                <th class="text-center">Constancia</th>
                                <th class="text-center">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ParticipantesOrdenados as $participanteInscrito)
                            <tr class="align-middle">
                                <td class="text-center">{{ $participanteInscrito->participante->user->email }}</td>
                                <td class="text-center">
                                    {{ $participanteInscrito->participante->user->datos_generales->apellido_paterno }}
                                    {{ $participanteInscrito->participante->user->datos_generales->apellido_materno }}
                                    {{ $participanteInscrito->participante->user->datos_generales->nombre }}
                                </td>
                                <td class="text-center">
                                    {{ $participanteInscrito->participante->user->datos_generales->departamento->nombre }}
                                </td>
                                @if ($curso->estado_calificacion != 0)
                                    <td class="text-center">
                                        @if ($participanteInscrito->calificacion)
                                            {{ $participanteInscrito->calificacion }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    @if ($participanteInscrito->acreditado == 2)
                                        <td class="text-center text-success">Acreditado</td>
                                    @endif
                                    @if ($participanteInscrito->acreditado == 1)
                                        <td class="text-center text-danger">No Acreditado</td>
                                    @endif
                                    @if ($participanteInscrito->acreditado == 0)
                                        <td class="text-center text-primary">Sin Calificar</td>
                                    @endif
                                @else
                                    <td class="text-center">-</td>
                                    <td class="text-center text-primary">Sin Calificar</td>
                                @endif
                                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <!-- Columna Constancia -->
                                    <td class="text-center">
                                        @if ($participanteInscrito->acreditado == 2)
                                            <a href="{{ route('curso.constancia', ['curso_id' => $curso->id, 'participante_id' => $participanteInscrito->id]) }}"
                                               class="btn-action btn-edit" target="_blank" title="Constancia">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <!-- Columna Acciones -->
                                    @if ($curso->estatus == 1 || $curso->estado_calificacion == 0)
                                        <td class="text-center">
                                            <form action="{{ route('curso_participante.destroy', ['participanteInscrito' => $participanteInscrito]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="Eliminar participante"
                                                    onclick="return confirm('¿Estás seguro de que quieres eliminar este docente del curso?');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @else
                                        <td class="text-center">-</td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 me-2">
                <div class="d-flex justify-content-end gap-2">
                    @if ($curso->estado_calificacion == 1 and $curso->estatus == 0)
                        @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                            <form action="{{ route('admin.entregar_calificacion', $curso->id) }}" method="GET" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-green py-2 px-4 rounded"
                                    onclick="return confirm('¿Estás seguro de que quieres subir las calificaciones? ⚠️UNA VEZ SUBIDAS EL INSTRUCTOR NO PODRÁ HACER CAMBIOS⚠️');">
                                    Subir calificacion
                                </button>
                            </form>

                            <form action="{{ route('admin.devolver_calificacion', $curso->id) }}" method="GET" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-yellow py-2 px-4 rounded"
                                    onclick="return confirm('¿Estás seguro de que quieres devolver las calificaciones al instructor?');">
                                    Devolver calificacion
                                </button>
                            </form>
                        @endif
                    @elseif ($curso->estado_calificacion == 2)
                        <span class="text-success fw-bold">Calificaciones subidas</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</x-app-layout>
