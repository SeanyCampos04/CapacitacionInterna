<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Detalles del curso: ') }} {{ $curso->nombre }}
        </h2>
    </x-slot>

    <div class="container mt-6 mx-auto">
        <div class="flex flex-wrap justify-center">
            <div class="mb-4 mx-2 w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
                <div class="bg-white rounded-lg overflow-hidden shadow-md h-full">
                    <div class="p-4">
                        <h5 class="text-xl font-semibold">{{ $curso->nombre }}</h5>
                        <h6 class="text-sm font-semibold text-gray-700 mb-2">
                            <strong>
                                @if ($curso->instructores->count() == 1)
                                    Instructor:
                                    @foreach ($curso->instructores as $instructor)
                                        <span class="font-medium">{{ $instructor->user->datos_generales->nombre }}
                                            {{ $instructor->user->datos_generales->apellido_paterno }}
                                            {{ $instructor->user->datos_generales->apellido_materno }}</span>
                                    @endforeach
                                @else
                                    Instructores:
                                    <div class="flex flex-wrap">
                                        @foreach ($curso->instructores as $instructor)
                                            <div class="bg-gray-100 p-2 m-1 rounded-lg shadow-sm">
                                                {{ $instructor->user->datos_generales->nombre }}
                                                {{ $instructor->user->datos_generales->apellido_paterno }}
                                                {{ $instructor->user->datos_generales->apellido_materno }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </strong>
                        </h6>
                        <p class="text-sm text-gray-700"><strong>Departamento:</strong>
                            {{ $curso->departamento->nombre }}</p>
                        <p class="text-sm text-gray-700"><strong>Periodo:</strong> {{ $curso->periodo->periodo }}</p>
                        <p class="text-sm text-gray-700"><strong>Duración:</strong> {{ $curso->duracion }} horas</p>
                        <p class="text-sm text-gray-700"><strong>Horario:</strong> {{ $curso->horario }}</p>
                        <p class="text-sm text-gray-700"><strong>Modalidad:</strong> {{ $curso->modalidad }}</p>
                        <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $curso->lugar }}</p>
                        <p class="text-sm text-gray-700"><strong>Inscritos:</strong>
                            {{ $curso->cursos_participantes->count() }}/{{ $curso->limite_participantes }}</p>
                        <p class="text-sm text-gray-700"><strong>Estado:</strong>
                            @if ($curso->estatus == 1)
                                Disponible
                            @else
                                Terminado
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario para subir ficha técnica -->
        <div class="flex justify-center mt-6">
            <form action="{{ route('curso.subir_fichatecnica', ['curso_id' => $curso->id]) }}" method="POST"
                enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6 w-full md:w-2/3 lg:w-1/2">
                @csrf
                <h3 class="text-lg font-bold mb-4 text-center">Subir Ficha Técnica</h3>

                <!-- Mostrar enlace al archivo actual si existe -->
                @if ($curso->ficha_tecnica)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Actualmente hay una ficha técnica subida:</p>
                        <a href="{{ asset('uploads/' . $curso->ficha_tecnica) }}" target="_blank"
                            class="text-blue-600 hover:text-blue-800 underline">
                            Ver Ficha Técnica Actual
                        </a>
                    </div>
                @endif
                @if ($estatus_usuario == 1)
                    <!-- Campo para subir nueva ficha técnica -->
                    <div class="mb-4">
                        <label for="ficha_tecnica" class="block text-sm font-medium text-gray-700">Ficha Técnica
                            (PDF)</label>
                        <input type="file" id="ficha_tecnica" name="ficha_tecnica" accept="application/pdf"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <!-- Botón para subir -->
                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Subir Ficha Técnica
                        </button>
                    </div>
                @endif

            </form>
        </div>


        <!-- Tabla de participantes -->
        <div class="py-12">
            <h1 class="text-center text-xl"><strong>Docentes inscritos</strong></h1>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xm text-gray-700 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center">Correo</th>
                                    <th scope="col" class="px-6 py-3 text-center">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-center">Departamento</th>
                                    <th scope="col" class="px-6 py-3 text-center">Calificación</th>
                                    <th scope="col" class="px-6 py-3 text-center">Estatus</th>
                                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ParticipantesOrdenados as $participanteInscrito)
                                    <tr class="bg-white border-b">
                                        <td class="text-left">{{ $participanteInscrito->participante->user->email }}
                                        </td>
                                        <td class="text-left">
                                            {{ $participanteInscrito->participante->user->datos_generales->apellido_paterno }}
                                            {{ $participanteInscrito->participante->user->datos_generales->apellido_materno }}
                                            {{ $participanteInscrito->participante->user->datos_generales->nombre }}
                                        </td>
                                        <td class="text-left">
                                            {{ $participanteInscrito->participante->user->datos_generales->departamento->nombre }}
                                        </td>
                                        <td class="text-center">
                                            @if ($participanteInscrito->calificacion)
                                                {{ $participanteInscrito->calificacion }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($participanteInscrito->acreditado == 2)
                                                <span class="text-green-600">Acreditado</span>
                                            @elseif ($participanteInscrito->acreditado == 1)
                                                <span class="text-red-600">No Acreditado</span>
                                            @else
                                                <span class="text-blue-600">Sin Calificar</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($curso->estado_calificacion == 0 && $curso->estatus == 0)
                                                @if ($estatus_usuario == 1)
                                                    <form
                                                        action="{{ route('instructor.edit', $participanteInscrito->id) }}"
                                                        method="GET">
                                                        @csrf
                                                        <x-primary-button
                                                            class="common-button bg-blue-600 text-white hover:bg-blue-700">
                                                            Calificar
                                                        </x-primary-button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 text-right">
                    @if ($curso->estado_calificacion == 0 && $curso->estatus == 0)
                        @if ($estatus_usuario == 1)
                            <form action="{{ route('instructor.subir_calificacion', $curso->id) }}" method="GET">
                                @csrf
                                <x-primary-button class="common-button bg-green-600 text-white hover:bg-green-700"
                                    onclick="return confirm('¿Estás seguro de que quieres subir las calificaciones?');">
                                    Subir calificación
                                </x-primary-button>
                            </form>
                        @endif
                    @elseif ($curso->estado_calificacion != 0)
                        Calificaciones subidas
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
