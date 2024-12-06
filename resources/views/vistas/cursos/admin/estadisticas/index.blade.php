<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Estadísticas por año') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xm text-gray-700 bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Año
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periodos as $anio => $groupedPeriodos)
                                <tr class="bg-white border-b">
                                    <td class="text-center">{{ $anio }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('cursos_estadisticas.show', $anio) }}">
                                            <x-primary-button
                                                class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-0">
                                                Ver detalles
                                            </x-primary-button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
