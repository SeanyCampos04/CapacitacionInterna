<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Detalles del curso: ') }} {{ $curso_participante->curso->nombre }}
        </h2>
    </x-slot>

    <div class="container mt-6 mx-auto">
        <div class="flex flex-wrap justify-center">
            <div class="mb-4 mx-2 w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
                <div class="bg-white rounded-lg overflow-hidden shadow-md h-full">
                    <div class="p-4">
                        <h5 class="text-xl font-semibold">{{ $curso_participante->curso->nombre }}</h5>
                        <h6 class="text-sm font-semibold text-gray-700 mb-2">
                            <strong>
                                @if ($curso_participante->curso->instructores->count() == 1)
                                    Instructor:
                                    @foreach ($curso_participante->curso->instructores as $instructor)
                                        <span class="font-medium">{{ $instructor->user->datos_generales->nombre }}
                                            {{ $instructor->user->datos_generales->apellido_paterno }}
                                            {{ $instructor->user->datos_generales->apellido_materno }}</span>
                                    @endforeach
                                @else
                                    Instructores:
                                    <div class="flex flex-wrap">
                                        @foreach ($curso_participante->curso->instructores as $instructor)
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
                            {{ $curso_participante->curso->departamento->nombre }}</p>
                        <p class="text-sm text-gray-700"><strong>Periodo:</strong>
                            {{ $curso_participante->curso->periodo->periodo }}</p>
                        <p class="text-sm text-gray-700"><strong>Fecha de inicio:</strong>
                            {{ $curso_participante->curso->fdi }}</p>
                        <p class="text-sm text-gray-700"><strong>Fecha de terminación:</strong>
                            {{ $curso_participante->curso->fdf }}</p>
                        <p class="text-sm text-gray-700"><strong>Duración:</strong>
                            {{ $curso_participante->curso->duracion }} horas</p>
                        <p class="text-sm text-gray-700"><strong>Horario:</strong>
                            {{ $curso_participante->curso->horario }}</p>
                        <p class="text-sm text-gray-700"><strong>Modalidad:</strong>
                            {{ $curso_participante->curso->modalidad }}</p>
                        <p class="text-sm text-gray-700"><strong>Lugar:</strong>
                            {{ $curso_participante->curso->lugar }}</p>
                        <p class="text-sm text-gray-700"><strong>Inscritos:</strong>
                            {{ $curso_participante->curso->cursos_participantes->count() }}/{{ $curso_participante->curso->limite_participantes }}
                        </p>
                        @if ($curso_participante->curso->estatus == 0)
                            <p class="text-sm text-gray-700"><strong>Estado:</strong>Terminado</p>
                        @else
                            <p class="text-sm text-gray-700"><strong>Estado:</strong>Activo</p>
                        @endif
                        @if ($curso_participante->curso->estado_calificacion == 2)
                            <p class="text-sm text-gray-700">
                                <strong>Calificación:</strong>{{ $curso_participante->calificacion }}
                            </p>
                            <p class="text-sm text-gray-700"><strong>Estatus:</strong>
                                @if ($curso_participante->acreditado == 2)
                                    <span class="text-green-600">Acreditado</span>
                                @endif
                                @if ($curso_participante->acreditado == 1)
                                    <span class="text-red-600">No Acreditado</span>
                                @endif
                                @if ($curso_participante->acreditado == 0)
                                    <span class="text-blue-600">Sin Calificar</span>
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-700">
                                <strong>Calificación:</strong> ---
                            </p>
                            <p class="text-sm text-gray-700"><strong>Estatus:</strong>
                                <span class="text-blue-600">Sin Calificar</span>
                            </p>
                        @endif


                        <!-- Botón para contestar la encuesta -->
                        <div class="mt-4 text-center">
                            @if (!$encuesta)
                                <a href="{{ route('encuesta.formulario', ['curso_id' => $curso_participante->curso->id]) }}"
                                    style="background-color: #1B396A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                                    Contestar Encuesta
                                </a>
                            @else
                                Encuesta contestada
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
