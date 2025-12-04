<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Solicitar curso') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col items-center pt-6 bg-gray-100">
        <form action="{{ route('solicitarcursos.store') }}" method="POST"
            class="w-full sm:max-w-4xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-section">
                    <x-input-label for="nombre" value="Nombre del curso/taller" />
                    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" />
                    @error('nombre')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-section">
                    <x-input-label for="instructor" value="Instructor(es) propuesto(s)" />
                    <x-text-input id="instructor" name="instructor" type="text" class="mt-1 block w-full" />
                    @error('instructor')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-section">
                    <x-input-label for="contacto_instructor" value="Contacto del instructor(es) propuesto(s)" />
                    <x-text-input id="contacto_instructor" name="contacto_instructor" type="text" class="mt-1 block w-full" />
                    @error('contacto_instructor')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-section">
                    <x-input-label for="participantes" value="Número aproximado de participantes" />
                    <x-text-input id="participantes" name="participantes" type="number" min="1" class="mt-1 block w-full" />
                    @error('participantes')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-section">
                    <x-input-label for="prioridad" value="Prioridad" />
                    <select name="prioridad" id="prioridad"
                        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Seleccione una prioridad</option>
                        <option value="Alta">Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                    @error('prioridad')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-section">
                    <x-input-label for="origen" value="Origen de la necesidad de capacitación" />
                    <select name="origen" id="origen"
                        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Seleccione un origen</option>
                        <option value="Evaluación Docente">Evaluación docente</option>
                        <option value="Evaluación Departamental">Evaluación Departamental</option>
                        <option value="Programas de estudio">Programas de estudio (análisis en academias)</option>
                        <option value="Concentrado de capacitación">Concentrado de capacitación</option>
                        <option value="Necesidades institucionales">Necesidades institucionales</option>
                        <option value="Otro">Otro:</option>
                    </select>
                    @error('origen')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-section md:col-span-2">
                    <x-input-label for="objetivo" value="Objetivo del curso/taller" />
                    <textarea id="objetivo" name="objetivo" rows="3" class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    @error('objetivo')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-section md:col-span-2">
                    <x-input-label for="requerimientos" value="Requerimientos de la capacitación y/o Comentarios adicionales" />
                    <textarea id="requerimientos" name="requerimientos" rows="3" class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    @error('requerimientos')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full" style="background: #4f46e5; border: none; color: white; padding: 12px; border-radius: 8px; font-size: 1rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#4338ca'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(79, 70, 229, 0.3)';" onmouseout="this.style.background='#4f46e5'; this.style.transform='translateY(0px)'; this.style.boxShadow='none';">
                    Registrar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
