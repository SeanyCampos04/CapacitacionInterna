<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del diplomado:') }} {{ $diplomado->nombre }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">
        @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
            <div class="mb-6">
                <a href="{{ route('diplomados.modulos.create', $diplomado->id) }}" class="inline-block px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Agregar módulo
                </a>
            </div>
        @endif
        <ul class="space-y-4">
            @foreach ($modulos as $modulo)
                @php
                    $fechaActual = \Carbon\Carbon::now();
                    $fechaInicio = \Carbon\Carbon::parse($modulo->fecha_inicio);
                    $fechaTermino = \Carbon\Carbon::parse($modulo->fecha_termino);

                    // Determinar el estilo según las fechas
                    if ($fechaActual->lt($fechaInicio)) {
                        $li = 'bg-gray-100 border-blue-500'; // Aún no comienza
                        $estado = 'No Iniciado'; // Estado cuando aún no ha comenzado
                        $estadoClass = 'text-gray-500 text-center';
                    } elseif ($fechaActual->between($fechaInicio, $fechaTermino)) {
                        $li = 'bg-green-100 border-green-500'; // En curso
                        $estado = 'En Progreso'; // Estado cuando está en curso
                        $estadoClass = 'text-blue-500 font-semibold text-center';
                    } elseif ($fechaActual->gt($fechaTermino)) {
                        $li = 'bg-gray-100 border-gray-500'; // Ya terminó
                        $estado = 'Completado'; // Estado cuando ya terminó
                        $estadoClass = 'text-green-500 font-semibold text-center';
                    }
                @endphp

                <li class="py-4 px-6 border-l-4 {{ $li }} border border-gray-600 rounded-lg flex justify-between items-center mb-4">
                    <div>
                        <span class="text-sm font-medium">{{ $modulo->nombre }}</span>
                        <div class="text-sm">
                            <p>Instructor: {{ $modulo->instructore->user->datos_generales->nombre }}
                                {{ $modulo->instructore->user->datos_generales->apellido_paterno }}
                                {{ $modulo->instructore->user->datos_generales->apellido_materno }}</p>
                            <p>Inicio: {{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') }}</p>
                            <p>Término: {{ \Carbon\Carbon::parse($modulo->fecha_termino)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <!-- Mostrar el estado según el tipo -->
                    <p class="{{ $estadoClass }}">{{ $estado }}</p>
                    <!-- Botón Editar -->
                    @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                        <a href="{{ route('diplomados.modulos.edit', $modulo->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            Editar
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</x-app-diplomados-layout>
