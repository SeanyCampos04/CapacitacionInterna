<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Cursos disponibles') }}
        </h2>
    </x-slot>

    <div class="container mt-6 mx-auto">
        <div class="flex flex-wrap justify-center">
            @if ($cursos->isEmpty())
                <div class="w-full text-center p-4">
                    <p class="text-lg font-semibold text-gray-500">No hay cursos disponibles en este momento.</p>
                </div>
            @else
                @foreach ($cursos as $curso)
                    @if($curso->cursos_participantes->count() < $curso->limite_participantes)
                        @if($curso->estatus == 1)
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
                                                        {{ $instructor->user->datos_generales->apellido_materno }}
                                                    </span>
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
                                    <p class="text-sm text-gray-700"><strong>Departamento:</strong> {{ $curso->departamento->nombre }}</p>
                                    <p class="text-sm text-gray-700"><strong>Periodo:</strong> {{ $curso->periodo->periodo }}</p>
                                    <p class="text-sm text-gray-700"><strong>Fecha de inicio:</strong> {{ $curso->fdi }}</p>
                                    <p class="text-sm text-gray-700"><strong>Fecha de terminación:</strong> {{ $curso->fdf}}</p>
                                    <p class="text-sm text-gray-700"><strong>Duración:</strong> {{ $curso->duracion }} horas</p>
                                    <p class="text-sm text-gray-700"><strong>Horario:</strong> {{ $curso->horario }}</p>
                                    <p class="text-sm text-gray-700"><strong>Modalidad:</strong> {{ $curso->modalidad }}</p>
                                    <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $curso->lugar }}</p>
                                    <form action="{{ route('curso_participante.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                        <x-primary-button class="mt-4">Registrar</x-primary-button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
