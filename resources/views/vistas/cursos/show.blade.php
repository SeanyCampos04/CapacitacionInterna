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
                                        <span class="font-medium">
                                            {{ $instructor->user->datos_generales->nombre }}
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
                        <p class="text-sm text-gray-700"><strong>Departamento:</strong>
                            {{ $curso->departamento->nombre }}</p>
                        <p class="text-sm text-gray-700"><strong>Periodo:</strong> {{ $curso->periodo->periodo }}</p>
                        <p class="text-sm text-gray-700"><strong>Fecha de inicio:</strong> {{ $curso->fdi }}</p>
                        <p class="text-sm text-gray-700"><strong>Fecha de terminación:</strong> {{ $curso->fdf }}</p>
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

                        <!-- Mostrar ficha técnica existente -->
                        @if ($curso->ficha_tecnica)
                            <div class="mb-4">
                                <p class="text-sm text-gray-700"><strong>Ficha Técnica Actual:</strong></p>
                                <a href="{{ asset('uploads/' . $curso->ficha_tecnica) }}"
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800">
                                   Ver ficha técnica actual (PDF)
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-gray-700">No se ha subido ninguna ficha técnica para este curso.</p>
                        @endif

                        @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))

                            <div class="grid grid-cols-3 gap-2 w-full max-w-[400px]">
                                <form action="{{ route('cursos.edit', $curso->id) }}" method="GET" class="">
                                    @csrf
                                    @method('GET')
                                    <x-primary-button class="justify-center common-button w-full">Editar</x-primary-button>
                                </form>
                                @if ($curso->estatus == 0)
                                    <form action="{{ route('iniciar_cursos.update', ['curso' => $curso]) }}"
                                        method="POST" class="">
                                        @csrf
                                        @method('PUT')
                                        <x-primary-button class="justify-center common-button w-full">Iniciar
                                            curso</x-primary-button>
                                    </form>
                                @endif

                                @if ($curso->estatus == 1)
                                    <form action="{{ route('terminar_cursos.update', ['curso' => $curso]) }}"
                                        method="POST" class="">
                                        @csrf
                                        @method('PUT')
                                        <x-primary-button class="justify-center common-button w-full"
                                            onclick="return confirm('¿Estás seguro de que quieres terminar este curso?');">Terminar
                                            curso</x-primary-button>
                                    </form>
                                @endif

                                @if ($curso->estatus == 0)
                                    <form action="{{ route('encuesta.resultados', $curso->id) }}" method="GET"
                                        class="">
                                        @csrf
                                        @method('GET')
                                        <x-primary-button class="justify-center common-button w-full">Ver
                                            resultados</x-primary-button>
                                    </form>
                                @endif

                                @if ($curso->estado_calificacion == 0 or $curso->estatus == 1)
                                    <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST"
                                        class="">
                                        @csrf
                                        @method('DELETE')
                                        <x-primary-button
                                            class="justify-center w-full common-button bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-0 "
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este curso?');">Eliminar</x-primary-button>
                                    </form>
                                @endif

                                <form action="{{ route('curso.pdf', $curso->id) }}" method="get" class="">
                                    @csrf
                                    @method('GET')
                                    <x-primary-button
                                        class="justify-center w-full common-button bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0 ">Generar
                                        pdf</x-primary-button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Instructores -->
    @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
        <div class="py-6">
            <h1 class="text-center text-xl"><strong>Instructor del curso</strong></h1>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xm text-gray-700 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center">Correo</th>
                                    <th scope="col" class="px-6 py-3 text-center">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-center">Departamento</th>
                                    <th scope="col" class="px-6 py-3 text-center">Reconocimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($curso->instructores as $instructor)
                                    <tr class="bg-white border-b">
                                        <td class="text-center px-6 py-4">{{ $instructor->user->email }}</td>
                                        <td class="text-center px-6 py-4">
                                            {{ $instructor->user->datos_generales->nombre }}
                                            {{ $instructor->user->datos_generales->apellido_paterno }}
                                            {{ $instructor->user->datos_generales->apellido_materno }}
                                        </td>
                                        <td class="text-center px-6 py-4">
                                            {{ $instructor->user->datos_generales->departamento->nombre }}
                                        </td>
                                        <td class="text-center px-6 py-4">
                                            <a href="{{ route('curso.reconocimiento.instructor', ['curso_id' => $curso->id, 'instructor_id' => $instructor->id]) }}" target="_blank">
                                                <x-primary-button class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-0">
                                                    🏆 Reconocimiento
                                                </x-primary-button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12">
        <h1 class="text-center text-xl"><strong>Docentes inscritos</strong></h1>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xm text-gray-700 bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Correo
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Departamento
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Calificación
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Estatus
                                </th>
                                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Constancia
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Acciones
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ParticipantesOrdenados as $participanteInscrito)
                                <tr class="bg-white border-b">
                                    <td class="text-left">{{ $participanteInscrito->participante->user->email }}</td>
                                    <td class="text-left">
                                        {{ $participanteInscrito->participante->user->datos_generales->apellido_paterno }}
                                        {{ $participanteInscrito->participante->user->datos_generales->apellido_materno }}
                                        {{ $participanteInscrito->participante->user->datos_generales->nombre }}
                                    </td>
                                    <td class="text-left">
                                        {{ $participanteInscrito->participante->user->datos_generales->departamento->nombre }}
                                    </td>
                                    @if ($curso->estado_calificacion != 0)
                                        <td class="text-center">
                                            @if ($participanteInscrito->calificacion)
                                                {{ $participanteInscrito->calificacion }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if ($participanteInscrito->acreditado == 2)
                                            <td class="text-center text-green-600">Acreditado</td>
                                        @endif
                                        @if ($participanteInscrito->acreditado == 1)
                                            <td class="text-center text-red-600">No Acreditado</td>
                                        @endif
                                        @if ($participanteInscrito->acreditado == 0)
                                            <td class="text-center text-blue-600">Sin Calificar</td>
                                        @endif
                                    @else
                                        <td class="text-center">
                                            -
                                        </td>
                                        <td class="text-center text-blue-600">Sin Calificar</td>
                                    @endif
                                    @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                        <!-- Columna Constancia -->
                        <td class="text-center">
                            @if ($participanteInscrito->acreditado == 2)
                                <a href="{{ route('curso.constancia', ['curso_id' => $curso->id, 'participante_id' => $participanteInscrito->id]) }}" target="_blank">
                                    <x-primary-button class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0">
                                        📄 Constancia
                                    </x-primary-button>
                                </a>
                            @else
                                <span class="text-gray-400">No disponible</span>
                            @endif
                        </td>                                        <!-- Columna Acciones -->
                                        @if ($curso->estatus == 1 || $curso->estado_calificacion == 0)
                                            <td class="text-center">
                                                <form
                                                    action="{{ route('curso_participante.destroy', ['participanteInscrito' => $participanteInscrito]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-primary-button
                                                        class="common-button bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-0"
                                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este docente del curso?');">Eliminar</x-primary-button>
                                                </form>
                                            </td>
                                        @else
                                            <td class="text-center">-</td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4 text-right">

                <div class="flex justify-end space-x-4">
                    @if ($curso->estado_calificacion == 1 and $curso->estatus == 0)
                        @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                            <form action="{{ route('admin.entregar_calificacion', $curso->id) }}" method="GET">
                                @csrf
                                <x-primary-button
                                    class="common-button bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0"
                                    onclick="return confirm('¿Estás seguro de que quieres subir las calificaciones? ⚠️UNA VEZ SUBIDAS EL INSTRUCTOR NO PODRÁ HACER CAMBIOS⚠️');">
                                    Subir calificacion
                                </x-primary-button>
                            </form>

                            <form action="{{ route('admin.devolver_calificacion', $curso->id) }}" method="GET">
                                @csrf
                                <x-primary-button
                                    class="common-button bg-yellow-600 text-white hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-0"
                                    onclick="return confirm('¿Estás seguro de que quieres devolver las calificaciones al instructor?');">
                                    Devolver calificacion
                                </x-primary-button>
                            </form>
                        @endif
                    @elseif ($curso->estado_calificacion == 2)
                        Calificaciones subidas
                    @endif
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
