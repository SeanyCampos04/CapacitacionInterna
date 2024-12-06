<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Mis solicitudes') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xm text-gray-700 bg-gray-50">
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
                                    Contacto instructor
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
                                        @if ($solicitar->estatus == 1 or $solicitar->estatus == 0)
                                            <form action="{{ route('solicitarcursos.destroy', $solicitar->id) }}" method="POST" id="deleteForm_{{ $solicitar->id }}">
                                                @csrf
                                                @method("DELETE")
                                                <x-primary-button class="justify-center w-full common-button bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-0 " onclick="return confirm('¿Estás seguro de que quieres eliminar esta solicitud?');">Eliminar</x-primary-button>
                                            </form>
                                        @endif
                                        <form action="{{ route('solicitarcursos.show', $solicitar->id) }}" method="GET">
                                            @csrf
                                            <x-primary-button class="justify-center common-button w-full">Ver detalles</x-primary-button>
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
