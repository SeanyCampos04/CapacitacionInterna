<x-app-externa-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Capacitaciones Externas Registradas') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome - Updated -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .optimized-container {
            width: 98%;
            max-width: 1400px !important;
            margin: 1rem auto !important;
            padding: 1.25rem;
        }

        /* Estilo del header igual al de Diplomados */
        .table thead th,
        .custom-header th {
            background-color: #e3f2fd !important;
            color: #333 !important;
            font-weight: 600 !important;
            border: none !important;
            padding: 15px 12px !important;
            font-size: 14px !important;
        }

        .custom-header {
            background-color: #e3f2fd !important;
        }

        .compact-table {
            font-size: 0.95rem;
        }

        .compact-table th,
        .compact-table td {
            padding: 0.5rem 0.75rem !important;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Badges más grandes y visibles */
        .compact-table .badge {
            font-size: 0.85rem !important;
            padding: 0.4em 0.8em !important;
            font-weight: 600 !important;
        }

        .compact-table th {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        /* Botones de acción */
        .action-btn {
            width: 80px;
            height: 35px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
            transition: all 0.2s ease;
            text-decoration: none !important;
            font-size: 1rem;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .btn-view {
            background: #3b82f6;
            color: white;
        }

        .btn-view:hover {
            background: #2563eb;
            color: white;
        }

        .btn-edit {
            background: #f59e0b;
            color: white;
        }

        .btn-edit:hover {
            background: #d97706;
            color: white;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: white;
        }

        .btn-certificate {
            background: #10b981;
            color: white;
        }

        .btn-certificate:hover {
            background: #059669;
            color: white;
        }

        .btn-participants {
            background: #8b5cf6;
            color: white;
        }

        .btn-participants:hover {
            background: #7c3aed;
            color: white;
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="optimized-container bg-white shadow-lg rounded-lg">

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
                <table class="table table-hover compact-table">
                    <thead class="custom-header">
                        <tr>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center" style="min-width: 200px;">Capacitación</th>
                            <th class="text-center">Inicio</th>
                            <th class="text-center">Término</th>
                            <th class="text-center">Año</th>
                            <th class="text-center">Organismo</th>
                            <th class="text-center">Horas</th>
                            <th class="text-center">Evidencia</th>
                            <th class="text-center">Comentarios</th>
                            <th class="text-center">Folio</th>
                            <th class="text-center">Acciones</th>
                            @if($tipo_usuario == 1) <!-- Verifica si no es docente para mostrar las acciones -->
                                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <th class="text-center">Constancias</th>
                                    <th class="text-center">Detalles</th>
                                @endif
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($capacitaciones as $capacitacion)
                            <tr class="align-middle">
                                <td class="text-center">{{ $capacitacion->nombre }} {{ $capacitacion->apellido_paterno }} {{ $capacitacion->apellido_materno }}</td>
                                <td class="text-center">
                                    @if($capacitacion->tipo_capacitacion == 'diplomado')
                                        <span class="badge" style="background-color: #00bcd4; color: white;">{{ $capacitacion->tipo_capacitacion }}</span>
                                    @elseif($capacitacion->tipo_capacitacion == 'mooc')
                                        <span class="badge" style="background-color: #00bcd4; color: white;">{{ $capacitacion->tipo_capacitacion }}</span>
                                    @elseif($capacitacion->tipo_capacitacion == 'taller_curso')
                                        <span class="badge" style="background-color: #00bcd4; color: white;">taller_curso</span>
                                    @else
                                        <span class="badge" style="background-color: #00bcd4; color: white;">{{ $capacitacion->tipo_capacitacion }}</span>
                                    @endif
                                </td>
                                <td class="text-start" title="{{ $capacitacion->nombre_capacitacion }}" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $capacitacion->nombre_capacitacion }}
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($capacitacion->fecha_inicio)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($capacitacion->fecha_termino)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $capacitacion->anio }}</td>
                                <td class="text-center">{{ $capacitacion->organismo }}</td>
                                <td class="text-center"><span class="badge bg-secondary">{{ $capacitacion->horas }}h</span></td>
                                <td class="text-center">
    @php
        $rutaEvidencia = 'storage/evidencias/' . basename($capacitacion->evidencia);
    @endphp

    @if ($capacitacion->evidencia && file_exists(public_path($rutaEvidencia)))
        <a href="{{ url($rutaEvidencia) }}"
           target="_blank"
           class="action-btn btn-view"
           title="Ver evidencia">
            <i class="fas fa-file-pdf"></i>
        </a>
    @else
        <span class="text-muted small">-</span>
    @endif
</td>


                                <td class="text-center">
                                    <!-- Mostrar el estado de la capacitación -->
                                    @if($capacitacion->status)
                                        <span class="badge bg-success small">{{ $capacitacion->status }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Mostrar el folio de la capacitación -->
                                    @if($capacitacion->folio)
                                        @if($capacitacion->folio == 'Rechazado')
                                            <span class="badge bg-danger small">{{ $capacitacion->folio }}</span>
                                        @else
                                            <span class="badge bg-primary small">
                                                @if(str_starts_with($capacitacion->folio, 'TNM-169-'))
                                                    {{ $capacitacion->folio }}
                                                @else
                                                    TNM-169-{{ $capacitacion->folio }}
                                                @endif
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('capacitacionesext.destroy', $capacitacion->id) }}" method="POST" class="delete-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        @if ($capacitacion->folio)
                                            <button type="button"
                                                class="action-btn btn-delete opacity-50"
                                                disabled
                                                title="No se puede eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <button type="submit"
                                                class="action-btn btn-delete"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta capacitación?')"
                                                title="Eliminar capacitación">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </form>
                                </td>

                                @if($tipo_usuario == 1) <!-- Verifica si no es docente para mostrar las acciones -->
                                    @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                        <td class="text-center">
                                            <a href="{{ route('capacitacionesext.constancia', $capacitacion->id) }}"
                                               target="_blank"
                                               class="action-btn btn-certificate"
                                               title="Generar constancia">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="action-btn btn-view"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-{{ $capacitacion->id }}"
                                                title="Ver detalles">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
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
