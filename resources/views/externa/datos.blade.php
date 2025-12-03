<x-app-externa-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Capacitaciones Externas Registradas') }}
        </h2>
    </x-slot>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Capacitaciones Externas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body {
                background-color: #f8f9fa;
            }
            .container {
                margin-top: 50px;
                max-width: 1200px;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }
            h2 {
                text-align: left;
                margin-bottom: 10px;
                font-size: 24px;
            }
            .table-responsive {
                margin-top: 20px;
                overflow-x: auto;
            }
            table {
                width: 100%;
            }

            th, td {
                text-align: center;
                vertical-align: middle;
                white-space: nowrap;
            }
            
            /* Estilos específicos para la columna de nombre de capacitación */
            .capacitacion-nombre {
                max-width: 250px;
                min-width: 200px;
                white-space: nowrap;
                overflow: hidden;
                position: relative;
            }
            
            .capacitacion-nombre .texto-scroll {
                display: inline-block;
                animation: scroll-text 10s linear infinite;
                padding-left: 100%;
            }
            
            .capacitacion-nombre:hover .texto-scroll {
                animation-play-state: paused;
            }
            
            @keyframes scroll-text {
                0% {
                    transform: translate3d(100%, 0, 0);
                }
                100% {
                    transform: translate3d(-100%, 0, 0);
                }
            }
            
            /* Alternativa con scrollbar */
            .capacitacion-scroll {
                max-width: 250px;
                min-width: 200px;
                overflow-x: auto;
                overflow-y: hidden;
                white-space: nowrap;
            }
            
            .capacitacion-scroll::-webkit-scrollbar {
                height: 4px;
            }
            
            .capacitacion-scroll::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 2px;
            }
            
            .capacitacion-scroll::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 2px;
            }
            
            .capacitacion-scroll::-webkit-scrollbar-thumb:hover {
                background: #a1a1a1;
            }
            .delete-btn {
                margin-left: 10px;
            }
            a {
                text-decoration: none !important;
            }
            .label-left {
                text-align: left;
                display: block; /* Asegura que la etiqueta tome toda la línea */
            }
            .input-group-text {
                background-color: #e9ecef;
                border: 1px solid #ced4da;
                color: #495057;
                font-weight: 500;
                white-space: nowrap;
            }
            .input-group .form-control {
                border-left: 0;
            }
            .input-group .form-control:focus {
                box-shadow: none;
                border-color: #ced4da;
            }
            .form-control.is-invalid {
                border-color: #dc3545;
            }
            .text-danger {
                color: #dc3545 !important;
                font-size: 0.875em;
                margin-top: 0.25rem;
            }

        </style>
    </head>
    <body>
        <div class="container">
                  @if(
    in_array('admin', $user_roles) ||
    in_array('CAD', $user_roles) ||
    in_array('Jefe Departamento', $user_roles) ||
    in_array('Subdirector Academico', $user_roles) ||
    in_array('Docente', $user_roles) ||
    empty($user_roles) ||
    (isset($is_mis_capacitaciones) && $is_mis_capacitaciones)
)

        <!-- Mostrar buscador para roles específicos O si es Mis Capacitaciones -->
                        <!-- Buscador y filtros -->
                        <div class="mb-6">
                           @if(isset($is_mis_capacitaciones) && $is_mis_capacitaciones)
    <form id="searchForm" action="{{ route('externa.mis_capacitaciones') }}" method="GET" class="flex flex-wrap items-center gap-3">
@else
    <form id="searchForm" action="{{ route('capacitacionesext.filtrar') }}" method="GET" class="flex flex-wrap items-center gap-3">
