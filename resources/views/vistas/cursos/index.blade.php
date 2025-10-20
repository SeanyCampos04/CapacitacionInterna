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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xm text-gray-700 bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-center">Instructor</th>
                                <th scope="col" class="px-6 py-3 text-center">Fecha de inicio</th>
                                <th scope="col" class="px-6 py-3 text-center">Fecha de terminación</th>
                                <th scope="col" class="px-6 py-3 text-center">Modalidad</th>
                                <th scope="col" class="px-6 py-3 text-center">Periodo</th>
                                <th scope="col" class="px-6 py-3 text-center">Lugar</th>
                                <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cursos as $curso)
                                <tr class="bg-white border-b">
                                    <td class="text-center px-4 py-3">{{ $curso->nombre }}</td>

                                    <td class="text-center px-4 py-3">
                                        @foreach ($curso->instructores as $instructor)
                                            {{ optional($instructor->user->datos_generales)->nombre }}
                                            {{ optional($instructor->user->datos_generales)->apellido_paterno }}
                                            {{ optional($instructor->user->datos_generales)->apellido_materno }}
                                            <br>
                                        @endforeach
                                    </td>

                                    <td class="text-center px-4 py-3">
                                        @if($curso->fdi)
                                            {{ \Carbon\Carbon::parse($curso->fdi)->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center px-4 py-3">
                                        @if($curso->fdf)
                                            {{ \Carbon\Carbon::parse($curso->fdf)->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center px-4 py-3">{{ $curso->modalidad ?? '-' }}</td>
                                    <td class="text-center px-4 py-3">{{ optional($curso->periodo)->periodo ?? '-' }}</td>
                                    <td class="text-center px-4 py-3">{{ $curso->lugar ?? '-' }}</td>

                                    <td class="text-center px-4 py-3">
                                        <a href="{{ route('cursos.show', $curso->id) }}"
                                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b">
                                    <td colspan="8" class="text-center py-4 text-gray-500">
                                        No hay cursos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
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
