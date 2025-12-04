<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Cursos Terminados") }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .optimized-container {
            width: 95%;
            max-width: 1200px !important;
            margin: 2rem auto !important;
            padding: 1.5rem;
        }
    </style>

    <div class="py-12">
        <div class="optimized-container bg-white shadow-lg rounded-lg">
            <table class="w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Nombre</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Instructor</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Departamento</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Periodo</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Calificación</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Estatus</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cursosTerminados as $cursoTerminado)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b border-gray-200 text-sm font-semibold text-blue-600">{{ $cursoTerminado->curso->nombre }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                @foreach ($cursoTerminado->curso->instructores as $instructor)
                                    {{ $instructor->user->datos_generales->nombre }}
                                    {{ $instructor->user->datos_generales->apellido_paterno }}
                                    {{ $instructor->user->datos_generales->apellido_materno }}<br>
                                @endforeach
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm text-center">{{ $cursoTerminado->curso->departamento->nombre }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm text-center">{{ $cursoTerminado->curso->periodo->periodo }}</td>
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
                            <td class="py-2 px-4 border-b border-gray-200 text-center">
                                <a href="{{ route('cursos_terminados.show', $cursoTerminado->curso->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 transition-colors duration-200"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-2 px-4 text-center text-gray-500">
                                No hay cursos terminados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
