<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Usuarios') }}
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
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Departamento
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
                            @foreach ($usuarios as $usuario)
                                <tr class="bg-white border-b">
                                    <td class="text-center">{{ $usuario->datos_generales->nombre }} {{ $usuario->datos_generales->apellido_paterno }} {{ $usuario->datos_generales->apellido_materno }}</td>
                                    <td class="text-center">{{ $usuario->email }}</td>
                                    <td class="text-center">{{ $usuario->datos_generales->departamento->nombre }}</td>
                                    @if ($usuario->estatus == 1)
                                    <td class="text-center">Activo</td>
                                    @endif
                                    @if ($usuario->estatus == 0)
                                    <td class="text-center">Inactivo</td>
                                    @endif
                                    <td class="text-center">
                                        <form action="{{ route('usuario_datos.index', $usuario->id) }}" method="GET">
                                            @csrf
                                            @method('GET')
                                            <x-primary-button class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-0">
                                                Ver detalles
                                            </x-primary-button>
                                        </form>
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
