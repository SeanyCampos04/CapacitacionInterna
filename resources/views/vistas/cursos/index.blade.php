<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Cursos') }}
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
                                    Instructor
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Fecha de inicio
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Fecha de terminación
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Modalidad
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Periodo
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Lugar
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cursos as $curso)
                                <tr class="bg-white border-b">
                                    <td class="text-center">{{ $curso->nombre }}</td>
                                    <td class="text-center">
                                        @foreach ($curso->instructores as $instructor)
                                            {{ $instructor->user->datos_generales->nombre }}
                                            {{ $instructor->user->datos_generales->apellido_paterno }}
                                            {{ $instructor->user->datos_generales->apellido_materno }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $curso->fdi }}</td>
                                    <td class="text-center">{{ $curso->fdf }}</td>
                                    <td class="text-center">{{ $curso->modalidad }}</td>
                                    <td class="text-center">{{ $curso->periodo->periodo }}</td>
                                    <td class="text-center">{{ $curso->lugar }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('cursos.show', $curso->id) }}" method="GET">
                                            @csrf
                                            @method('GET')
                                            <x-primary-button class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-0">
                                                Ver detalles
                                            </x-primary-button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b">
                                    <td colspan="8" class="text-center py-4 text-gray-500">
                                        No hay cursos registrados.
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
