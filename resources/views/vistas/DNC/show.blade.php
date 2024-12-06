<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Detalles solicitud') }}
        </h2>
    </x-slot>

        <div class="container mt-6 mx-auto">
            <div class="flex flex-wrap justify-center">
                <div class="mb-4 mx-2 w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
                    <div class="bg-white rounded-lg overflow-hidden shadow-md h-full">
                        <div class="p-4">
                            <h5 class="text-xl font-semibold">{{ $solicitarcurso->nombre }}</h5>
                            <h6 class="text-sm font-semibold text-gray-700 mb-2"><strong>Instructor:</strong>
                                {{ $solicitarcurso->instructor_propuesto }}</h6>
                                <p class="text-sm text-gray-700"><strong>Contacto del instructor:</strong> {{ $solicitarcurso->contacto_propuesto }}
                                </p>
                            <p class="text-sm text-gray-700"><strong>Objetivo:</strong> {{ $solicitarcurso->objetivo }}
                            </p>
                            <p class="text-sm text-gray-700"><strong>Participantes aproximados:</strong>
                                {{ $solicitarcurso->num_participantes }}</p>
                            <p class="text-sm text-gray-700"><strong>Prioridad:</strong>
                                {{ $solicitarcurso->prioridad }}</p>
                            @if ($solicitarcurso->estatus == 1)
                            <p class="text-sm text-red-600"><strong>Negado</strong></p>
                            @endif
                            @if ($solicitarcurso->estatus == 2)
                            <p class="text-sm text-green-600"><strong>Aceptado</strong></p>
                            @endif
                            @if ($solicitarcurso->estatus == 0)
                            <p class="text-sm text-blue-600"><strong>Pendiente</strong></p>
                            @endif
                            @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                @if ($solicitarcurso->estatus == 0)
                                <div class="grid grid-cols-2 gap-2 w-full max-w-[400px]">
                                    <form
                                        action="{{ route('cursos.create', $solicitarcurso->id) }}"
                                        method="PUT">
                                        @csrf
                                        <x-primary-button class="justify-center common-button w-full">Registrar</x-primary-button>
                                    </form>
                                    <form action="{{ route('negar_solicitud.update', $solicitarcurso->id) }}" method="POST" class="">
                                        @csrf
                                        @method('PUT')
                                        <x-primary-button class="justify-center w-full common-button bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-0 " onclick="return confirm('¿Estás seguro de que quieres negar este curso?');">Negar</x-primary-button>
                                    </form>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
