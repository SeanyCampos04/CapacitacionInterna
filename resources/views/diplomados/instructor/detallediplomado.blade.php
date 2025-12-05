<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del diplomado:') }} {{$diplomado->nombre}}
        </h2>
    </x-slot>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .info-card h3, .info-card p, .info-card strong {
            color: white !important;
        }
        .info-card .text-gray-100 {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .module-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-align: center;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-active { background-color: #d1fae5; color: #065f46; }
        .status-completed { background-color: #dbeafe; color: #1e40af; }
    </style>

    <div class="container mx-auto mt-6 space-y-6">

        <!-- Información General del Diplomado -->
        <div class="info-card">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-2xl font-bold mb-4">
                        <i class="fas fa-graduation-cap mr-3"></i>{{ $diplomado->nombre }}
                    </h3>
                    <div class="space-y-3">
                        <p><strong><i class="fas fa-bullseye mr-2"></i>Objetivo:</strong></p>
                        <p class="text-gray-100 leading-relaxed">{{ $diplomado->objetivo }}</p>

                        <p><strong><i class="fas fa-align-left mr-2"></i>Descripción:</strong></p>
                        <p class="text-gray-100 leading-relaxed">{{ $diplomado->descripcion ?? 'Sin descripción disponible' }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="bg-white bg-opacity-20 rounded-lg p-4">
                        <p class="flex items-center mb-2">
                            <i class="fas fa-calendar-alt mr-3"></i><strong>Periodo de Realización</strong>
                        </p>
                        <p class="text-lg">
                            {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->format('d/m/Y') }}
                        </p>
                    </div>

                    <div class="bg-white bg-opacity-20 rounded-lg p-4">
                        <p class="flex items-center mb-2">
                            <i class="fas fa-map-marker-alt mr-3"></i><strong>Sede</strong>
                        </p>
                        <p class="text-lg">{{ $diplomado->sede }}</p>
                    </div>

                    <div class="bg-white bg-opacity-20 rounded-lg p-4">
                        <p class="flex items-center mb-2">
                            <i class="fas fa-tag mr-3"></i><strong>Tipo</strong>
                        </p>
                        <p class="text-lg">{{ $diplomado->tipo }}</p>
                    </div>

                    <div class="bg-white bg-opacity-20 rounded-lg p-4">
                        <p class="flex items-center mb-2">
                            <i class="fas fa-clock mr-3"></i><strong>Total de Módulos</strong>
                        </p>
                        <p class="text-lg">{{ $modulos->count() }} módulos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulos del Diplomado -->
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h3 class="text-xl font-semibold mb-6 text-gray-800">
                <i class="fas fa-list-alt mr-3 text-indigo-600"></i>Módulos del Diplomado
            </h3>

            @if($modulos->count() > 0)
                <div class="space-y-4">
                    @foreach ($modulos as $modulo)
                        @php
                           $fechaActual = \Carbon\Carbon::now();
                           $fechaInicio = \Carbon\Carbon::parse($modulo->fecha_inicio);
                           $fechaTermino = \Carbon\Carbon::parse($modulo->fecha_termino);

                           if ($fechaActual->lt($fechaInicio)) {
                               $statusClass = 'status-pending';
                               $estado = 'No Iniciado';
                               $icon = 'fas fa-clock';
                               $iconColor = 'text-yellow-600';
                           } elseif ($fechaActual->between($fechaInicio, $fechaTermino)) {
                               $statusClass = 'status-active';
                               $estado = 'En Progreso';
                               $icon = 'fas fa-play-circle';
                               $iconColor = 'text-green-600';
                           } else {
                               $statusClass = 'status-completed';
                               $estado = 'Completado';
                               $icon = 'fas fa-check-circle';
                               $iconColor = 'text-blue-600';
                           }

                           // Contar los participantes por calificar
                           $participantesSinCalificar = $modulo->calificacionesModulos->whereNull('calificacion')->count();
                           $totalParticipantes = $modulo->calificacionesModulos->count();
                        @endphp

                        <div class="module-card bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-book mr-2 text-indigo-600"></i>{{ $modulo->nombre }}
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                        <div>
                                            <p class="mb-2"><strong>Número de módulo:</strong> {{ $modulo->numero }}</p>
                                            <p class="mb-2">
                                                <i class="fas fa-chalkboard-teacher mr-2"></i><strong>Instructor:</strong>
                                                {{ $modulo->instructore->user->datos_generales->nombre }}
                                                {{ $modulo->instructore->user->datos_generales->apellido_paterno }}
                                                {{ $modulo->instructore->user->datos_generales->apellido_materno }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="mb-2">
                                                <i class="fas fa-calendar-start mr-2"></i><strong>Inicio:</strong>
                                                {{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') }}
                                            </p>
                                            <p class="mb-2">
                                                <i class="fas fa-calendar-check mr-2"></i><strong>Término:</strong>
                                                {{ \Carbon\Carbon::parse($modulo->fecha_termino)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end space-y-2">
                                    <div class="status-badge {{ $statusClass }}">
                                        <i class="{{ $icon }} mr-1"></i>{{ $estado }}
                                    </div>
                                </div>
                            </div>

                            <!-- Información de participantes -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                    <div class="bg-white rounded-lg p-3">
                                        <p class="text-2xl font-bold text-blue-600">{{ $totalParticipantes }}</p>
                                        <p class="text-sm text-gray-600">Total Participantes</p>
                                    </div>
                                    @if ($estado === 'Completado')
                                        <div class="bg-white rounded-lg p-3">
                                            <p class="text-2xl font-bold text-green-600">{{ $totalParticipantes - $participantesSinCalificar }}</p>
                                            <p class="text-sm text-gray-600">Calificados</p>
                                        </div>
                                        <div class="bg-white rounded-lg p-3">
                                            <p class="text-2xl font-bold {{ $participantesSinCalificar > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $participantesSinCalificar }}</p>
                                            <p class="text-sm text-gray-600">Por Calificar</p>
                                        </div>
                                    @else
                                        <div class="bg-white rounded-lg p-3">
                                            <p class="text-2xl font-bold text-gray-400">-</p>
                                            <p class="text-sm text-gray-600">Calificados</p>
                                        </div>
                                        <div class="bg-white rounded-lg p-3">
                                            <p class="text-2xl font-bold text-gray-400">-</p>
                                            <p class="text-sm text-gray-600">Por Calificar</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('diplomados.detalle.modulo.participantes', $modulo) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-users mr-2"></i>Ver Participantes
                                </a>

                                @if ($estado === 'Completado' && $participantesSinCalificar > 0)
                                    <a href="{{ route('diplomados.detalle.modulo.participantes', $modulo) }}"
                                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors duration-200">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Calificar Pendientes
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-info-circle text-6xl text-gray-400 mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-600 mb-2">No hay módulos registrados</h4>
                        <p class="text-gray-500">Este diplomado aún no tiene módulos asignados.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Botón de regreso -->
        <div class="flex justify-start">
            <a href="{{ route('diplomados.curso_instructor') }}"
               class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Regresar a Diplomados en Curso
            </a>
        </div>
    </div>
</x-app-diplomados-layout>
