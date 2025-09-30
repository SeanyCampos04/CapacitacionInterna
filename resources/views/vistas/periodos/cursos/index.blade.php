<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Cursos del periodo:') }} {{ $periodo->periodo ?? 'Sin periodo' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                    <!--  Buscador de cursos -->
                    <form action="{{ route('cursos.index') }}" method="GET" class="mb-4 flex justify-end p-4">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Buscar por curso, instructor o departamento"
                            class="border border-gray-300 rounded-lg px-3 py-2 mr-2 w-1/3"
                        >
                        <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                            Buscar
                        </x-primary-button>
                    </form>

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xm text-gray-700 bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-center">Nombre</th>
                                <th class="px-6 py-3 text-center">Instructor</th>
                                <th class="px-6 py-3 text-center">Departamento</th>
                                <th class="px-6 py-3 text-center">Periodo</th>
                                <th class="px-6 py-3 text-center">Modalidad</th>
                                <th class="px-6 py-3 text-center">Inscritos</th>
                                <th class="px-6 py-3 text-center">Estado</th>
                                <th class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cursos as $curso)
                                <tr class="bg-white border-b">
                                    <td class="text-center">{{ $curso->nombre }}</td>
                                    <td class="text-center">
                                        @foreach ($curso->instructores as $instructor)
                                            {{ $instructor->user->datos_generales->nombre ?? '' }}
                                            {{ $instructor->user->datos_generales->apellido_paterno ?? '' }}
                                            {{ $instructor->user->datos_generales->apellido_materno ?? '' }}
                                            <br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $curso->departamento->nombre ?? 'Sin departamento' }}</td>
                                    <td class="text-center">{{ $curso->periodo->periodo ?? 'Sin periodo' }}</td>
                                    <td class="text-center">{{ $curso->modalidad }}</td>
                                    <td class="text-center">{{ $curso->cursos_participantes->count() }}/{{ $curso->limite_participantes }}</td>
                                    <td class="text-center">
                                        {{ $curso->estatus == 1 ? 'Disponible' : 'Terminado' }}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('cursos.show', $curso->id) }}" method="GET">
                                            @csrf
                                            @method('GET')
                                            <x-primary-button class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-0">
                                                Ver detalles
                                            </x-primary-button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b">
                                    <td colspan="8" class="text-center py-4 text-gray-500">
                                        No hay cursos disponibles en este periodo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
