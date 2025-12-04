<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Detalles del usuario:') }} {{ $usuario->datos_generales->nombre }}
            {{ $usuario->datos_generales->apellido_paterno }} {{ $usuario->datos_generales->apellido_materno }}
        </h2>
    </x-slot>

    <div class="container mt-6 mx-auto">
        <div class="flex flex-wrap justify-center">
            <div class="mb-4 mx-2 w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
                <div class="bg-white rounded-lg overflow-hidden shadow-md h-full">
                    <div class="p-4">
                        <h5 class="text-xl font-semibold">{{ $usuario->datos_generales->nombre }}
                            {{ $usuario->datos_generales->apellido_paterno }}
                            {{ $usuario->datos_generales->apellido_materno }}</h5>
                        <h6 class="text-sm font-semibold text-gray-700 mb-2"><strong>Email:</strong>
                            {{ $usuario->email }}</h6>
                        <p class="text-sm text-gray-700"><strong>Roles:</strong>
                            @foreach ($usuario->user_roles as $userRole)
                                {{ $userRole->nombre }},
                            @endforeach
                        </p>
                        @if ($usuario->tipo == 1)
                            <p class="text-sm text-gray-700"><strong>Tipo:</strong>Docente</p>
                        @endif
                        @if ($usuario->tipo == 2)
                            <p class="text-sm text-gray-700"><strong>Tipo:</strong>Administrativo</p>
                        @endif
                        @if ($usuario->tipo == 3)
                            <p class="text-sm text-gray-700"><strong>Tipo:</strong>Otro</p>
                        @endif
                        @if ($usuario->estatus == 1)
                            <p class="text-sm text-gray-700"><strong>Estatus:</strong>Activo</p>
                        @endif
                        @if ($usuario->estatus == 0)
                            <p class="text-sm text-gray-700"><strong>Estatus:</strong>Inactivo</p>
                        @endif
                        @if ($usuario->datos_generales->departamento->nombre)
                            <p class="text-sm text-gray-700"><strong>Departamento:</strong>
                                {{ $usuario->datos_generales->departamento->nombre }}</p>
                        @endif
                        @if ($usuario->datos_generales->fecha_nacimiento)
                            <p class="text-sm text-gray-700"><strong>Fecha nacimiento:</strong>
                                {{ $usuario->datos_generales->fecha_nacimiento }}</p>
                        @endif
                        @if ($usuario->datos_generales->curp)
                            <p class="text-sm text-gray-700"><strong>curp:</strong>
                                {{ $usuario->datos_generales->curp }}</p>
                        @endif
                        @if ($usuario->datos_generales->rfc)
                            <p class="text-sm text-gray-700"><strong>rfc:</strong> {{ $usuario->datos_generales->rfc }}
                            </p>
                        @endif
                        @if ($usuario->datos_generales->telefono)
                            <p class="text-sm text-gray-700"><strong>Telefono:</strong>
                                {{ $usuario->datos_generales->telefono }}</p>
                        @endif
                        @if ($usuario->datos_generales->sexo)
                            <p class="text-sm text-gray-700">
                                <strong>Sexo:</strong>{{ $usuario->datos_generales->sexo }}</p>
                        @endif
                        @if ($usuario->instructor && $usuario->instructor->cvu)
                            <p class="text-sm text-gray-700">
                                <strong>{{ __('CVU:') }}</strong>
                                <a href="{{ asset('uploads/' . $usuario->instructor->cvu) }}" target="_blank"
                                    class="text-blue-600 underline">
                                    {{ __('Ver CVU') }}
                                </a>
                            </p>
                        @endif

                        <div class="grid grid-cols-3 gap-4 w-full max-w-[400px]">
                            @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                <form action="{{ route('usuario.edit', $usuario->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('GET')
                                    <x-primary-button
                                        class="common-button bg-blue-600 text-white hover:bg-blue-700 active:bg-red-800 focus:outline-none focus:ring-0">Editar</x-primary-button>
                                </form>
                                @if ($usuario->estatus == 1)
                                    <form action="{{ route('usuario.desactivar', $usuario->id) }}" method="POST"
                                        class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <x-primary-button
                                            class="common-button bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-0"
                                            onclick="return confirm('¿Estás seguro de que quieres poner como inactivo a este usuario?');">Desactivar</x-primary-button>
                                    </form>
                                @else
                                    <form action="{{ route('usuario.activar', $usuario->id) }}" method="POST"
                                        class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <x-primary-button
                                            class="common-button bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0"
                                            onclick="return confirm('¿Estás seguro de que quieres poner como activo a este usuario?');">Activar</x-primary-button>
                                    </form>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <h1 class="text-center text-xl"><strong>Historial de cursos</strong></h1>
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
                                    Fecha de inicio
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Fecha final
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Clase
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Competencias Digitales
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Formación Tutorial
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Instructor
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cursos as $curso)
                                <tr class="bg-white border-b">
                                    <td class="text-center">{{ $curso->curso->nombre }}</td>
                                    <td class="text-center">{{ $curso->curso->fdi }}</td>
                                    <td class="text-center">{{ $curso->curso->fdf }}</td>
                                    <td class="text-center">{{ $curso->curso->clase }}</td>
                                    <td class="text-center">
                                        @if ($curso->curso->es_tics)
                                            Si
                                        @else
                                            No
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($curso->curso->es_tutorias)
                                            Si
                                        @else
                                            No
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @foreach ($curso->curso->cursos_instructores as $instructor)
                                            {{ $instructor->instructor->user->datos_generales->nombre }}
                                            {{ $instructor->instructor->user->datos_generales->apellido_paterno }}
                                            {{ $instructor->instructor->user->datos_generales->apellido_materno }} <br>
                                        @endforeach
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-gray-500 py-4">
                                        No tiene ningún historial de cursos registrado.
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
