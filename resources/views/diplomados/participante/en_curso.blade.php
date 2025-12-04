<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Diplomados en curso') }}
        </h2>
    </x-slot>

    <style>
        .optimized-container {
            width: 95%;
            max-width: 1200px !important;
            margin: 2rem auto !important;
            padding: 1.5rem;
        }
    </style>

    <div class="optimized-container bg-white shadow-lg rounded-lg">
        <!-- Buscador -->
        <form method="GET" action="{{ route('diplomados.curso_docente') }}" class="mb-6" id="buscarFormDiplomados">
            <div class="flex flex-row flex-wrap gap-4 w-full items-end">
                <!-- Input de búsqueda por nombre -->
                <div class="flex-1 min-w-[280px]">
                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre', $nombre ?? '') }}"
                        placeholder="Buscar por nombre del diplomado..."
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    >
                </div>
                 <!-- Fecha inicio -->
            <div>
                <label class="font-semibold text-gray-700">Fecha de inicio</label>
                <input type="date" name="fecha_inicio"
                       value="{{ request('fecha_inicio') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm"
                       placeholder="dd/mm/aaaa">
            </div>

            <!-- Fecha fin -->
            <div>
                <label class="font-semibold text-gray-700">Fecha de término</label>
                <input type="date" name="fecha_fin"
                       value="{{ request('fecha_fin') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm"
                       placeholder="dd/mm/aaaa">
            </div>



                <!-- Botón buscar -->
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-700 text-white rounded-md shadow hover:bg-indigo-800"> Buscar </button>


            </div>




        <!-- Resultados -->
@php
    $totalResultados = $diplomados ? $diplomados->count() : 0;
@endphp

<p class="text-sm text-gray-500 mt-2">
    Resultados: <strong>{{ $totalResultados }}</strong> diplomados
</p>
</form>





        <!-- Tabla -->
        <table class="w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Diplomado</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Fecha de inicio</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Fecha de termino</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Progreso</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($diplomados as $diplomado)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b border-gray-200 text-sm font-semibold text-blue-600">
                        {{ $diplomado->diplomado->nombre }}
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">
                        {{ \Carbon\Carbon::parse($diplomado->diplomado->inicio_realizacion)->format('d/m/Y') }}
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">
                        {{ \Carbon\Carbon::parse($diplomado->diplomado->termino_realizacion)->format('d/m/Y') }}
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200 text-center">
                        <a href="{{ route('diplomados.detalles_participante', $diplomado->diplomado->id) }}"
                           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                           Progreso
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-2 px-4 text-center text-gray-500">
                        No hay diplomados en curso.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>



    <!-- Javascript: submit automático con debounce -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('buscarFormDiplomados');
            if (!form) return;

            const input = form.querySelector('input[name="nombre"]');
            const dateInputs = Array.from(form.querySelectorAll('input[name="inicio_realizacion"], input[name="termino_realizacion"]'));

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

            // Enviar inmediatamente cuando cambien los inputs de fecha
            dateInputs.forEach(input => input.addEventListener('change', () => form.submit()));
        });
    </script>
</x-app-diplomados-layout>
