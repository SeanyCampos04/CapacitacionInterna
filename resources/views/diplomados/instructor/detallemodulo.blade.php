<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calificar módulo:') }} {{ $modulo->nombre }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .table-container {
            width: 95%;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1.5rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-calificar {
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-calificar:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-actualizar {
            background: linear-gradient(45deg, #f59e0b, #d97706);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-actualizar:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        .module-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
    </style>

    <div class="table-container">
        <!-- Información del módulo -->
        <div class="module-info">
            <h3 class="text-xl font-bold mb-2">
                <i class="fas fa-graduation-cap mr-2"></i>{{ $modulo->nombre }}
            </h3>
            <div class="row">
                <div class="col-md-6">
                    <p><i class="fas fa-calendar-alt mr-2"></i><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p><i class="fas fa-calendar-check mr-2"></i><strong>Término:</strong> {{ \Carbon\Carbon::parse($modulo->fecha_termino)->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th scope="col"><i class="fas fa-user mr-2"></i>Docente</th>
                    <th scope="col"><i class="fas fa-star mr-2"></i>Calificación</th>
                    <th scope="col" class="text-center"><i class="fas fa-cogs mr-2"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($docentes as $docente)
                    <tr>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $docente['docente']->nombre }} {{ $docente['docente']->apellido_paterno }}</h6>
                                    <small class="text-muted">{{ $docente['docente']->apellido_materno }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">
                            @if($docente['calificacion'])
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>{{ $docente['calificacion'] }}
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-clock me-1"></i>Sin calificación
                                </span>
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            @php
                                $fecha_actual = \Carbon\Carbon::now();
                                $fecha_inicio = \Carbon\Carbon::parse($modulo->fecha_inicio);
                                $fecha_termino = \Carbon\Carbon::parse($modulo->fecha_termino);
                                $fecha_limite = $fecha_termino->copy()->addDays(7);
                            @endphp

                            @if ($fecha_actual->lt($fecha_termino))
                                <span class="badge bg-info">
                                    <i class="fas fa-hourglass-start me-1"></i>Aún no es el lapso de calificar
                                </span>
                            @elseif ($fecha_actual->gte($fecha_termino) && $fecha_actual->lte($fecha_limite))
                                @if($docente['calificacion'])
                                    <button class="btn btn-actualizar btn-sm"
                                            onclick="openUpdateModal('{{ $docente['docente']->id }}', '{{ $docente['calificacion'] }}')">
                                        <i class="fas fa-edit me-1"></i>Actualizar Calificación
                                    </button>
                                @else
                                    <button class="btn btn-calificar btn-sm"
                                            onclick="openModal('{{ $docente['docente']->id }}')">
                                        <i class="fas fa-star me-1"></i>Calificar
                                    </button>
                                @endif
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Ya pasó el lapso de calificar
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal para Calificar -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabel">
                        <i class="fas fa-star me-2"></i>Calificar Docente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="calificarForm" method="POST" action="{{ route('diplomados.actualizar.calificacion.modulo.participante') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="participante_id" name="participante_id" value="">
                        <input type="hidden" id="diplomado_id" name="diplomado_id" value="{{ $diplomado->id }}">
                        <input type="hidden" id="modulo_id" name="modulo_id" value="{{ $modulo->id }}">

                        <div class="mb-3">
                            <label for="calificacion" class="form-label">
                                <i class="fas fa-chart-line me-2"></i>Calificación (0-100)
                            </label>
                            <input type="number" class="form-control" id="calificacion" name="calificacion"
                                   min="0" max="100" step="0.1" placeholder="Ingrese la calificación" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Guardar Calificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Actualizar Calificación -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="updateModalLabel">
                        <i class="fas fa-edit me-2"></i>Actualizar Calificación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" method="POST" action="{{ route('diplomados.actualizar.calificacion.modulo.participante') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="update_participante_id" name="participante_id" value="">
                        <input type="hidden" id="update_diplomado_id" name="diplomado_id" value="{{ $diplomado->id }}">
                        <input type="hidden" id="update_modulo_id" name="modulo_id" value="{{ $modulo->id }}">

                        <div class="mb-3">
                            <label for="update_calificacion" class="form-label">
                                <i class="fas fa-chart-line me-2"></i>Nueva Calificación (0-100)
                            </label>
                            <input type="number" class="form-control" id="update_calificacion" name="calificacion"
                                   min="0" max="100" step="0.1" placeholder="Ingrese la nueva calificación" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync-alt me-1"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts personalizados -->
    <script>
        function openModal(participanteId) {
            const modal = new bootstrap.Modal(document.getElementById('modal'));
            document.getElementById('participante_id').value = participanteId;
            modal.show();
        }

        function openUpdateModal(participanteId, calificacion) {
            const modal = new bootstrap.Modal(document.getElementById('updateModal'));
            document.getElementById('update_participante_id').value = participanteId;
            document.getElementById('update_calificacion').value = calificacion;
            modal.show();
        }
    </script>
</x-app-diplomados-layout>
