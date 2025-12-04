<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
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

            <div class="mt-6">
                <button type="submit" class="w-full" style="background: #4f46e5; border: none; color: white; padding: 12px; border-radius: 8px; font-size: 1rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#4338ca'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(79, 70, 229, 0.3)';" onmouseout="this.style.background='#4f46e5'; this.style.transform='translateY(0px)'; this.style.boxShadow='none';">
                    Guardar Calificación
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
