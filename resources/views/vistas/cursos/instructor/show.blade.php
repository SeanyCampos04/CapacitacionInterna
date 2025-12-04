<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Detalles del curso: ') }} {{ $curso->nombre }}
        </h2>
    </x-slot>

    <!-- Font Awesome -->
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
            min-width: 120px;
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

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 600px;
        }

        .form-container h3 {
            color: #1f2937;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .btn-upload {
            background: #4f46e5;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-upload:hover {
            background: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .optimized-container {
            width: 95%;
            max-width: 1200px !important;
            margin: 1rem auto !important;
            padding: 2rem;
        }

        .compact-table {
            font-size: 0.9rem;
            width: 100%;
        }

        .compact-table th,
        .compact-table td {
            padding: 12px 8px !important;
            vertical-align: middle;
        }

        .compact-table th {
            background-color: #10b981 !important;
            color: white !important;
            font-weight: 600 !important;
            border: none !important;
            padding: 18px 12px !important;
            font-size: 14px !important;
            white-space: nowrap;
        }

        .compact-table td {
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }

        .compact-table th:nth-child(1) { width: 25%; }
        .compact-table th:nth-child(2) { width: 25%; }
        .compact-table th:nth-child(3) { width: 20%; }
        .compact-table th:nth-child(4) { width: 10%; }
        .compact-table th:nth-child(5) { width: 10%; }
        .compact-table th:nth-child(6) { width: 10%; }

        .section-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin: 3rem 0 2rem 0;
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
        </div>

        <!-- Formulario para subir ficha técnica -->
        <div class="form-container">
            <form action="{{ route('curso.subir_fichatecnica', ['curso_id' => $curso->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <h3>Subir Ficha Técnica</h3>

                <!-- Mostrar enlace al archivo actual si existe -->
                @if ($curso->ficha_tecnica)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Actualmente hay una ficha técnica subida:</p>
                        <a href="{{ asset('uploads/' . $curso->ficha_tecnica) }}" target="_blank"
                            class="text-blue-600 hover:text-blue-800 underline font-medium">
                            Ver Ficha Técnica Actual
                        </a>
                    </div>
                @endif
                @if ($estatus_usuario == 1)
                    <!-- Campo para subir nueva ficha técnica -->
                    <div class="mb-4">
                        <label for="ficha_tecnica" class="block text-sm font-medium text-gray-700 mb-2">Ficha Técnica (PDF)</label>
                        <input type="file" id="ficha_tecnica" name="ficha_tecnica" accept="application/pdf"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <!-- Botón para subir -->
                    <div class="text-center">
                        <button type="submit" class="btn-upload">
                            Subir Ficha Técnica
                        </button>
                    </div>
                @endif
            </form>
        </div>


        <!-- Tabla de participantes -->
        <h1 class="section-title">Docentes inscritos</h1>

        <div class="optimized-container bg-white shadow-lg rounded-lg">
            <div class="table-responsive">
                <table class="table table-hover compact-table">
                    <thead>
                        <tr>
                            <th class="text-center">Correo</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Departamento</th>
                            <th class="text-center">Calificación</th>
                            <th class="text-center">Estatus</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ParticipantesOrdenados as $participanteInscrito)
                            <tr>
                                <td class="text-center">{{ $participanteInscrito->participante->user->email }}</td>
                                <td class="text-center">
                                    {{ $participanteInscrito->participante->user->datos_generales->apellido_paterno }}
                                    {{ $participanteInscrito->participante->user->datos_generales->apellido_materno }}
                                    {{ $participanteInscrito->participante->user->datos_generales->nombre }}
                                </td>
                                <td class="text-center">
                                    {{ $participanteInscrito->participante->user->datos_generales->departamento->nombre }}
                                </td>
                                <td class="text-center">
                                    @if ($participanteInscrito->calificacion)
                                        <span class="fw-bold">{{ $participanteInscrito->calificacion }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($participanteInscrito->acreditado == 2)
                                        <span class="badge bg-success">Acreditado</span>
                                    @elseif ($participanteInscrito->acreditado == 1)
                                        <span class="badge bg-danger">No Acreditado</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Sin Calificar</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($curso->estado_calificacion == 0 && $curso->estatus == 0)
                                        @if ($estatus_usuario == 1)
                                            <a href="{{ route('instructor.edit', $participanteInscrito->id) }}"
                                               class="btn btn-sm"
                                               title="Calificar participante"
                                               style="background-color: #10b981; color: white; border: none; padding: 6px 10px; border-radius: 4px; text-decoration: none;">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-center">
                @if ($curso->estado_calificacion == 0 && $curso->estatus == 0)
                    @if ($estatus_usuario == 1)
                        <form action="{{ route('instructor.subir_calificacion', $curso->id) }}" method="GET" style="display: inline;">
                            @csrf
                            <button type="submit"
                                    class="btn btn-lg fw-bold"
                                    style="background-color: #10b981; color: white; padding: 15px 30px; font-size: 16px; border-radius: 10px; border: none; box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(16, 185, 129, 0.4)'; this.style.backgroundColor='#059669';"
                                    onmouseout="this.style.transform='translateY(0px)'; this.style.boxShadow='0 4px 8px rgba(16, 185, 129, 0.3)'; this.style.backgroundColor='#10b981';"
                                    onclick="return confirm('¿Estás seguro de que quieres subir las calificaciones?');">
                                <i class="fas fa-upload me-2"></i> Subir Calificaciones
                            </button>
                        </form>
                    @endif
                @elseif ($curso->estado_calificacion != 0)
                    <div class="alert alert-success d-flex align-items-center justify-content-center" role="alert" style="max-width: 400px; margin: 0 auto; border-radius: 10px;">
                        <i class="fas fa-check-circle me-2"></i> Calificaciones subidas correctamente
                    </div>
                @endif
            </div>
        </div>
            </div>
        </div>
    </div>

</x-app-layout>
