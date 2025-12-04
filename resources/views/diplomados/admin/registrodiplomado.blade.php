<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($diplomado) ? 'Editar Diplomado' : 'Registro de Diplomado' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
                <form
                    action="{{ isset($diplomado) ? route('diplomados.diplomados.update', $diplomado->id) : route('diplomados.diplomados.store') }}"
                    method="POST" enctype="multipart/form-data" onsubmit="return validateDates()">
                    @csrf
                    @if (isset($diplomado))
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre:</label>
                        <input type="text" id="nombre" name="nombre"
                            value="{{ isset($diplomado) ? $diplomado->nombre : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="objetivo" class="block text-sm font-medium text-gray-700">Objetivo:</label>
                        <input type="text" id="objetivo" name="objetivo"
                            value="{{ isset($diplomado) ? $diplomado->objetivo : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Diplomado:</label>
                        <select id="tipo" name="tipo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <option value="Interno" {{ isset($diplomado) && $diplomado->tipo == 'Interno' ? 'selected' : '' }}>
                                Interno
                            </option>
                            <option value="Externo" {{ isset($diplomado) && $diplomado->tipo == 'Externo' ? 'selected' : '' }}>
                                Externo
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="clase" class="block text-sm font-medium text-gray-700">Categoría:</label>
                        <select id="clase" name="clase"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <option value="Docente" {{ isset($diplomado) && $diplomado->clase == 'Docente' ? 'selected' : '' }}>
                                Docente
                            </option>
                            <option value="Profesionalizante" {{ isset($diplomado) && $diplomado->clase == 'Profesionalizante' ? 'selected' : '' }}>
                                Profesionalizante
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="sede" class="block text-sm font-medium text-gray-700">Sede:</label>
                        <input type="text" id="sede" name="sede"
                            value="{{ isset($diplomado) ? $diplomado->sede : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="responsable" class="block text-sm font-medium text-gray-700">Nombre del Responsable:</label>
                        <input type="text" id="responsable" name="responsable"
                            value="{{ isset($diplomado) ? $diplomado->responsable : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="correo_contacto" class="block text-sm font-medium text-gray-700">Correo de Contacto:</label>
                        <input type="email" id="correo_contacto" name="correo_contacto"
                            value="{{ isset($diplomado) ? $diplomado->correo_contacto : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="inicio_oferta" class="block text-sm font-medium text-gray-700">Fecha de inicio para la oferta:</label>
                        <input type="date" id="inicio_oferta" name="inicio_oferta"
                            value="{{ isset($diplomado) ? $diplomado->inicio_oferta : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="termino_oferta" class="block text-sm font-medium text-gray-700">Fecha de término para la oferta:</label>
                        <input type="date" id="termino_oferta" name="termino_oferta"
                            value="{{ isset($diplomado) ? $diplomado->termino_oferta : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="inicio_realizacion" class="block text-sm font-medium text-gray-700">Fecha de inicio para la realización:</label>
                        <input type="date" id="inicio_realizacion" name="inicio_realizacion"
                            value="{{ isset($diplomado) ? $diplomado->inicio_realizacion : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="termino_realizacion" class="block text-sm font-medium text-gray-700">Fecha de término para la realización:</label>
                        <input type="date" id="termino_realizacion" name="termino_realizacion"
                            value="{{ isset($diplomado) ? $diplomado->termino_realizacion : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                        {{ isset($diplomado) ? 'Guardar Cambios' : 'Registrar Diplomado' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateDates() {
            const terminoOfertaValue = document.getElementById('termino_oferta').value;
            const inicioRealizacionValue = document.getElementById('inicio_realizacion').value;
            const terminoRealizacionValue = document.getElementById('termino_realizacion').value;

            if (!terminoOfertaValue || !inicioRealizacionValue || !terminoRealizacionValue) {
                alert("Por favor, complete todas las fechas requeridas.");
                return false;
            }

            const terminoOferta = new Date(terminoOfertaValue);
            const inicioRealizacion = new Date(inicioRealizacionValue);
            const terminoRealizacion = new Date(terminoRealizacionValue);

            if (terminoOferta >= inicioRealizacion) {
                alert("La fecha de término de oferta debe ser anterior a la fecha de inicio de realización.");
                return false;
            }

            if (terminoRealizacion <= terminoOferta || terminoRealizacion <= inicioRealizacion) {
                alert(
                    "La fecha de término de realización debe ser posterior a las fechas de término de oferta e inicio de realización."
                );
                return false;
            }

            return true;
        }
    </script>
</x-app-diplomados-layout>
