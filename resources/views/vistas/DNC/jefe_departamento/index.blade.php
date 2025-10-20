<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Mis solicitudes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">

            <!--Buscador  -->
            <div class="mb-6">
                <form id="searchForm" action="{{ route('jefe_solicitarcursos.index') }}" method="GET" class="flex flex-wrap items-center gap-3">

                    <!-- Input -->
<input
    type="text"
    name="q"
    id="searchInput"
    placeholder="Buscar por nombre del curso o instructor..."
    value="{{ old('q', request('q')) }}"
    class="flex-1 rounded-md border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
/>

<!-- Select Prioridad -->
<select name="prioridad" id="prioridadSelect"
    class="h-10 w-44 rounded-md border border-gray-300 px-3 focus:border-indigo-600 focus:ring focus:ring-indigo-200">
    <option value="">-- Prioridad --</option>
    <option value="Alta" {{ request('prioridad') == 'Alta' ? 'selected' : '' }}>Alta</option>
    <option value="Media" {{ request('prioridad') == 'Media' ? 'selected' : '' }}>Media</option>
    <option value="Baja" {{ request('prioridad') == 'Baja' ? 'selected' : '' }}>Baja</option>
</select>

<!-- Select Estatus -->
<select name="estatus" id="estatusSelect"
    class="h-10 w-44 rounded-md border border-gray-300 px-3 focus:border-indigo-600 focus:ring focus:ring-indigo-200">
    <option value="">-- Estatus --</option>
    <option value="0" {{ request('estatus') === '0' ? 'selected' : '' }}>Pendiente</option>
    <option value="1" {{ request('estatus') === '1' ? 'selected' : '' }}>Negado</option>
    <option value="2" {{ request('estatus') === '2' ? 'selected' : '' }}>Aceptado</option>
</select>

<!-- Botón Buscar -->
<button type="submit"
    class="h-10 px-5 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 active:bg-indigo-800 shadow-md transition">
    Buscar
</button>

                </form>

                <!-- Resultados -->
                <p class="text-sm text-gray-500 mt-2">
                    Resultados: <strong>{{ $totalFiltradas ?? (method_exists($solicitarCursos ?? null, 'total') ? $solicitarCursos->total() : ($solicitarCursos ? $solicitarCursos->count() : 0)) }}</strong>
                    solicitudes
                </p>
            </div>

            <!-- TABLA -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xm text-gray-700 bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-center">Nombre del curso</th>
                                <th class="px-6 py-3 text-center">Instructor</th>
                                <th class="px-6 py-3 text-center">Contacto instructor</th>
                                <th class="px-6 py-3 text-center">Cupo</th>
                                <th class="px-6 py-3 text-center">Prioridad</th>
                                <th class="px-6 py-3 text-center">Estatus</th>
                                <th class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($solicitarCursos as $solicitar)
                                <tr class="bg-white border-b">
                                    <td class="text-center px-4 py-3">{{ $solicitar->nombre }}</td>
                                    <td class="text-center px-4 py-3">{{ $solicitar->instructor_propuesto ?? $solicitar->instructor }}</td>
                                    <td class="text-center px-4 py-3">{{ $solicitar->contacto_propuesto ?? $solicitar->contacto_instructor }}</td>
                                    <td class="text-center px-4 py-3">{{ $solicitar->num_participantes ?? $solicitar->cupo }}</td>

                                    {{-- Prioridad --}}
                                    <td class="text-center px-4 py-3">
                                        @if ($solicitar->prioridad == 'Alta') <span class="text-red-600">Alta</span>
                                        @elseif ($solicitar->prioridad == 'Media') <span class="text-orange-600">Media</span>
                                        @else <span class="text-blue-600">Baja</span>
                                        @endif
                                    </td>

                                    {{-- Estatus --}}
                                    <td class="text-center px-4 py-3">
                                        @if ($solicitar->estatus === 0) <span class="text-blue-600">Pendiente</span>
                                        @elseif ($solicitar->estatus === 1) <span class="text-red-600">Negado</span>
                                        @elseif ($solicitar->estatus === 2) <span class="text-green-600">Aceptado</span>
                                        @else {{ $solicitar->estatus }}
                                        @endif
                                    </td>

                                    <td class="text-center px-4 py-3">
                                        <form action="{{ route('solicitarcursos.show', $solicitar->id) }}" method="GET">
                                            @csrf
                                            <x-primary-button class="justify-center common-button w-full">Ver detalles</x-primary-button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-gray-500">No se encontraron resultados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación (si aplica) --}}
                <div class="p-4">
                    @if(method_exists($solicitarCursos ?? null, 'links'))
                        {{ $solicitarCursos->links() }}
                    @endif
                </div>
            </div>

        </div>
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
</x-app-layout>
