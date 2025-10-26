<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calificar módulo:') }} {{ $modulo->nombre }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">
        {{ $modulo->fecha_inicio }}
        {{ $modulo->fecha_termino }}
        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Docente
                    </th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">
                        Calificación</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($docentes as $docente)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">
                            {{ $docente['docente']->nombre }}
                            {{ $docente['docente']->apellido_paterno }}
                            {{ $docente['docente']->apellido_materno }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">
                            {{ $docente['calificacion'] ?? 'Sin calificación' }}
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                @php
                                    $fecha_actual = \Carbon\Carbon::now();
                                    $fecha_inicio = \Carbon\Carbon::parse($modulo->fecha_inicio);
                                    $fecha_termino = \Carbon\Carbon::parse($modulo->fecha_termino);
                                    $fecha_limite = $fecha_termino->copy()->addDays(7); // Copiar la fecha de término y agregarle 7 días
                                @endphp

                                @if ($fecha_actual->lt($fecha_termino))
                                    <!-- Si la fecha actual es antes de la fecha de término -->
                                    <span class="text-gray-500">Aún no es el lapso de calificar</span>
                                @elseif ($fecha_actual->gte($fecha_termino) && $fecha_actual->lte($fecha_limite))
                                    <!-- Si la fecha actual está entre la fecha de término y la fecha límite (fecha de término + 7 días) -->
                                    @if($docente['calificacion'])
                                        <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-4 rounded"
                                                onclick="openUpdateModal('{{ $docente['docente']->id }}', '{{ $docente['calificacion'] }}')">
                                            Actualizar Calificación
                                        </button>
                                    @else
                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded"
                                                onclick="openModal('{{ $docente['docente']->id }}')">
                                            Calificar
                                        </button>
                                    @endif
                                @else
                                    <!-- Si la fecha actual ya pasó la fecha límite (fecha de término + 7 días) -->
                                    <span class="text-red-500">Ya pasó el lapso de calificar</span>
                                @endif
                            </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal para Calificar -->
    <div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h3 class="text-xl font-semibold mb-4">Calificar Docente</h3>
            <form id="calificarForm" method="POST"
                action="{{ route('actualizar.calificacion.modulo.participante') }}">
                @csrf
                <input type="hidden" id="participante_id" name="participante_id" value="">
                <input type="hidden" id="diplomado_id" name="diplomado_id" value="{{ $diplomado->id }}">
                <input type="hidden" id="modulo_id" name="modulo_id" value="{{ $modulo->id }}">
                <div class="mb-4">
                    <label for="calificacion" class="block text-sm font-medium text-gray-700">Calificación</label>
                    <input type="number" id="calificacion" name="calificacion" min="0" max="100"
                        step="0.1"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 rounded"
                        onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                        Calificar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Actualizar Calificación -->
    <div id="updateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h3 class="text-xl font-semibold mb-4">Actualizar Calificación</h3>
            <form id="updateForm" method="POST" action="{{ route('actualizar.calificacion.modulo.participante') }}">
                @csrf
                <input type="hidden" id="update_participante_id" name="participante_id" value="">
                <input type="hidden" id="update_diplomado_id" name="diplomado_id" value="{{ $diplomado->id }}">
                <input type="hidden" id="update_modulo_id" name="modulo_id" value="{{ $modulo->id }}">
                <div class="mb-4">
                    <label for="update_calificacion"
                        class="block text-sm font-medium text-gray-700">Calificación</label>
                    <input type="number" id="update_calificacion" name="calificacion" min="0" max="100"
                        step="0.1"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 rounded"
                        onclick="closeUpdateModal()">Cancelar</button>
                    <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-4 rounded">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal(participanteId) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('participante_id').value = participanteId;
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function openUpdateModal(participanteId, calificacion) {
            document.getElementById('updateModal').classList.remove('hidden');
            document.getElementById('update_participante_id').value = participanteId;
            document.getElementById('update_calificacion').value = calificacion;
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').classList.add('hidden');
        }
    </script>
</x-app-diplomados-layout>
