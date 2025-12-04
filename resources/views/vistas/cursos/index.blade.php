<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Cursos') }}
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

        .compact-table td::-webkit-scrollbar {
            height: 6px;
        }

        .compact-table td::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .compact-table td::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .compact-table td::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
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
    </style>

    <div class="optimized-container bg-white shadow-lg rounded-lg">

        <!--  buscador con filtro por periodo -->
            <div class="mb-6">
                <form id="searchForm" action="{{ route('cursos.index') }}" method="GET" class="flex flex-wrap items-center gap-3">

                    <!-- Campo de texto -->
                    <input
                        type="text"
                        name="q"
                        id="searchInput"
                        placeholder="Buscar por nombre, instructor, departamento o modalidad"
                        value="{{ old('q', $search ?? request('q')) }}"
                        class="flex-1 rounded-md border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    >

                    <!-- Select de periodo -->
                    <select name="periodo_id" id="periodoSelect"
                        class="rounded-md border-gray-300 shadow-sm px- py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">--Todos los periodos-- </option>
                        @foreach ($periodos as $p)
                            <option value="{{ $p->id }}" {{ ($periodoFiltro ?? '') == $p->id ? 'selected' : '' }}>
                                {{ $p->periodo }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-700 text-white rounded-md shadow hover:bg-indigo-800">
                        Buscar
                    </button>
                </form>

                <!-- Resultados -->
                @php
                    $totalResultados = method_exists($cursos ?? null, 'total') ? $cursos->total() : ($cursos ? $cursos->count() : 0);
                @endphp
                <p class="text-sm text-gray-500 mt-2">
                    Resultados: <strong>{{ $totalResultados }}</strong> cursos
                </p>
            </div>

        <!-- TABLA -->
        <div class="table-responsive">
            <table class="table table-hover compact-table">
                <thead>
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Instructor</th>
                        <th class="text-center">Fecha Inicio</th>
                        <th class="text-center">Fecha Final</th>
                        <th class="text-center">Modalidad</th>
                        <th class="text-center">Periodo</th>
                        <th class="text-center">Lugar</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cursos as $curso)
                        <tr class="align-middle">
                            <td class="text-center">{{ $curso->nombre }}</td>
                            <td class="text-center">
                                @foreach ($curso->instructores as $instructor)
                                    {{ optional($instructor->user->datos_generales)->nombre }}
                                    {{ optional($instructor->user->datos_generales)->apellido_paterno }}
                                    {{ optional($instructor->user->datos_generales)->apellido_materno }}<br>
                                @endforeach
                            </td>
                            <td class="text-center">
                                @if($curso->fdi)
                                    {{ \Carbon\Carbon::parse($curso->fdi)->format('Y-m-d') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if($curso->fdf)
                                    {{ \Carbon\Carbon::parse($curso->fdf)->format('Y-m-d') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{{ $curso->modalidad ?? '-' }}</td>
                            <td class="text-center">{{ optional($curso->periodo)->periodo ?? '-' }}</td>
                            <td class="text-center">{{ $curso->lugar ?? '-' }}</td>

                            <td class="py-2 px-4 border-b border-gray-200 text-center">
                                <a href="{{ route('cursos.show', $curso->id) }}"
                                   class="btn-action btn-view"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">
                                No hay cursos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if(method_exists($cursos ?? null, 'links'))
            <div class="mt-4">
                {{ $cursos->links() }}
            </div>
        @endif

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS: auto búsqueda con debounce -->
    <script>
        (function () {
            const input = document.getElementById('searchInput');
            const select = document.getElementById('periodoSelect');
            const form = document.getElementById('searchForm');
            if (!input || !form) return;

            let timeout = null;
            input.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => form.submit(), 800);
            });

            // enviar el formulario automáticamente al cambiar de periodo
            select.addEventListener('change', function () {
                form.submit();
            });
        })();
    </script>
</x-app-layout>
