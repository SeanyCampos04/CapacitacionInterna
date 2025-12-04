<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Periodos') }}
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
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Periodo
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Año
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Trimestre
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($periodos as $periodo)
                                <tr class="bg-white border-b">
                                    <td class="text-center">{{ $periodo->id }}</td>
                                    <td class="text-center">{{ $periodo->periodo }}</td>
                                    <td class="text-center">{{ $periodo->anio }}</td>
                                    @if ($periodo->trimestre == 1)
                                        <td class="text-center">Enero - Marzo</td>
                                    @endif
                                    @if ($periodo->trimestre == 2)
                                        <td class="text-center">Abril - Junio</td>
                                    @endif
                                    @if ($periodo->trimestre == 3)
                                        <td class="text-center">Julio - Septiembre</td>
                                    @endif
                                    @if ($periodo->trimestre == 4)
                                        <td class="text-center">Octubre - Diciembre</td>
                                    @endif
                                    <td class="text-center">
                                        @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                            <form action="{{ route('periodos.destroy', $periodo->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <x-primary-button class="bg-red-600 hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-0"
                                                    onclick="return confirm('¿Estás seguro de que quieres eliminar este periodo?');">
                                                    Eliminar
                                                </x-primary-button>
                                            </form>
                                        @endif

                                        <a href="{{ route('periodos.show', $periodo->id) }}">
                                            <x-primary-button class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-0">
                                                Ver detalles
                                            </x-primary-button>
                                        </a>
                                        @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                            <a href="{{ route('periodos.edit', $periodo->id) }}">
                                                <x-primary-button class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0">
                                                    Editar
                                                </x-primary-button>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b">
                                    <td colspan="5" class="text-center py-4 text-gray-500">
                                        No hay periodos registrados.
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
