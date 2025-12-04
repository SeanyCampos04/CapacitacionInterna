<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar curso') }}
        </h2>
    </x-slot>

    <!-- Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="min-h-screen flex flex-col  items-center pt-6  bg-gray-100">
        <form action="{{ route('cursos.update', ['curso' => $curso]) }}" method="POST"
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @csrf
            @method('PUT')
            <!-- Nombre del curso -->
            <div class="mt-4">
                <x-input-label for="nombre" value="Nombre del curso" />
                <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" value="{{ $curso->nombre }}"/>
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>
            <!-- Instructor -->
            <div class="mt-4">
                <x-input-label for="instructores" value="Instructor" />
                <select name="instructores[]" id="instructores" multiple
                    class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                    @foreach ($instructores as $instructor)
                        <option value="{{ $instructor->instructor->id }}"
                            @foreach ($curso->instructores as $instructore)
                                {{$instructore->id == $instructor->instructor->id ? 'selected' : ''}}
                            @endforeach>
                            {{ $instructor->datos_generales->nombre}}
                            {{ $instructor->datos_generales->apellido_paterno}}
                            {{ $instructor->datos_generales->apellido_materno}}
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
                        <option value="{{$departamento->id}}" {{$curso->departamento->id == $departamento->id ? 'selected' : ''}}>{{$departamento->nombre}}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('departamento')" class="mt-2" />
            </div>
            <!-- fecha de inicio  -->
            <div class="mt-4">
                <x-input-label for="fecha_inicio" value="Fecha de inicio" />
                <x-text-input id="fecha_inicio" name="fecha_inicio" type="date" class="mt-1 block w-full"
                    value="{{ $curso->fdi }}" />
                <x-input-error :messages="$errors->get('fecha_inicio')" class="mt-2" />
            </div>
            <!-- fecha final -->
            <div class="mt-4">
                <x-input-label for="fecha_final" value="Fecha final" />
                <x-text-input id="fecha_final" name="fecha_final" type="date" class="mt-1 block w-full"
                    value="{{ $curso->fdf }}" />
                <x-input-error :messages="$errors->get('fecha_final')" class="mt-2" />
            </div>
            <!-- objetivo -->
            <div class="mt-4">
                <x-input-label for="objetivo" value="Objetivo" />
                <x-text-input id="objetivo" name="objetivo" type="text" class="mt-1 block w-full"
                    value="{{ $curso->objetivo }}" />
                    <x-input-error :messages="$errors->get('objetivo')" class="mt-2" />
            </div>
            <!-- Modalidad -->
            <div class="mt-4">
                <x-input-label for="modalidad" value="Modalidad" />
                <select name="modalidad" id="modalidad"
                class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="{{ $curso->modalidad}}">{{ $curso->modalidad}}</option>
                    @if ($curso->modalidad != "Presencial")
                        <option value="Presencial">Presencial</option>
                    @endif
                    @if ($curso->modalidad != "Linea")
                        <option value="Linea">En Linea</option>
                    @endif
                    @if ($curso->modalidad != "Mixta")
                        <option value="Mixta">Mixta</option>
                    @endif
                </select>
                <x-input-error :messages="$errors->get('modalidad')" class="mt-2" />
            </div>
            <!-- lugar -->
            <div class="mt-4">
                <x-input-label for="lugar" value="Lugar" />
                <x-text-input id="lugar" name="lugar" type="text" class="mt-1 block w-full" value="{{$curso->lugar}}"/>
                    <x-input-error :messages="$errors->get('lugar')" class="mt-2" />
            </div>
            <!-- Horario -->
            <div class="mt-4">
                <x-input-label for="horario" value="Horario" />
                <x-text-input id="horario" name="horario" type="text" class="mt-1 block w-full" value="{{$curso->horario}}"/>
                    <x-input-error :messages="$errors->get('horario')" class="mt-2" />
            </div>
            <!-- Duracion -->
            <div class="mt-4">
                <x-input-label for="duracion" value="Duracion" />
                <x-text-input id="duracion" name="duracion" type="text" class="mt-1 block w-full" value="{{$curso->duracion}}"/>
                    <x-input-error :messages="$errors->get('duracion')" class="mt-2" />
            </div>
            <!-- No registro -->
            <div class="mt-4">
                <x-input-label for="no_registro" value="Numero registro" />
                <x-text-input id="no_registro" name="no_registro" type="text" class="mt-1 block w-full" value="{{$curso->no_registro}}"/>
                    <x-input-error :messages="$errors->get('no_registro')" class="mt-2" />
            </div>
            <!-- Periodo -->
            <div class="mt-4">
                <x-input-label for="periodo" value="Periodo" />
                <select name="periodo" id="periodo"
                    class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'">
                    <option value="{{ $curso->periodo->id }}">{{ $curso->periodo->periodo }}</option>
                    @foreach ($periodos as $periodo)
                        @if ($curso->periodo->id != $periodo->id)
                            <option value="{{ $periodo->id }}">{{ $periodo->periodo }}</option>
                        @endif
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('periodo')" class="mt-2" />
            </div>
            <!-- tipo -->
            <div class="mt-4">
                <x-input-label for="tipo" value="Tipo" />
                    <select name="tipo" id="tipo"
                        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="{{ $curso->tipo}}">{{ $curso->tipo}}</option>
                        @if ($curso->tipo != "Curso")
                            <option value="Curso">Curso</option>
                        @endif
                        @if ($curso->tipo != "Taller")
                            <option value="Taller">Taller</option>
                        @endif
                        @if ($curso->tipo != "Curso/Taller")
                            <option value="Curso/Taller">Curso/Taller</option>
                        @endif
                    </select>
                    <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
            </div>
             <!-- clase -->
            <div class="mt-4">
                <x-input-label for="clase" value="Clase" />
                <select id="clase" name="clase" class="mt-1 block w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="Docente" {{$curso->clase == "Docente" ? 'selected' : ''}}>Docente</option>
                    <option value="Profesional" {{$curso->clase == "Profesional" ? 'selected' : ''}}>Profesional</option>
                </select>
                <x-input-error :messages="$errors->get('clase')" class="mt-2" />
            </div>
            <!-- limite_participantes -->
            <div class="mt-4">
                <x-input-label for="limite_participantes" value="limite de participantes" />
                <x-text-input id="limite_participantes" name="limite_participantes" type="text" class="mt-1 block w-full" value="{{$curso->limite_participantes}}" />
                <x-input-error :messages="$errors->get('limite_participantes')" class="mt-2" />
            </div>
            <!-- Es tics -->
            <div class="mt-4">
                <x-input-label for="es_tics" value="" />
                <div class="flex items-center mt-1">
                    <input type="checkbox" id="es_tics" name="es_tics" value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $curso->es_tics ? 'checked' : '' }}>
                    <label for="es_tics" class="ml-2 text-gray-700">Aporta Competencias Digitales</label>
                </div>
            </div>
            <!-- Es tutorias -->
            <div class="mt-4">
                <x-input-label for="es_tutorias" value="" />
                <div class="flex items-center mt-1">
                    <input type="checkbox" id="es_tutorias" name="es_tutorias" value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $curso->es_tutorias ? 'checked' : '' }}>
                    <label for="es_tutorias" class="ml-2 text-gray-700">Aporta Formación Tutorial</label>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" name="action" value="editar"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>
                    Actualizar Curso
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
