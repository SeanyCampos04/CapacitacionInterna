<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Detalles solicitud') }}
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
            min-width: 120px;
        }

        .info-value {
            color: #6b7280;
            text-align: right;
            flex: 1;
        }

        .btn-container {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
    </style>

    <div class="container mt-6 mx-auto">
        <div class="course-info-card">
            <h5>{{ $solicitarcurso->nombre }}</h5>

            <div class="info-item">
                <span class="info-label">Instructor:</span>
                <span class="info-value">{{ $solicitarcurso->instructor_propuesto }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Contacto del instructor:</span>
                <span class="info-value">{{ $solicitarcurso->contacto_propuesto }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Objetivo:</span>
                <span class="info-value">{{ $solicitarcurso->objetivo }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Participantes aproximados:</span>
                <span class="info-value">{{ $solicitarcurso->num_participantes }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Prioridad:</span>
                <span class="info-value">
                    @if ($solicitarcurso->prioridad == 'Alta')
                        <span class="badge bg-danger">Alta</span>
                    @elseif ($solicitarcurso->prioridad == 'Media')
                        <span class="badge bg-warning text-dark">Media</span>
                    @else
                        <span class="badge bg-info">Baja</span>
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    @if ($solicitarcurso->estatus == 1)
                        <span class="badge bg-danger">Negado</span>
                    @elseif ($solicitarcurso->estatus == 2)
                        <span class="badge bg-success">Aceptado</span>
                    @elseif ($solicitarcurso->estatus == 0)
                        <span class="badge bg-primary">Pendiente</span>
                    @endif
                </span>
            </div>

            @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                @if ($solicitarcurso->estatus == 0)
                    <div class="btn-container">
                        <form action="{{ route('cursos.create', $solicitarcurso->id) }}" method="PUT">
                            @csrf
                            <button type="submit" class="btn-primary">Registrar</button>
                        </form>
                        <form action="{{ route('negar_solicitud.update', $solicitarcurso->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn-danger" onclick="return confirm('¿Estás seguro de que quieres negar este curso?');">Negar</button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
        </div>
</x-app-layout>
