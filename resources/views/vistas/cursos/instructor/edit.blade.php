<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Calificar Participante:') }} {{ $curso_participante->participante->nombre_completo }}
        </h2>
    </x-slot>
    <div class="min-h-screen flex flex-col items-center pt-6 bg-gray-100">
        <form action="{{ route('instructor.update', ['cursos_participante' => $curso_participante]) }}" method="POST"
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @csrf
            @method('PUT')

            <!-- Información del participante -->
            <div class="mt-4">
                <x-input-label for="nombre_participante" value="Nombre del Participante" />
                <x-text-input id="nombre_participante" name="nombre_participante" type="text"
                    class="mt-1 block w-full" value="{{ $curso_participante->participante->user->datos_generales->nombre }} {{ $curso_participante->participante->user->datos_generales->apellido_paterno }} {{ $curso_participante->participante->user->datos_generales->apellido_materno }}" readonly />
            </div>

            <!-- Nombre del curso -->
            <div class="mt-4">
                <x-input-label for="nombre_curso" value="Nombre del Curso" />
                <x-text-input id="nombre_curso" name="nombre_curso" type="text"
                    class="mt-1 block w-full" value="{{ $curso_participante->curso->nombre }}" readonly />
            </div>

            <!-- Calificación -->
            <div class="mt-4">
                <x-input-label for="calificacion" value="Calificación" />
                <x-text-input id="calificacion" name="calificacion" type="number" step="0.01" min="0" max="100"
                    class="mt-1 block w-full" value="{{ old('calificacion', $curso_participante->calificacion ?? '') }}" required />
                <x-input-error :messages="$errors->get('calificacion')" class="mt-2" />
            </div>

            <!-- Comentarios -->
            <div class="mt-4">
                <x-input-label for="comentarios" value="Comentarios" />
                <textarea id="comentarios" name="comentarios" rows="4"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">{{ old('Comentarios', $curso_participante->comentarios ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('comentarios')" class="mt-2" />
            </div>

            <x-primary-button class="mt-4">Guardar Calificación</x-primary-button>
        </form>
    </div>
</x-app-layout>
