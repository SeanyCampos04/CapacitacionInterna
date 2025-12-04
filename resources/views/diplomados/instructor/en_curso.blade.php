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

    <!-- BUSCADOR -->
<div class="optimized-container mb-4 p-4 rounded-lg shadow bg-white">
   <form id="buscarFormDiplomadosInstructor" method="GET">
    <div class="flex flex-col md:flex-row items-center gap-4 w-full">

        <!-- Input -->
        <div class="w-full md:w-2/3">
            <input type="text" name="nombre"
                value="{{ request('nombre') }}"
                class="w-full border-gray-300 rounded-lg shadow-sm"
                placeholder="Buscar por nombre del diplomado...">
        </div>


        <!-- Botón buscar -->
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-700 text-white rounded-md shadow hover:bg-indigo-800"> Buscar </button>

    </div>


 <!-- Resultados -->
@php
    $totalResultados = $diplomados ? $diplomados->count() : 0;
@endphp

<p class="text-sm text-gray-500 mt-2 mb-4">
    Resultados: <strong>{{ $totalResultados }}</strong> diplomados
</p>
    </form>


        <!-- Tabla -->

        <table class="w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Diplomado</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Objetivo</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Detalles</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($diplomados as $solicitud)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b border-gray-200 text-sm font-semibold text-blue-600">
                            {{ $solicitud->diplomado->nombre }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">
                            {{ $solicitud->diplomado->objetivo }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                            <a href="{{ route('diplomados.detalles_instructor', $solicitud->diplomado->id) }}"
                               class="text-blue-500 hover:underline">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-2 px-4 text-center text-gray-500">
                            No hay diplomados en curso.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('buscarFormDiplomadosInstructor');
        if (!form) return;

        const input = form.querySelector('input[name="nombre"]');
        if (!input) return;

        function debounce(fn, delay) {
            let t;
            return function (...args) {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        const submitDebounced = debounce(() => form.submit(), 500);
        input.addEventListener('input', submitDebounced);
    });
</script>



</x-app-diplomados-layout>
