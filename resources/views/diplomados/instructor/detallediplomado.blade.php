<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del diplomado:') }} {{$diplomado->nombre}}
        </h2>
    </x-slot>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">
        <ul>
            @foreach ($modulos as $modulo)
                @php
                   $fechaActual = \Carbon\Carbon::now();
                   $fechaInicio = \Carbon\Carbon::parse($modulo->fecha_inicio);
                   $fechaTermino = \Carbon\Carbon::parse($modulo->fecha_termino);

                   if ($fechaActual->lt($fechaInicio)) {
                       $li = 'bg-gray-100 border-blue-500'; // Aún no comienza
                       $estado = 'No Iniciado';
                       $estadoClass = 'text-gray-500 text-center';
                   } elseif ($fechaActual->between($fechaInicio, $fechaTermino)) {
                       $li = 'bg-green-100 border-green-500'; // En curso
                       $estado = 'En Progreso';
                       $estadoClass = 'text-blue-500 font-semibold text-center';
                   } else {
                       $li = 'bg-gray-100 border-blue-500'; // Ya terminó
                       $estado = 'Completado';
                       $estadoClass = 'text-green-500 font-semibold text-center';
                   }

                   // Contar los participantes por calificar
                   $participantesSinCalificar = $modulo->calificacionesModulos->whereNull('calificacion')->count();
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
                    <p class="{{ $estadoClass }}">{{ $estado }}</p>

                    <!-- Mostrar cantidad de participantes sin calificar si el módulo está completado -->
                    @if ($estado === 'Completado' && $participantesSinCalificar > 0)
                        <p class="text-red-500 font-semibold">Participantes por calificar: {{ $participantesSinCalificar }}</p>
                    @endif

                    <a href="{{ route('diplomados.detalle.modulo.participantes', $modulo) }}"
                       class="inline-flex items-center px-3 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600 transition-colors duration-200">
                        <i class="fas fa-users mr-2"></i>Ver participantes
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-diplomados-layout>
