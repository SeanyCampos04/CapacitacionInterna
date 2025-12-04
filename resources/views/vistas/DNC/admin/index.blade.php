<script>
    (function () {
        const form = document.querySelector('form');
        if (!form) return;

        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('change', () => form.submit());
        });

        const textInput = form.querySelector('input[name="q"]');
        if (textInput) {
            let timeout;
            textInput.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => form.submit(), 600);
            });
        }
    })();
</script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Solicitudes') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .optimized-container {
            width: 98%;
            max-width: 1400px !important;
            margin: 1rem auto !important;
            padding: 1.25rem;
        }

        .compact-table {
            font-size: 0.95rem;
        }

        .compact-table th,
        .compact-table td {
            padding: 0.5rem 0.75rem !important;
            vertical-align: middle;
            white-space: nowrap;
        }

        .compact-table .badge {
            font-size: 0.85rem !important;
            padding: 0.4em 0.8em !important;
            font-weight: 600 !important;
        }

        .compact-table th {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        /* Estilo del header igual al de Diplomados */
        .table thead th {
            background-color: #e3f2fd !important;
            color: #333 !important;
            font-weight: 600 !important;
            border: none !important;
            padding: 15px 12px !important;
            font-size: 14px !important;
        }

        .btn-action {
            width: 80px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            margin: 0 3px;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .btn-view {
            background: #3b82f6;
            color: white;
        }

        .btn-view:hover {
            background: #2563eb;
            color: white;
        }
    </style>

    <div class="optimized-container bg-white shadow-lg rounded-lg">

        <!-- Buscador con filtros -->
        <form method="GET" action="{{ route('admin_solicitarcursos.index') }}" class="mb-6" id="buscarForm">
            <div class="flex flex-row flex-wrap gap-4 w-full items-center">
                <!-- Input de búsqueda -->
                <div class="flex-1 min-w-[350px]">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Buscar por nombre, instructor o contacto..."
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                <!-- Filtro por Prioridad -->
                <div class="flex-1 min-w-[180px]">
                    <select name="prioridad" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">-- Prioridad --</option>
                        <option value="Alta" {{ request('prioridad') == 'Alta' ? 'selected' : '' }}>Alta</option>
                        <option value="Media" {{ request('prioridad') == 'Media' ? 'selected' : '' }}>Media</option>
                        <option value="Baja" {{ request('prioridad') == 'Baja' ? 'selected' : '' }}>Baja</option>
                    </select>
                </div>
                <!-- Filtro por Estatus -->
                <div class="flex-none w-[180px]">
                    <select name="estatus" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">-- Estatus --</option>
                        <option value="0" {{ request('estatus') === '0' ? 'selected' : '' }}>Pendiente</option>
                        <option value="1" {{ request('estatus') === '1' ? 'selected' : '' }}>Negado</option>
                        <option value="2" {{ request('estatus') === '2' ? 'selected' : '' }}>Aceptado</option>
                    </select>
                </div>
                <!-- Filtro por Departamento -->
                @if(isset($departamentos))
                <div class="flex-none w-[230px]">
                    <select name="departamento" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">-- Departamento --</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep->id }}" {{ request('departamento') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <!-- Botón buscar -->
                <div class="flex-none">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-700 text-white rounded-md shadow hover:bg-indigo-800">
                        Buscar
                    </button>
                </div>
            </div>
        </form>

        <!-- Javascript: submit automático (input con debounce y cambios en selects) -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('buscarForm');
                if (!form) return;

                const input = form.querySelector('input[name="q"]');
                const selects = Array.from(form.querySelectorAll('select[name="prioridad"], select[name="estatus"], select[name="departamento"]'));

                // Debounce helper
                function debounce(fn, delay) {
                    let t;
                    return function (...args) {
                        clearTimeout(t);
                        t = setTimeout(() => fn.apply(this, args), delay);
                    };
                }

                if (input) {
                    // Enviar tras 500ms sin teclear
                    const submitDebounced = debounce(() => form.submit(), 500);
                    input.addEventListener('input', submitDebounced);
                }

                // Enviar inmediatamente cuando cambie cualquiera de los selects
                selects.forEach(s => s.addEventListener('change', () => form.submit()));
            });
        </script>

        <!-- Resultados -->
        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Resultados:
                <span class="font-medium text-gray-800">
                    {{ $totalSolicitudes }}
                </span>
                {{ $totalSolicitudes === 1 ? 'solicitud' : 'solicitudes' }}
            </div>
            <div></div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table table-hover compact-table">
                <thead>
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Objetivo</th>
                        <th class="text-center">Instructor</th>
                        <th class="text-center">Contacto del instructor</th>
                        <th class="text-center">Cupo</th>
                        <th class="text-center">Prioridad</th>
                        <th class="text-center">Estatus</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitarCursos as $solicitar)
                        <tr class="align-middle">
                            <td class="text-center">{{ $solicitar->nombre }}</td>
                            <td class="text-center">{{ $solicitar->objetivo }}</td>
                            <td class="text-center">{{ $solicitar->instructor_propuesto }}</td>
                            <td class="text-center">{{ $solicitar->contacto_propuesto }}</td>
                            <td class="text-center">{{ $solicitar->num_participantes }}</td>
                            <td class="text-center">
                                @if($solicitar->prioridad == 'Alta')
                                    <span class="badge bg-danger">Alta</span>
                                @elseif($solicitar->prioridad == 'Media')
                                    <span class="badge bg-warning">Media</span>
                                @else
                                    <span class="badge bg-info">Baja</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($solicitar->estatus == 0)
                                    <span class="badge bg-primary">Pendiente</span>
                                @elseif($solicitar->estatus == 1)
                                    <span class="badge bg-danger">Negado</span>
                                @else
                                    <span class="badge bg-success">Aceptado</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('solicitarcursos.show', $solicitar->id) }}"
                                   class="btn-action btn-view"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">
                                No hay solicitudes disponibles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
