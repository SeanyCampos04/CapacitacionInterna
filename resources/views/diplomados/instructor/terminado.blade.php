<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Diplomados Terminados') }}
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
                               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 transition-colors duration-200">
                                <i class="fas fa-eye mr-2"></i>Ver detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-2 px-4 text-center text-gray-500">
                            No hay diplomados terminados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-diplomados-layout>
