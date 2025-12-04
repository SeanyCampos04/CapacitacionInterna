<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Registrar curso') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
                <form action="{{ route('cursos.store') }}" method="POST" onsubmit="return validateDates()">
            @csrf
            <x-text-input id="dncId" name="dncId" type="hidden" class="mt-1 block w-full" value="{{$solicitarcurso->id ?? ''}}"/>

            <!-- Nombre del curso -->
            <div class="mt-4">
                <x-input-label for="nombre" value="Nombre del curso" />
                <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full"
                    value="{{ $solicitarcurso->nombre ?? '' }}" />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>
            <!-- Instructores -->
            <div class="mt-4">
                <x-input-label for="instructores" value="Seleccionar instructores" />
                <select name="instructores[]" id="instructores" multiple
                        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    @foreach ($instructores as $instructor)
                        <option value="{{ $instructor->instructor->id }}">
                            {{ $instructor->datos_generales->nombre }}
                            {{ $instructor->datos_generales->apellido_paterno }}
                            {{ $instructor->datos_generales->apellido_materno }}
                        </option>
                    @endforeach
                </select>
                <small class="text-gray-500">Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar varios instructores.</small>
                <x-input-error :messages="$errors->get('instructores')" class="mt-2" />
            </div>
            <!-- departamento -->
            <div class="mt-4">
                <x-input-label for="departamento" :value="__('Departamento')" />
                <select name="departamento" id="departamento" class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    @foreach ($departamentos as $departamento )
                        <option value="{{$departamento->id}}">{{$departamento->nombre}}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('departamento')" class="mt-2" />
            </div>
            <!-- fecha de inicio -->
            <div class="mt-4">
                <x-input-label for="fecha_inicio" value="Fecha de Inicio" />
                <x-text-input id="fecha_inicio" name="fecha_inicio" type="date" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('fecha_inicio')" class="mt-2" />
            </div>
            <!-- fecha final -->
            <div class="mt-4">
                <x-input-label for="fecha_final" value="Fecha Final" />
                <x-text-input id="fecha_final" name="fecha_final" type="date" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('fecha_final')" class="mt-2" />
            </div>

            <!-- objetivo -->
            <div class="mt-4">
                <x-input-label for="objetivo" value="Objetivo" />
                <x-text-input id="objetivo" name="objetivo" type="text" class="mt-1 block w-full"
                    value="{{ $solicitarcurso->objetivo ?? '' }}" />
                <x-input-error :messages="$errors->get('objetivo')" class="mt-2" />
            </div>
            <!-- Modalidad -->
            <div class="mt-4">
                <x-input-label for="modalidad" value="Modalidad" />
                <select name="modalidad" id="modalidad"
                class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="Presencial">Presencial</option>
                    <option value="Linea">En linea</option>
                    <option value="Mixta">Mixta</option>
                </select>
                <x-input-error :messages="$errors->get('modalidad')" class="mt-2" />
            </div>
            <!-- lugar -->
            <div class="mt-4">
                <x-input-label for="lugar" value="Lugar" />
                <x-text-input id="lugar" name="lugar" type="text" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('lugar')" class="mt-2" />
            </div>
            <!-- Horario -->
            <div class="mt-4">
                <x-input-label for="horario" value="Horario" />
                <x-text-input id="horario" name="horario" type="text" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('horario')" class="mt-2" />
            </div>
            <!-- Duracion -->
            <div class="mt-4">
                <x-input-label for="duracion" value="Duracion" />
                <x-text-input id="duracion" name="duracion" type="text" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('duracion')" class="mt-2" />
            </div>
            <!-- No registro -->
            <div class="mt-4">
                <x-input-label for="no_registro" value="Numero registro" />
                <x-text-input id="no_registro" name="no_registro" type="text" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('no_registro')" class="mt-2" />
            </div>
            <!-- Periodo -->
            <div class="mt-4">
                <x-input-label for="periodo" value="Periodo" />
                <select name="periodo" id="periodo"
                    class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'">
                    @foreach ($periodos as $periodo)
                        <option value="{{ $periodo->id }}">{{ $periodo->periodo }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('periodo')" class="mt-2" />
            </div>
            <!-- tipo -->
            <div class="mt-4">
                <x-input-label for="tipo" value="Tipo" />
                    <select name="tipo" id="tipo"
                        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="Curso">Curso</option>
                        <option value="Taller">Taller</option>
                        <option value="Curso/Taller">Curso/Taller</option>
                    </select>
                <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
            </div>
            <!-- Clase -->
            <div class="mt-4">
                <x-input-label for="clase" value="Clase" />
                <select id="clase" name="clase" class="mt-1 block w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="Docente">Docente</option>
                    <option value="Profesional">Profesional</option>
                </select>
                <x-input-error :messages="$errors->get('clase')" class="mt-2" />
            </div>

            <!-- limite_participantes -->
            <div class="mt-4">
                <x-input-label for="limite_participantes" value="Limite de participantes" />
                <x-text-input id="limite_participantes" name="limite_participantes" type="text" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('limite_participantes')" class="mt-2" />
            </div>
            <!-- Es tics -->
            <div class="mt-4">
                <x-input-label for="es_tics" value="" />
                <div class="flex items-center mt-1">
                    <input type="checkbox" id="es_tics" name="es_tics" value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <label for="es_tics" class="ml-2 text-gray-700">Aporta Competencias Digitales</label>
                </div>
            </div>
            <!-- Es tutorias -->
            <div class="mt-4">
                <x-input-label for="es_tutorias" value="" />
                <div class="flex items-center mt-1">
                    <input type="checkbox" id="es_tutorias" name="es_tutorias" value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <label for="es_tutorias" class="ml-2 text-gray-700">Aporta Formación Tutorial</label>
                </div>
            </div>
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 mt-4">
                        Registrar Curso
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
