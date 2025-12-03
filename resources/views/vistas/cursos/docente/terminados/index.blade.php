<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Cursos terminados') }}
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
                                    Departamento
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Periodo
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Calificación
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
                            @forelse ($cursosTerminados as $cursoTerminado)
                                <tr class="bg-white border-b">
                                    <td class="text-center">{{ $cursoTerminado->curso->nombre }}</td>
                                    <td class="text-center">
                                        @foreach ($cursoTerminado->curso->instructores as $instructor)
                                            {{ $instructor->user->datos_generales->nombre }}
                                            {{ $instructor->user->datos_generales->apellido_paterno }}
                                            {{ $instructor->user->datos_generales->apellido_materno }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $cursoTerminado->curso->departamento->nombre }}</td>
                                    <td class="text-center">{{ $cursoTerminado->curso->periodo->periodo }}</td>
                                    @if ($cursoTerminado->curso->estado_calificacion == 2)
                                        <td class="text-center">
                                            @if ($cursoTerminado->calificacion)
                                                {{ $cursoTerminado->calificacion }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if ($cursoTerminado->acreditado == 2)
                                            <td class="text-center text-green-600">Acreditado</td>
                                        @endif
                                        @if ($cursoTerminado->acreditado == 1)
                                            <td class="text-center text-red-600">No Acreditado</td>
                                        @endif
                                        @if ($cursoTerminado->acreditado == 0)
                                            <td class="text-center text-blue-600">Sin Calificar</td>
                                        @endif
                                    @else
                                        <td class="text-center">
                                            -
                                        </td>
                                        <td class="text-center text-blue-600">Sin Calificar</td>
                                    @endif
                                    <td class="text-center">
                                        <!-- Botón "Ver detalles" mejorado -->
                                        <a href="{{ route('cursos_terminados.show', $cursoTerminado->curso->id) }}"
                                            class="inline-block bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-all">
                                            Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b">
                                    <td colspan="7" class="text-center py-4 text-gray-500">
                                        No hay cursos terminados.
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
