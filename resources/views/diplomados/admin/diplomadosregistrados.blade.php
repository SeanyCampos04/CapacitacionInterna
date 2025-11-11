<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Diplomados Registrados') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .card-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin: 15px;
            padding: 25px;
        }
        .table-container {
            background: white;
            overflow: hidden;
            margin: -10px;
            border-radius: 8px;
        }
        .table thead th {
            background-color: #e3f2fd;
            color: #333;
            font-weight: 600;
            border: none;
            padding: 15px 12px;
            font-size: 14px;
        }
        .table tbody td {
            padding: 15px 12px;
            vertical-align: middle;
            border-color: #e9ecef;
            font-size: 14px;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .btn-action {
            width: 50px;
            height: 40px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            margin: 0 3px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-view { background-color: #2196F3; color: white; }
        .btn-edit { background-color: #FF9800; color: white; }
        .btn-delete { background-color: #F44336; color: white; }
        .btn-requests { background-color: #4CAF50; color: white; }
        .btn-inscribed { background-color: #673AB7; color: white; }
        .btn-action:hover {
            transform: translateY(-2px);
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .btn-view:hover { background-color: #1976D2; color: white; }
        .btn-edit:hover { background-color: #F57C00; color: white; }
        .btn-delete:hover { background-color: #D32F2F; color: white; }
        .btn-requests:hover { background-color: #388E3C; color: white; }
        .btn-inscribed:hover { background-color: #512DA8; color: white; }
    </style>

    <div class="container-fluid px-4 py-4">
        <div class="card-container">
            <!-- Buscador de diplomados -->
            <form id="buscadorDiplomados" method="GET" action="{{ route('diplomados.diplomados.index') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" class="form-control" placeholder="Buscar por nombre del diplomado">
                    </div>

                    <div class="col-md-2">
                        <select name="tipo" id="tipo" class="form-select">
                            <option value="">--Todos los tipos--</option>
                            <option value="Interno" {{ request('tipo') == 'Interno' ? 'selected' : '' }}>Interno</option>
                            <option value="Externo" {{ request('tipo') == 'Externo' ? 'selected' : '' }}>Externo</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="sede" id="sede" value="{{ request('sede') }}" class="form-control" placeholder="Buscar por sede">
                    </div>

                    <div class="col-md-2">
                        <label for="inicio_oferta" class="form-label fw-semibold">Desde (Oferta)</label>
                        <input type="date" name="inicio_oferta" id="inicio_oferta" value="{{ request('inicio_oferta') }}" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label for="termino_realizacion" class="form-label fw-semibold">Hasta (Realización)</label>
                        <input type="date" name="termino_realizacion" id="termino_realizacion" value="{{ request('termino_realizacion') }}" class="form-control">
                    </div>

                    <div class="col-md-12 mt-3 text-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-700 text-white rounded-md shadow hover:bg-indigo-800">
                            Buscar
                        </button>
                    </div>
                </div>
            </form>

            <!-- Resultados -->
            @php
                $totalResultados = $diplomados ? $diplomados->count() : 0;
            @endphp
            <p class="text-sm text-gray-500 mt-2">
                Resultados: <strong>{{ $totalResultados }}</strong> diplomados
            </p>

            <!-- Contenido de la tabla -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Sede</th>
                                <th>Inicio<br>Oferta</th>
                                <th>Término<br>Oferta</th>
                                <th>Inicio<br>Realización</th>
                                <th>Término<br>Realización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($diplomados as $diplomado)
                                <tr>
                                    <td><strong>{{ $diplomado->nombre }}</strong></td>
                                    <td>{{ $diplomado->tipo }}</td>
                                    <td>{{ $diplomado->sede }}</td>
                                    <td>{{ \Carbon\Carbon::parse($diplomado->inicio_oferta)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($diplomado->termino_oferta)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <!-- Botón Ver -->
                                            <a href="{{ route('diplomados.detalle', $diplomado->id) }}"
                                               class="btn-action btn-view"
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                                <!-- Botón Editar -->
                                                <a href="{{ route('diplomados.diplomados.edit', $diplomado->id) }}"
                                                   class="btn-action btn-edit"
                                                   title="Editar">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>

                                                <!-- Botón Eliminar -->
                                                <form action="{{ route('diplomados.diplomados.destroy', $diplomado->id) }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar este diplomado?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn-action btn-delete"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                                <!-- Botón Solicitudes -->
                                                <a href="{{ route('diplomados.solicitudes_diplomado.index', $diplomado->id) }}"
                                                   class="btn-action btn-requests"
                                                   title="Ver solicitudes">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>

                                                <!-- Botón Docentes Inscritos -->
                                                <a href="{{ route('diplomados.docentes_inscritos', $diplomado->id) }}"
                                                   class="btn-action btn-inscribed"
                                                   title="Docentes inscritos">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Búsqueda automática -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formulario = document.getElementById('buscadorDiplomados');

            const campos = [
                document.getElementById('nombre'),
                document.getElementById('tipo'),
                document.getElementById('sede'),
                document.getElementById('inicio_oferta'),
                document.getElementById('termino_realizacion')
            ];

            campos.forEach(campo => {
                if (!campo) return;

                if (campo.tagName === 'INPUT' && campo.type === 'text') {
                    campo.addEventListener('input', function () {
                        clearTimeout(campo.dataset.timer);
                        campo.dataset.timer = setTimeout(() => {
                            formulario.submit();
                        }, 300);
                    });
                } else {
                    campo.addEventListener('change', function () {
                        formulario.submit();
                    });
                }
            });
        });
    </script>

</x-app-diplomados-layout>
