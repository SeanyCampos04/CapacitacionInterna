<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitudes') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 6px;
            border: none;
            text-decoration: none;
            font-size: 14px;
            margin: 0 2px;
            transition: all 0.2s;
        }

        .btn-view {
            background-color: #3b82f6;
            color: white;
        }

        .btn-view:hover {
            background-color: #2563eb;
            color: white;
            transform: scale(1.05);
        }

        .solicitudes-container {
            margin: 2rem 1.5rem !important;
            padding: 1.5rem;
        }
    </style>

    <div class="solicitudes-container bg-white shadow-lg rounded-lg">

        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Nombre</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Tipo</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Sede</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Inicio Oferta</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Término Oferta</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Inicio Realización</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Término Realización</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Estatus</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Como</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Detalles</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudesParticipante as $solicitudParticipante)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudParticipante->diplomado->nombre }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudParticipante->diplomado->tipo }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudParticipante->diplomado->sede }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudParticipante->diplomado->inicio_oferta)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudParticipante->diplomado->termino_oferta)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudParticipante->diplomado->inicio_realizacion)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudParticipante->diplomado->termino_realizacion)->format('d/m/Y') }}</td>
                    @if ($solicitudParticipante->estatus == 0)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">En espera</td>
                    @endif
                    @if ($solicitudParticipante->estatus == 1)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Negado</td>
                    @endif
                    @if ($solicitudParticipante->estatus == 2)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Aceptado</td>
                    @endif
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Participante</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-center">
                        <button type="button"
                                class="btn-action btn-view"
                                data-bs-toggle="modal"
                                data-bs-target="#detailsModal-participante-{{ $solicitudParticipante->diplomado->id }}"
                                title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @endforeach

                @foreach ($solicitudesInstructor as $solicitudInstructor)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudInstructor->diplomado->nombre }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudInstructor->diplomado->tipo }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudInstructor->diplomado->sede }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudInstructor->diplomado->inicio_oferta)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudInstructor->diplomado->termino_oferta)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudInstructor->diplomado->inicio_realizacion)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudInstructor->diplomado->termino_realizacion)->format('d/m/Y') }}</td>
                    @if ($solicitudInstructor->estatus == 0)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">En espera</td>
                    @endif
                    @if ($solicitudInstructor->estatus == 1)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Negado</td>
                    @endif
                    @if ($solicitudInstructor->estatus == 2)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Aceptado</td>
                    @endif
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Instructor</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-center">
                        <button type="button"
                                class="btn-action btn-view"
                                data-bs-toggle="modal"
                                data-bs-target="#detailsModal-instructor-{{ $solicitudInstructor->diplomado->id }}"
                                title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modales para Participantes -->
    @foreach ($solicitudesParticipante as $solicitudParticipante)
    <div class="modal fade" id="detailsModal-participante-{{ $solicitudParticipante->diplomado->id }}" tabindex="-1" aria-labelledby="detailsModalLabel-participante-{{ $solicitudParticipante->diplomado->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailsModalLabel-participante-{{ $solicitudParticipante->diplomado->id }}">
                        <i class="fas fa-info-circle me-2"></i>Detalles del Diplomado
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-primary"><i class="fas fa-graduation-cap me-2"></i>Información General</h6>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>ID:</strong> <span class="text-muted">{{ $solicitudParticipante->diplomado->id }}</span></p>
                                            <p><strong>Categoría:</strong> <span class="text-muted">{{ $solicitudParticipante->diplomado->clase }}</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Responsable:</strong> <span class="text-muted">{{ $solicitudParticipante->diplomado->responsable }}</span></p>
                                            <p><strong>Correo de Contacto:</strong> <span class="text-muted">{{ $solicitudParticipante->diplomado->correo_contacto }}</span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><strong>Objetivo:</strong></p>
                                            <div class="bg-light p-3 rounded">
                                                <span class="text-muted">{{ $solicitudParticipante->diplomado->objetivo }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modales para Instructores -->
    @foreach ($solicitudesInstructor as $solicitudInstructor)
    <div class="modal fade" id="detailsModal-instructor-{{ $solicitudInstructor->diplomado->id }}" tabindex="-1" aria-labelledby="detailsModalLabel-instructor-{{ $solicitudInstructor->diplomado->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="detailsModalLabel-instructor-{{ $solicitudInstructor->diplomado->id }}">
                        <i class="fas fa-info-circle me-2"></i>Detalles del Diplomado
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-success"><i class="fas fa-chalkboard-teacher me-2"></i>Información General</h6>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>ID:</strong> <span class="text-muted">{{ $solicitudInstructor->diplomado->id }}</span></p>
                                            <p><strong>Categoría:</strong> <span class="text-muted">{{ $solicitudInstructor->diplomado->clase }}</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Responsable:</strong> <span class="text-muted">{{ $solicitudInstructor->diplomado->responsable }}</span></p>
                                            <p><strong>Correo de Contacto:</strong> <span class="text-muted">{{ $solicitudInstructor->diplomado->correo_contacto }}</span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><strong>Objetivo:</strong></p>
                                            <div class="bg-light p-3 rounded">
                                                <span class="text-muted">{{ $solicitudInstructor->diplomado->objetivo }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-diplomados-layout>
