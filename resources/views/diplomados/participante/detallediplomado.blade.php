<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del diplomado:') }} {{$diplomado->nombre}}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">
        <ul>
            @foreach ($modulos as $modulo)
                @php
                    $fechaActual = \Carbon\Carbon::now();
                    $fechaInicio = \Carbon\Carbon::parse($modulo->fecha_inicio);
                    $fechaTermino = \Carbon\Carbon::parse($modulo->fecha_termino);

                    $calificacion = $modulo->calificacionesModulos->where('participante_id', $user->participante->id)->first();

                    // Determinar el estilo según las fechas
                    if ($fechaActual->lt($fechaInicio)) {
                        $li = 'bg-gray-100 border-blue-500'; // Aún no comienza
                        $estado = 'No Iniciado'; // Estado cuando aún no ha comenzado
                        $estadoClass = 'text-gray-500 text-center';
                    } elseif ($fechaActual->between($fechaInicio, $fechaTermino)) {
                        $li = 'bg-green-100 border-green-500'; // En curso
                        $estado = 'En Progreso'; // Estado cuando está en curso
                        $estadoClass = 'text-blue-500 font-semibold text-center';
                    } else {
                        $li = 'bg-gray-100 border-blue-500'; // Ya terminó
                        $estado = 'Completado'; // Estado cuando ya terminó
                        $estadoClass = 'text-green-500 font-semibold text-center';
                        if ($calificacion->calificacion === null) {
                            $estado = "Sin calificar";
                        }
                    }

                    if ($calificacion->calificacion !== null) {
                        if ($calificacion->calificacion <= 70) {
                            $estadoClass = 'text-red-500 font-semibold text-center';
                            $estado = 'Reprobado';
                        }
                    }
                @endphp

                <li class="py-4 px-6 border-l-4 {{ $li }} rounded-lg flex justify-between items-center">
                    <div>
                        <span class="text-sm font-medium">{{ $modulo->nombre }}</span>
                        <div class="text-sm">
                            <p>Instructor: {{ $modulo->instructore->user->datos_generales->nombre }} {{ $modulo->instructore->user->datos_generales->apellido_paterno }} {{ $modulo->instructore->user->datos_generales->apellido_materno }}</p>
                            <p>Inicio: {{ $modulo->fecha_inicio }}</p>
                            <p>Término: {{ $modulo->fecha_termino }}</p>
                        </div>
                    </div>
                    <!-- Mostrar el estado según el tipo -->
                    <p class="{{ $estadoClass }}">{{ $estado }}</p>

                    <!-- Mostrar la calificación -->
                    <p>
                        @if ($calificacion)
                            {{ $calificacion->calificacion ?? 'Sin calificación' }}
                        @else
                            Sin calificación
                        @endif
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-diplomados-layout>
