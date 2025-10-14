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
        <h2 class="font-semibold text-xl text-white">
            {{ __('Solicitudes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!--  Buscador y filtros -->
            <div class="mb-6 bg-white p-4 rounded-md shadow-sm">
                <form method="GET" action="{{ route('admin_solicitarcursos.index') }}" class="flex flex-wrap items-center gap-3">
                    <!-- Texto libre -->
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Buscar por nombre, instructor o contacto..."
                        class="flex-1 rounded-md border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    >

                    <!-- Filtro prioridad -->
                    <select name="prioridad" class="rounded-md border-gray-300 shadow-sm px- py-2">
                        <option value="">-- Prioridad --</option>
                        <option value="Alta" {{ request('prioridad') == 'Alta' ? 'selected' : '' }}>Alta</option>
                        <option value="Media" {{ request('prioridad') == 'Media' ? 'selected' : '' }}>Media</option>
                        <option value="Baja" {{ request('prioridad') == 'Baja' ? 'selected' : '' }}>Baja</option>
                    </select>

                    <!-- Filtro estatus -->
                    <select name="estatus" class="rounded-md border-gray-300 shadow-sm px- py-2">
                        <option value="">-- Estatus --</option>
                        <option value="0" {{ request('estatus') === '0' ? 'selected' : '' }}>Pendiente</option>
                        <option value="1" {{ request('estatus') === '1' ? 'selected' : '' }}>Negado</option>
                        <option value="2" {{ request('estatus') === '2' ? 'selected' : '' }}>Aceptado</option>
                    </select>

                    <!-- Filtro departamento -->
                    @if(isset($departamentos))
                        <select name="departamento" class="rounded-md border-gray-300 shadow-sm px-4 py-2">
                            <option value="">-- Departamento --</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep->id }}" {{ request('departamento') == $dep->id ? 'selected' : '' }}>
                                    {{ $dep->nombre }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <!-- Botón -->
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-700 text-white rounded-md shadow hover:bg-indigo-800">
                        Buscar
                    </button>
                </form>

                <!-- Contador de resultados -->
                <p class="mt-3 text-gray-600 text-sm">
                    Resultados:  <strong>{{ $totalSolicitudes }}</strong> solicitudes
                </p>
            </div>

            <!-- Tabla -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-sm text-gray-700 bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-center">Nombre</th>
                                <th class="px-6 py-3 text-center">Objetivo</th>
                                <th class="px-6 py-3 text-center">Instructor</th>
                                <th class="px-6 py-3 text-center">Contacto del instructor</th>
                                <th class="px-6 py-3 text-center">Cupo</th>
                                <th class="px-6 py-3 text-center">Prioridad</th>
                                <th class="px-6 py-3 text-center">Estatus</th>
                                <th class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($solicitarCursos as $solicitar)
                                <tr class="bg-white border-b">
                                    <td class="text-center">{{ $solicitar->nombre }}</td>
                                    <td class="text-center">{{ $solicitar->objetivo }}</td>
                                    <td class="text-center">{{ $solicitar->instructor_propuesto }}</td>
                                    <td class="text-center">{{ $solicitar->contacto_propuesto }}</td>
                                    <td class="text-center">{{ $solicitar->num_participantes }}</td>

                                    <!-- Prioridad -->
                                    <td class="text-center
                                        @if($solicitar->prioridad == 'Alta') text-red-600
                                        @elseif($solicitar->prioridad == 'Media') text-orange-600
                                        @else text-blue-600 @endif">
                                        {{ $solicitar->prioridad }}
                                    </td>

                                    <!-- Estatus -->
                                    <td class="text-center
                                        @if($solicitar->estatus == 0) text-blue-600
                                        @elseif($solicitar->estatus == 1) text-red-600
                                        @else text-green-600 @endif">
                                        @if ($solicitar->estatus == 0)
                                            Pendiente
                                        @elseif ($solicitar->estatus == 1)
                                            Negado
                                        @elseif ($solicitar->estatus == 2)
                                            Aceptado
                                        @endif
                                    </td>

                                    <!-- Botón -->
                                    <td class="text-center">
                                        <form action="{{ route('solicitarcursos.show', $solicitar->id) }}" method="GET">
                                            @csrf
                                            <x-primary-button class="justify-center common-button">
                                                Ver detalles
                                            </x-primary-button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b">
                                    <td colspan="8" class="text-center py-4 text-gray-500">
                                        No hay solicitudes disponibles.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
