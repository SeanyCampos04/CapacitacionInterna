<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Docentes Inscritos') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .card-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin: 15px 0;
            padding: 25px;
        }

        .diplomado-card {
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
        }

        .diplomado-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin: -1px -1px 0 -1px;
        }

        .diplomado-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .diplomado-info {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .table-container {
            background: white;
            overflow: hidden;
            border-radius: 8px;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
            border: none;
            padding: 15px 12px;
            font-size: 14px;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-color: #e9ecef;
            font-size: 14px;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.8rem;
            padding: 6px 12px;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-participant {
            background-color: #17a2b8;
            color: white;
        }

        .badge-instructor {
            background-color: #ffc107;
            color: #212529;
        }

        .no-inscriptions {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .back-button {
            margin-bottom: 20px;
        }
    </style>

    <div class="container-fluid px-4 py-4">
        <!-- Botón de regreso -->
        <div class="back-button">
            <a href="{{ route('diplomados.diplomados.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Regresar a Diplomados
            </a>
        </div>

        <!-- Título principal -->
        <div class="card-container">
            <div class="d-flex align-items-center mb-4">
                <i class="fas fa-users fa-2x text-primary me-3"></i>
                <div>
                    <h3 class="mb-1">Docentes Inscritos en: {{ $diplomado->nombre }}</h3>
                    <p class="text-muted mb-0">Participantes e instructores aceptados en este diplomado</p>
                </div>
            </div>

            <!-- Información del diplomado específico -->
            <div class="diplomado-card">
                <div class="diplomado-header">
                    <div class="diplomado-title">{{ $diplomado->nombre }}</div>
                    <div class="diplomado-info">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Duración: {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->format('d/m/Y') }}
                        <span class="ms-3">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $diplomado->sede }}
                        </span>
                        <span class="ms-3">
                            <i class="fas fa-tag me-1"></i>{{ $diplomado->tipo }}
                        </span>
                    </div>
                </div>

                <div class="table-container p-0">
                    @php
                        // Combinar participantes e instructores
                        $inscritos = collect();

                        // Agregar participantes
                        foreach($diplomado->solicitudesParticipantes as $solicitud) {
                            $user = $solicitud->participante->user;
                            $nombre = $user->datos_generales ?
                                "{$user->datos_generales->nombre} {$user->datos_generales->apellido_paterno} {$user->datos_generales->apellido_materno}" :
                                'Sin datos generales';

                            $inscritos->push([
                                'nombre' => $nombre,
                                'email' => $user->email,
                                'duracion' => \Carbon\Carbon::parse($diplomado->inicio_realizacion)->diffInDays(\Carbon\Carbon::parse($diplomado->termino_realizacion)) + 1,
                                'estatus' => $solicitud->estatus == 2 ? 'Aceptado' : 'Denegado',
                                'registro' => 'Participante'
                            ]);
                        }

                        // Agregar instructores
                        foreach($diplomado->solicitudesInstructores as $solicitud) {
                            $user = $solicitud->instructore->user;
                            $nombre = $user->datos_generales ?
                                "{$user->datos_generales->nombre} {$user->datos_generales->apellido_paterno} {$user->datos_generales->apellido_materno}" :
                                'Sin datos generales';

                            $inscritos->push([
                                'nombre' => $nombre,
                                'email' => $user->email,
                                'duracion' => \Carbon\Carbon::parse($diplomado->inicio_realizacion)->diffInDays(\Carbon\Carbon::parse($diplomado->termino_realizacion)) + 1,
                                'estatus' => $solicitud->estatus == 2 ? 'Aceptado' : 'Denegado',
                                'registro' => 'Instructor'
                            ]);
                        }
                    @endphp

                    @if($inscritos->isEmpty())
                        <div class="no-inscriptions">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                            <h5>No hay participantes en este diplomado</h5>
                            <p class="text-muted">Aún no hay docentes inscritos como participantes o instructores.</p>
                        </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nombre del Usuario/Participante</th>
                                                <th>Correo Electrónico</th>
                                                <th>Duración del Diplomado</th>
                                                <th>Estatus</th>
                                                <th>Como se Registró</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inscritos->sortBy('nombre') as $inscrito)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $inscrito['nombre'] }}</strong>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $inscrito['email'] }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $inscrito['duracion'] }} días</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $inscrito['estatus'] == 'Aceptado' ? 'badge-success' : 'badge-danger' }}">
                                                            {{ $inscrito['estatus'] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $inscrito['registro'] == 'Participante' ? 'badge-participant' : 'badge-instructor' }}">
                                                            <i class="fas {{ $inscrito['registro'] == 'Participante' ? 'fa-user-graduate' : 'fa-chalkboard-teacher' }} me-1"></i>
                                                            {{ $inscrito['registro'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                        <!-- Resumen -->
                        <div class="p-3 bg-light border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Total de inscritos: <strong>{{ $inscritos->count() }}</strong> |
                                Participantes: <strong>{{ $inscritos->where('registro', 'Participante')->count() }}</strong> |
                                Instructores: <strong>{{ $inscritos->where('registro', 'Instructor')->count() }}</strong>
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</x-app-diplomados-layout>
