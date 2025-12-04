<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Mis solicitudes') }}
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
            max-width: 200px;
            overflow-x: auto;
            white-space: nowrap;
        }

        .compact-table th {
            background-color: #e3f2fd !important;
            color: #333 !important;
            font-weight: 600 !important;
            border: none !important;
            padding: 15px 12px !important;
            font-size: 14px !important;
        }

        .btn-action {
            width: 50px;
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

        .btn-view {
            background: #3b82f6;
            color: white;
        }

        .btn-view:hover {
            background: #2563eb;
            color: white;
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .search-input {
            flex: 1;
            min-width: 300px;
            padding: 10px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
        }

        .search-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-select {
            padding: 10px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            min-width: 150px;
        }

        .search-select:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .search-btn {
            background: #4f46e5;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-btn:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        .results-info {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 1rem;
        }
    </style>

    <div class="optimized-container bg-white shadow-lg rounded-lg">
        <form id="searchForm" action="{{ route('jefe_solicitarcursos.index') }}" method="GET" class="search-form">
            <input
                type="text"
                name="q"
                id="searchInput"
                placeholder="Buscar por nombre del curso o instructor..."
                value="{{ old('q', request('q')) }}"
                class="search-input"
            />

            <select name="prioridad" id="prioridadSelect" class="search-select">
                <option value="">-- Prioridad --</option>
                <option value="Alta" {{ request('prioridad') == 'Alta' ? 'selected' : '' }}>Alta</option>
                <option value="Media" {{ request('prioridad') == 'Media' ? 'selected' : '' }}>Media</option>
                <option value="Baja" {{ request('prioridad') == 'Baja' ? 'selected' : '' }}>Baja</option>
            </select>

            <select name="estatus" id="estatusSelect" class="search-select">
                <option value="">-- Estatus --</option>
                <option value="0" {{ request('estatus') === '0' ? 'selected' : '' }}>Pendiente</option>
                <option value="1" {{ request('estatus') === '1' ? 'selected' : '' }}>Negado</option>
                <option value="2" {{ request('estatus') === '2' ? 'selected' : '' }}>Aceptado</option>
            </select>

            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>

        <p class="results-info">
            Resultados: <strong>{{ $totalFiltradas ?? (method_exists($solicitarCursos ?? null, 'total') ? $solicitarCursos->total() : ($solicitarCursos ? $solicitarCursos->count() : 0)) }}</strong> solicitudes
        </p>

        <div class="table-responsive">
            <table class="table table-hover compact-table">
                <thead>
                    <tr>
                        <th class="text-center">Nombre del curso</th>
                        <th class="text-center">Instructor</th>
                        <th class="text-center">Contacto instructor</th>
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
                            <td class="text-center">{{ $solicitar->instructor_propuesto ?? $solicitar->instructor }}</td>
                            <td class="text-center">{{ $solicitar->contacto_propuesto ?? $solicitar->contacto_instructor }}</td>
                            <td class="text-center">{{ $solicitar->num_participantes ?? $solicitar->cupo }}</td>

                            <td class="text-center">
                                @if ($solicitar->prioridad == 'Alta')
                                    <span class="badge bg-danger">Alta</span>
                                @elseif ($solicitar->prioridad == 'Media')
                                    <span class="badge bg-warning text-dark">Media</span>
                                @else
                                    <span class="badge bg-info">Baja</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($solicitar->estatus === 0)
                                    <span class="badge bg-primary">Pendiente</span>
                                @elseif ($solicitar->estatus === 1)
                                    <span class="badge bg-danger">Negado</span>
                                @elseif ($solicitar->estatus === 2)
                                    <span class="badge bg-success">Aceptado</span>
                                @else
                                    {{ $solicitar->estatus }}
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
                            <td colspan="7" class="text-center py-4 text-muted">No se encontraron resultados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($solicitarCursos ?? null, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $solicitarCursos->links() }}
            </div>
        @endif
    </div>

    {{-- JS debounce/autosubmit --}}
    <script>
        (function () {
            const input = document.getElementById('searchInput');
            const prioridad = document.getElementById('prioridadSelect');
            const estatus = document.getElementById('estatusSelect');
            const form = document.getElementById('searchForm');
            if (!form) return;

            let timeout = null;
            if (input) {
                input.addEventListener('input', function () {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => form.submit(), 800);
                });
            }

            [prioridad, estatus].forEach(select => {
                if (select) select.addEventListener('change', () => form.submit());
            });
        })();
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
