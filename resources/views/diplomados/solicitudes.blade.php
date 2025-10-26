<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitudes') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">

        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Nombre</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Tipo</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Sede</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Inicio Oferta</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Término Oferta</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Inicio Realización</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Término Realización</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Estatus</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Como</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudesParticipante as $solicitudParticipante)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudParticipante->diplomado->nombre }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudParticipante->diplomado->tipo }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudParticipante->diplomado->sede }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudParticipante->diplomado->inicio_oferta)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudParticipante->diplomado->termino_oferta)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudParticipante->diplomado->inicio_realizacion)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudParticipante->diplomado->termino_realizacion)->format('d/m/Y') }}</td>
                    @if ($solicitudParticipante->estatus == 0)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">En espera</td>
                    @endif
                    @if ($solicitudParticipante->estatus == 1)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Negado</td>
                    @endif
                    @if ($solicitudParticipante->estatus == 2)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Aceptado</td>
                    @endif
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Participante</td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="detailsModal-{{ $solicitudParticipante->diplomado->id }}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel-{{ $solicitudParticipante->diplomado->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailsModalLabel-{{ $solicitudParticipante->diplomado->id }}">Detalles del Diplomado</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>ID:</strong> {{ $solicitudParticipante->diplomado->id }}</p>
                                <p><strong>Objetivo:</strong> {{ $solicitudParticipante->diplomado->objetivo }}</p>
                                <p><strong>Categoría:</strong> {{ $solicitudParticipante->diplomado->clase }}</p>
                                <p><strong>Responsable:</strong> {{ $solicitudParticipante->diplomado->responsable }}</p>
                                <p><strong>Correo de Contacto:</strong> {{ $solicitudParticipante->diplomado->correo_contacto }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @foreach ($solicitudesInstructor as $solicitudInstructor)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudInstructor->diplomado->nombre }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudInstructor->diplomado->tipo }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $solicitudInstructor->diplomado->sede }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudInstructor->diplomado->inicio_oferta)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudInstructor->diplomado->termino_oferta)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudInstructor->diplomado->inicio_realizacion)->format('d/m/Y') }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($solicitudInstructor->diplomado->termino_realizacion)->format('d/m/Y') }}</td>
                    @if ($solicitudInstructor->estatus == 0)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">En espera</td>
                    @endif
                    @if ($solicitudInstructor->estatus == 1)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Negado</td>
                    @endif
                    @if ($solicitudInstructor->estatus == 2)
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Aceptado</td>
                    @endif
                    <td class="py-2 px-4 border-b border-gray-200 text-sm">Instructor</td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="detailsModal-{{ $solicitudInstructor->diplomado->id }}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel-{{ $solicitudInstructor->diplomado->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailsModalLabel-{{ $solicitudInstructor->diplomado->id }}">Detalles del Diplomado</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>ID:</strong> {{ $solicitudInstructor->diplomado->id }}</p>
                                <p><strong>Objetivo:</strong> {{ $solicitudInstructor->diplomado->objetivo }}</p>
                                <p><strong>Categoría:</strong> {{ $solicitudInstructor->diplomado->clase }}</p>
                                <p><strong>Responsable:</strong> {{ $solicitudInstructor->diplomado->responsable }}</p>
                                <p><strong>Correo de Contacto:</strong> {{ $solicitudInstructor->diplomado->correo_contacto }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Scripts necesarios para Bootstrap Modal -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</x-app-diplomados-layout>
