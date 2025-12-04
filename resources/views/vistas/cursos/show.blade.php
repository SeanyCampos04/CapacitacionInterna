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
        <div class="flex flex-wrap justify-center">
            <div class="mb-4 mx-2 w-full md:w-1/2 lg:w-5/12 xl:w-1/3">
                <div class="bg-white rounded-lg overflow-hidden shadow-md h-full">
                    <div class="p-4">
                        <h5 class="text-2xl font-bold mb-3">{{ $curso->nombre }}</h5>
                        <h6 class="text-base font-semibold text-gray-700 mb-2">
                            <strong>
                                @if ($curso->instructores->count() == 1)
                                    Instructor:
                                    @foreach ($curso->instructores as $instructor)
                                        <span class="font-medium">
                                            {{ $instructor->user->datos_generales->nombre }}
                                            {{ $instructor->user->datos_generales->apellido_paterno }}
                                            {{ $instructor->user->datos_generales->apellido_materno }}
                                        </span>
                                    @endforeach
                                @else
                                    Instructores:
                                    <div class="flex flex-wrap">
                                        @foreach ($curso->instructores as $instructor)
                                            <div class="bg-gray-100 p-2 m-1 rounded-lg shadow-sm">
                                                {{ $instructor->user->datos_generales->nombre }}
                                                {{ $instructor->user->datos_generales->apellido_paterno }}
                                                {{ $instructor->user->datos_generales->apellido_materno }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </strong>
                        </h6>
                        <p class="text-base text-gray-700 mb-1"><strong>Departamento:</strong> {{ $curso->departamento->nombre }}</p>
                        <p class="text-base text-gray-700 mb-1"><strong>Periodo:</strong> {{ $curso->periodo->periodo }}</p>
                        <p class="text-base text-gray-700 mb-1"><strong>Fecha de inicio:</strong> {{ $curso->fdi }}</p>
                        <p class="text-base text-gray-700 mb-1"><strong>Fecha de terminación:</strong> {{ $curso->fdf }}</p>
                        <p class="text-base text-gray-700 mb-1"><strong>Duración:</strong> {{ $curso->duracion }} horas</p>
                        <p class="text-base text-gray-700 mb-1"><strong>Horario:</strong> {{ $curso->horario }}</p>
                        <p class="text-base text-gray-700 mb-1"><strong>Modalidad:</strong> {{ $curso->modalidad }}</p>
                        <p class="text-base text-gray-700 mb-1"><strong>Lugar:</strong> {{ $curso->lugar }}</p>
                        <p class="text-base text-gray-700 mb-1"><strong>Inscritos:</strong> {{ $curso->cursos_participantes->count() }}/{{ $curso->limite_participantes }}</p>
                        <p class="text-base text-gray-700 mb-3"><strong>Estado:</strong>
                            @if ($curso->estatus == 1)
                                Disponible
                            @else
                                Terminado
                            @endif
                        </p>

                        <!-- Mostrar ficha técnica existente -->
                        @if ($curso->ficha_tecnica)
                            <div class="mb-4">
                                <p class="text-sm text-gray-700"><strong>Ficha Técnica Actual:</strong></p>
                                <a href="{{ asset('uploads/' . $curso->ficha_tecnica) }}"
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800">
                                   Ver ficha técnica actual (PDF)
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-gray-700">No se ha subido ninguna ficha técnica para este curso.</p>
                        @endif

                        @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))

                            <div class="d-flex flex-wrap justify-content-center gap-3">
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
            </div>
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
