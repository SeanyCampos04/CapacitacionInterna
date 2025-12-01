<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Diplomados a inscribirse') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn {
            border-radius: 6px;
            font-weight: 500;
        }
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            border-radius: 10px 10px 0 0;
        }
        .input-group .form-control {
            border-radius: 6px;
        }
        .d-flex.gap-2 {
            gap: 0.5rem !important;
        }
    </style>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">

        @if($diplomados->count() > 0)
            @foreach($diplomados as $diplomado)
        <div class="diplomado flex justify-between items-center border-b border-gray-300 py-4 mb-4">
            <div class="descripcion flex-1 ml-4">
                <h4 class="text-lg font-semibold text-gray-800">{{ $diplomado->nombre }}</h4>
                <label class="text-sm text-gray-600">Tipo</label>
                <p class="tipo text-md text-gray-700">{{ $diplomado->tipo }}</p>

                <label class="text-sm text-gray-600">Categoria</label>
                <p class="clase text-md text-gray-700">{{ $diplomado->clase }}</p>

                <label class="text-sm text-gray-600">Fecha de inicio del diplomado</label>
                <p class="iniciorealizacion text-md text-gray-700">{{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('d/m/Y') }}</p>

                <label class="text-sm text-gray-600">Fecha de terminación del diplomado</label>
                <p class="terminorealizacion text-md text-gray-700">{{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->format('d/m/Y') }}</p>

                <button type="button" class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#modalDescripcion{{ $diplomado->id }}">
                    Ver Descripción
                </button>
            </div>
            <div class="text-center mt-4 flex flex-col gap-2">
                {{-- Verifica si el usuario tiene solicitudes para este diplomado --}}
                @php
                    $tieneSolicitudInstructor = in_array($diplomado->id, $diplomadosConSolicitudInstructor);
                    $tieneSolicitudParticipante = in_array($diplomado->id, $diplomadosConSolicitudParticipante);
                @endphp

                {{-- Botón para participantes --}}
                @if ($user->tipo == 1 && !$tieneSolicitudParticipante && !$tieneSolicitudInstructor)
                    <button type="button" class="btn btn-primary w-full" data-bs-toggle="modal" data-bs-target="#modalParticipante{{ $diplomado->id }}">
                        Inscribirse como participante
                    </button>
                @endif

                {{-- Botón para instructores --}}
                @if ($user->instructor && !$tieneSolicitudInstructor && !$tieneSolicitudParticipante)
                    <button type="button" class="btn btn-primary w-full" data-bs-toggle="modal" data-bs-target="#modalInstructor{{ $diplomado->id }}">
                        Inscribir como instructor
                    </button>
                @endif

                {{-- Mensaje si ya tiene una solicitud --}}
                @if ($tieneSolicitudInstructor || $tieneSolicitudParticipante)
                    <p class="text-red-500">Ya has solicitado este diplomado.</p>
                @endif
            </div>
        </div>

        <!-- Modal Descripción -->
        <div class="modal fade" id="modalDescripcion{{ $diplomado->id }}" tabindex="-1" aria-labelledby="modalDescripcion{{ $diplomado->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDescripcion{{ $diplomado->id }}Label">Descripción de {{ $diplomado->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">{{ $diplomado->objetivo }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Inscribirse como Participante -->
        <div class="modal fade" id="modalParticipante{{ $diplomado->id }}" tabindex="-1" aria-labelledby="modalParticipante{{ $diplomado->id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalParticipante{{ $diplomado->id }}Label">Inscripción como Participante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subir archivo PDF</label>
                            <form action="{{ route('diplomados.solicitar_docente_oferta.store', $diplomado->id) }}" method="POST" enctype="multipart/form-data" id="formParticipante{{ $diplomado->id }}">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="file" name="pdf" class="form-control" accept="application/pdf" required id="pdfParticipante{{ $diplomado->id }}">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Inscribirse como Instructor -->
        <div class="modal fade" id="modalInstructor{{ $diplomado->id }}" tabindex="-1" aria-labelledby="modalInstructor{{ $diplomado->id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInstructor{{ $diplomado->id }}Label">Inscripción como Instructor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subir archivo PDF</label>
                            <form action="{{ route('diplomados.solicitar_instructor_oferta.store', $diplomado->id) }}" method="POST" enctype="multipart/form-data" id="formInstructor{{ $diplomado->id }}">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="file" name="pdf" class="form-control" accept="application/pdf" required id="pdfInstructor{{ $diplomado->id }}">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            @endforeach
        @else
            <div class="text-center py-8">
                <div class="bg-gray-50 rounded-lg p-8 border-2 border-dashed border-gray-200">
                    <i class="fas fa-graduation-cap text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-600 mb-2">No hay diplomados disponibles</h3>
                    <p class="text-gray-500 text-lg">En este momento no hay diplomados en oferta</p>
                    <p class="text-gray-400 text-sm mt-4">Vuelve pronto para ver nuevas ofertas de diplomados</p>
                </div>
            </div>
        @endif
    </div>

</x-app-diplomados-layout>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
// Función para mostrar el nombre del archivo seleccionado
document.addEventListener('DOMContentLoaded', function() {
    // Manejar cambios en los inputs de archivo
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
                console.log(`Archivo seleccionado: ${fileName} (${fileSize} MB)`);
            }
        });
    });
});
</script>
