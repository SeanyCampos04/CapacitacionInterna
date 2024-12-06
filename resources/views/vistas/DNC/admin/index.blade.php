<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Solicitudes') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-sm text-gray-700 bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Objetivo
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Instructor
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Contacto del instructor
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Cupo
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Prioridad
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Estatus
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Acciones
                                </th>
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
                                    @if ($solicitar->prioridad == 'Alta')
                                    <td class="text-center text-red-600">Alta</td>
                                    @elseif ($solicitar->prioridad == 'Media')
                                    <td class="text-center text-orange-600">Media</td>
                                    @elseif ($solicitar->prioridad == 'Baja')
                                    <td class="text-center text-blue-600">Baja</td>
                                    @endif
                                    @if ($solicitar->estatus == 0)
                                    <td class="text-center text-blue-600">Pendiente</td>
                                    @elseif ($solicitar->estatus == 1)
                                    <td class="text-center text-red-600">Negado</td>
                                    @elseif ($solicitar->estatus == 2)
                                    <td class="text-center text-green-600">Aceptado</td>
                                    @endif
                                    <td class="text-center">
                                        <form action="{{ route('solicitarcursos.show', $solicitar->id) }}" method="GET">
                                            @csrf
                                            <x-primary-button class="justify-center common-button">Ver detalles</x-primary-button>
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
