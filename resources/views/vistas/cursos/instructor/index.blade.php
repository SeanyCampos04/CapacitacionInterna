<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Cursos') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .optimized-container {
            width: 95%;
            max-width: 1200px !important;
            margin: 2rem auto !important;
            padding: 1.5rem;
        }
    </style>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <!--  TABLA -->
            <div class="optimized-container bg-white shadow-lg rounded-lg">
                <!-- BUSCADOR -->
                <div class="mb-6">
                    <form id="searchForm" action="{{ route('instructor.index') }}" method="GET" class="flex flex-wrap items-center gap-3">

                        <!-- Campo de búsqueda -->
                        <input
                            type="text"
                            name="q"
                            id="searchInput"
                            placeholder="Buscar por nombre, modalidad, lugar o periodo"
                            value="{{ old('q', $search ?? request('q')) }}"
                            class="flex-1 rounded-md border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        >

                        <!-- Filtro por periodo -->
                        <select
                            name="periodo_id"
                            id="periodoSelect"
                            class="rounded-md border-gray-300 shadow-sm px- py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        >
                            <option value="">--Todos los periodos--</option>
                            @foreach ($periodos as $p)
                                <option value="{{ $p->id }}" {{ ($periodoFiltro ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->periodo }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Botón de búsqueda -->
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-700 text-white rounded-md shadow hover:bg-indigo-800"
                        >
                            Buscar
                        </button>
                    </form>

                    <!-- Contador de resultados -->
                    @php
                        $totalResultados = method_exists($cursos ?? null, 'total') ? $cursos->total() : ($cursos ? $cursos->count() : 0);
                    @endphp
                    <p class="text-sm text-gray-500 mt-2">
                        Resultados: <strong>{{ $totalResultados }}</strong> cursos
                    </p>
                </div>
                <table class="w-full table-auto border-collapse border border-gray-200">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Nombre</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Fecha Inicio</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Fecha Final</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Modalidad</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Periodo</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Lugar</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cursos as $curso)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border-b border-gray-200 text-sm font-semibold text-blue-600">{{ $curso->nombre }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 text-sm text-center">
                                    @if($curso->fdi)
                                        {{ \Carbon\Carbon::parse($curso->fdi)->format('Y-m-d') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b border-gray-200 text-sm text-center">
                                    @if($curso->fdf)
                                        {{ \Carbon\Carbon::parse($curso->fdf)->format('Y-m-d') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b border-gray-200 text-sm text-center">{{ $curso->modalidad ?? '-' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 text-sm text-center">{{ optional($curso->periodo)->periodo ?? '-' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 text-sm text-center">{{ $curso->lugar ?? '-' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 text-center">
                                    <a href="{{ route('instructor.show', $curso->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 transition-colors duration-200"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-2 px-4 text-center text-gray-500">
                                    No tienes cursos disponibles.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginación -->
                @if(method_exists($cursos ?? null, 'links'))
                    <div class="mt-4">
                        {{ $cursos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JS: búsqueda automática -->
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

            // Enviar automáticamente al cambiar periodo
            select.addEventListener('change', function () {
                form.submit();
            });
        })();
    </script>
</x-app-layout>
