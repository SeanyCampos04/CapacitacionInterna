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
    <form method="GET">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Nombre del Diplomado -->
            <div>
                <label class="font-semibold text-gray-700">Nombre del diplomado</label>
                <input type="text" name="nombre"
                       value="{{ request('nombre') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm"
                       placeholder="Buscar por nombre...">
            </div>

            <!-- Fecha de Inicio -->
            <div>
                <label class="font-semibold text-gray-700">Fecha de inicio</label>
                <input type="date" name="fecha_inicio"
                       value="{{ request('fecha_inicio') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>

            <!-- Fecha de Término -->
            <div>
                <label class="font-semibold text-gray-700">Fecha de término</label>
                <input type="date" name="fecha_fin"
                       value="{{ request('fecha_fin') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>

        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                Buscar
            </button>

            <a href="{{ route('diplomados.curso_instructor') }}">
               class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg shadow hover:bg-gray-400">
                Limpiar
            </a>
        </div>
    </form>
</div>


    <div class="optimized-container bg-white shadow-lg rounded-lg">
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
</x-app-diplomados-layout>
