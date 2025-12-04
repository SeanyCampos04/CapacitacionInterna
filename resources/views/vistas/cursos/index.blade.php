<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Cursos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">

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
            <div class="optimized-container bg-white shadow-lg rounded-lg">
                <table class="w-full table-auto border-collapse border border-gray-200">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Nombre</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Instructor</th>
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
                                <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                    @foreach ($curso->instructores as $instructor)
                                        {{ optional($instructor->user->datos_generales)->nombre }}
                                        {{ optional($instructor->user->datos_generales)->apellido_paterno }}
                                        {{ optional($instructor->user->datos_generales)->apellido_materno }}<br>
                                    @endforeach
                                </td>
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
                                <a href="{{ route('cursos.show', $curso->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 transition-colors duration-200"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                                </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-2 px-4 text-center text-gray-500">
                                    No hay cursos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>                <!-- Paginación -->
                <div class="p-4">
                    @if(method_exists($cursos ?? null, 'links'))
                        {{ $cursos->links() }}
                    @endif
                </div>
            </div>

        </div>
    </div>

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
