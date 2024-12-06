<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Departamentos') }}
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
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Jefe departamento
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($departamentos as $departamento)
                            <tr class="bg-white border-b">
                                <td class="text-center">{{ $departamento->id }}</td>
                                <td class="text-center">{{ $departamento->nombre }}</td>
                                @if ($departamento->user)
                                <td class="text-center">
                                    {{ $departamento->user->datos_generales->nombre }}
                                    {{ $departamento->user->datos_generales->apellido_paterno }}
                                    {{ $departamento->user->datos_generales->apellido_materno }}
                                </td>
                                @else
                                <td class="text-center">Sin asignar</td>
                                @endif

                                <td class="text-center">
                                    <a href="{{route('departamentos.show', $departamento->id)}}">
                                        <x-primary-button class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-0">
                                            Ver detalles
                                        </x-primary-button>
                                    </a>

                                    @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <a href="{{route('departamentos.edit', $departamento->id)}}">
                                        <x-primary-button class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0">
                                            Editar
                                        </x-primary-button>
                                    </a>
                                    <form action="{{ route('departamentos.destroy', $departamento->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <x-primary-button class="bg-red-600 hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-0"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este departamento?');">
                                            Eliminar
                                        </x-primary-button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white border-b">
                                <td colspan="4" class="text-center py-4 text-gray-500">
                                    No hay departamentos disponibles.
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