@endif
                                <!-- Campo de búsqueda -->
                                <input
                                    type="text"
                                    name="q"
                                    id="searchInput"
                                    placeholder="Buscar por nombre, nombre de la capacitación u organismo"
                                    value="{{ old('q', $search ?? request('q')) }}"
                                    class="flex-1 rounded-md border-gray-300 shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                >

                                <!-- Filtro por tipo de capacitación -->
                                <select name="tipo_capacitacion" id="tipoSelect"
                                    class="rounded-md border-gray-300 shadow-sm px- py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    <option value="">--Todos los tipos de capacitación--</option>
                                    <option value="diplomado" {{ request('tipo_capacitacion') == 'diplomado' ? 'selected' : '' }}>Diplomado</option>
                                    <option value="taller_curso" {{ request('tipo_capacitacion') == 'taller_curso' ? 'selected' : '' }}>Taller o curso</option>
                                    <option value="mooc" {{ request('tipo_capacitacion') == 'mooc' ? 'selected' : '' }}>Mooc (TecNM)</option>
                                    <option value="otro" {{ request('tipo_capacitacion') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>

                                <!-- Filtro por año -->
                                <select name="anio" id="anioSelect"
                                    class="rounded-md border-gray-300 shadow-sm px- py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    <option value="">--Todos los años--</option>
                                    @for($i = 2022; $i <= 2028; $i++)
                                        <option value="{{ $i }}" {{ request('anio') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>

                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-700 text-white rounded-md shadow hover:bg-indigo-800">
                                    Buscar
                                </button>
                            </form>

                            <!-- Resultados -->
                            @php
                                $totalResultados = method_exists($capacitaciones ?? null, 'total') ? $capacitaciones->total() : ($capacitaciones ? $capacitaciones->count() : 0);
                            @endphp
                            <p class="text-sm text-gray-500 mt-2">
                                Resultados: <strong>{{ $totalResultados }}</strong> capacitaciones
                            </p>
                        </div>
                    @endif


            <div class="table-responsive">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xm text-gray-700 bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">Nombre Completo</th>
                            <th scope="col" class="px-6 py-3 text-center">Tipo de Capacitación</th>
                            <th scope="col" class="px-6 py-3 text-center capacitacion-scroll">Nombre de la Capacitación</th>
                            <th scope="col" class="px-6 py-3 text-center">Fecha Inicio</th>
                            <th scope="col" class="px-6 py-3 text-center">Fecha Termino</th>
                            <th scope="col" class="px-6 py-3 text-center">Año</th>
                            <th scope="col" class="px-6 py-3 text-center">Organismo</th>
                            <th scope="col" class="px-6 py-3 text-center">Horas</th>
                            <th scope="col" class="px-6 py-3 text-center">Evidencia</th>
                            <th scope="col" class="px-6 py-3 text-center">Comentarios</th> <!-- Nueva columna para el estado -->
                            <th scope="col" class="px-6 py-3 text-center">Folio</th>
                            <th scope="col" class="px-6 py-3 text-center">Eliminar</th>
                            @if($tipo_usuario == 1) <!-- Verifica si no es docente para mostrar las acciones -->
                                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <th scope="col" class="px-6 py-3 text-center">Constancias</th>
                                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                                @endif
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($capacitaciones as $capacitacion)
                            <tr class="bg-white border-b">
                                <td class="text-center">{{ $capacitacion->nombre }} {{ $capacitacion->apellido_paterno }} {{ $capacitacion->apellido_materno }}</td>
                                <td class="text-center">{{ $capacitacion->tipo_capacitacion }}</td>
                                <td class="text-center capacitacion-scroll" title="{{ $capacitacion->nombre_capacitacion }}">
                                    {{ $capacitacion->nombre_capacitacion }}
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($capacitacion->fecha_inicio)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($capacitacion->fecha_termino)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $capacitacion->anio }}</td>
                                <td class="text-center">{{ $capacitacion->organismo }}</td>
                                <td class="text-center">{{ $capacitacion->horas }}</td>
                                <td class="text-center">
    @php
        $rutaEvidencia = 'storage/evidencias/' . basename($capacitacion->evidencia);
    @endphp

    @if ($capacitacion->evidencia && file_exists(public_path($rutaEvidencia)))
        <a href="{{ url($rutaEvidencia) }}"
           target="_blank"
           class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition">
            Ver PDF
        </a>
    @else
        <span class="text-gray-400">No disponible</span>
    @endif
</td>


                                <td class="text-center">
                                    <!-- Mostrar el estado de la capacitación -->
                                    @if($capacitacion->status)
                                        {{ $capacitacion->status }}
                                    @else
                                        Ninguno
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Mostrar el folio de la capacitación -->
                                    @if($capacitacion->folio)
                                        @if($capacitacion->folio == 'Rechazado')
                                            {{ $capacitacion->folio }}
                                        @else
                                            @if(str_starts_with($capacitacion->folio, 'TNM-169-'))
                                                {{ $capacitacion->folio }}
                                            @else
                                                TNM-169-{{ $capacitacion->folio }}
                                            @endif
                                        @endif
                                    @else
                                        No asignado
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('capacitacionesext.destroy', $capacitacion->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        @if ($capacitacion->folio)
                                            <x-primary-button
                                                class="bg-red-600 cursor-not-allowed opacity-50"
                                                disabled>
                                                🗑
                                            </x-primary-button>
                                        @else
                                            <x-primary-button
                                                class="bg-red-600 hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-0"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta capacitación?');">
                                                🗑
                                            </x-primary-button>
                                        @endif
                                    </form>
                                </td>

                                @if($tipo_usuario == 1) <!-- Verifica si no es docente para mostrar las acciones -->
                                    @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                        <td class="text-center">
                                            <a href="{{ route('capacitacionesext.constancia', $capacitacion->id) }}" target="_blank">
                                                <x-primary-button class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0">
                                                    📄
                                                </x-primary-button>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <x-primary-button
                                                class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-{{ $capacitacion->id }}">
                                                Detalles
                                            </x-primary-button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modal-{{ $capacitacion->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $capacitacion->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel-{{ $capacitacion->id }}">Detalles</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('capacitacionesext.actualizarDatos', $capacitacion->id) }}" method="POST">
                                                                @csrf
                                                                <!-- Input para comentario -->
                                                                <div class="mb-3">
                                                                    <label for="comentario_{{ $capacitacion->id }}" class="form-label label-left">Comentario</label>
                                                                    <input type="text" name="comentario" id="comentario_{{ $capacitacion->id }}" class="form-control" placeholder="Escribe un comentario...">
                                                                </div>
                                                                <!-- Input para folio -->
                                                                <div class="mb-3">
                                                                    <label for="numero_folio_{{ $capacitacion->id }}" class="form-label label-left">Número de Registro</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">TNM-169-</span>
                                                                        <input type="text" name="numero_folio" id="numero_folio_{{ $capacitacion->id }}" class="form-control" placeholder="Ej: 09-2025/004" value="{{ $capacitacion->folio ? str_replace('TNM-169-', '', $capacitacion->folio) : '' }}" onkeyup="validarNumeroRegistro(this)">
                                                                    </div>
                                                                    <small class="form-text text-muted">Formato: XX-YYYY/XXX (con ceros, ej: 09-2025/004). No se permite formato de instructor (/I-)</small>
                                                                    <div id="error_{{ $capacitacion->id }}" class="text-danger" style="display: none;">No se permite el formato de instructor (/I-) en capacitaciones externas</div>
                                                                </div>
                                                                <!-- Botón de guardar -->
                                                                <div class="d-flex justify-content-end">
                                                                    <x-primary-button type="submit" class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0">
                                                                        Guardar
                                                                    </x-primary-button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Modal de éxito -->
        <div class="modal" id="successModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Éxito</h4>
                    </div>
                    <div class="modal-body">
                        {{ session('success') ?? 'Modal body..' }}
                    </div>
                    <div class="modal-footer">
                        <x-primary-button type="button" class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0" data-bs-dismiss="modal">
                            Cerrar
                        </x-primary-button>

                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Función para validar el número de registro
            function validarNumeroRegistro(input) {
                const valor = input.value.toUpperCase();
                const errorDiv = document.getElementById('error_' + input.id.split('_')[2]);
                const submitBtn = input.closest('form').querySelector('button[type="submit"]');

                if (valor.includes('/I-')) {
                    errorDiv.style.display = 'block';
                    input.classList.add('is-invalid');
                    submitBtn.disabled = true;
                } else {
                    errorDiv.style.display = 'none';
                    input.classList.remove('is-invalid');
                    submitBtn.disabled = false;
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                if ("{{ session('success') }}") {
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                }

                // Búsqueda automática con debounce
                const input = document.getElementById('searchInput');
                const tipoSelect = document.getElementById('tipoSelect');
                const anioSelect = document.getElementById('anioSelect');
                const form = document.getElementById('searchForm');

                if (input && form) {
                    let timeout = null;
                    input.addEventListener('input', function () {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => form.submit(), 800);
                    });

                    // Enviar el formulario automáticamente al cambiar los selectores
                    if (tipoSelect) {
                        tipoSelect.addEventListener('change', function () {
                            form.submit();
                        });
                    }

                    if (anioSelect) {
                        anioSelect.addEventListener('change', function () {
                            form.submit();
                        });
                    }
                }
            });
        </script>
    </body>
    </html>
</x-app-externa-layout>
