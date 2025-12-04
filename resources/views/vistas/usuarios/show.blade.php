<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Detalles del usuario:') }} {{ $usuario->datos_generales->nombre }}
            {{ $usuario->datos_generales->apellido_paterno }} {{ $usuario->datos_generales->apellido_materno }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .user-info-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 600px;
        }

        .user-info-card h5 {
            color: #1f2937;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            min-width: 120px;
        }

        .info-value {
            color: #6b7280;
            text-align: right;
            flex: 1;
        }

        .btn-action {
            width: 100px;
            height: 40px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            margin: 0 5px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-edit {
            background: #3b82f6;
            color: white;
        }

        .btn-edit:hover {
            background: #2563eb;
            color: white;
        }

        .btn-deactivate {
            background: #ef4444;
            color: white;
        }

        .btn-deactivate:hover {
            background: #dc2626;
            color: white;
        }

        .btn-activate {
            background: #10b981;
            color: white;
        }

        .btn-activate:hover {
            background: #059669;
            color: white;
        }

        .actions-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e5e7eb;
        }

        .optimized-container {
            width: 98%;
            max-width: 1400px !important;
            margin: 1rem auto !important;
            padding: 1.25rem;
        }

        .compact-table {
            font-size: 0.95rem;
        }

        .compact-table th,
        .compact-table td {
            padding: 0.5rem 0.75rem !important;
            vertical-align: middle;
            max-width: 200px;
            overflow-x: auto;
            white-space: nowrap;
        }

        .compact-table th {
            background-color: #e3f2fd !important;
            color: #333 !important;
            font-weight: 600 !important;
            border: none !important;
            padding: 15px 12px !important;
            font-size: 14px !important;
        }

        .section-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin: 3rem 0 2rem 0;
        }
    </style>

    <div class="container mt-6 mx-auto">
        <div class="user-info-card">
            <h5>{{ $usuario->datos_generales->nombre }}
                {{ $usuario->datos_generales->apellido_paterno }}
                {{ $usuario->datos_generales->apellido_materno }}</h5>

            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $usuario->email }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Roles:</span>
                <span class="info-value">
                    @foreach ($usuario->user_roles as $userRole)
                        {{ $userRole->nombre }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Tipo:</span>
                <span class="info-value">
                    @if ($usuario->tipo == 1)
                        Docente
                    @elseif ($usuario->tipo == 2)
                        Administrativo
                    @elseif ($usuario->tipo == 3)
                        Otro
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Estatus:</span>
                <span class="info-value">
                    @if ($usuario->estatus == 1)
                        <span class="text-green-600 font-semibold">Activo</span>
                    @else
                        <span class="text-red-600 font-semibold">Inactivo</span>
                    @endif
                </span>
            </div>

            @if ($usuario->datos_generales->departamento->nombre)
            <div class="info-item">
                <span class="info-label">Departamento:</span>
                <span class="info-value">{{ $usuario->datos_generales->departamento->nombre }}</span>
            </div>
            @endif

            @if ($usuario->datos_generales->fecha_nacimiento)
            <div class="info-item">
                <span class="info-label">Fecha nacimiento:</span>
                <span class="info-value">{{ $usuario->datos_generales->fecha_nacimiento }}</span>
            </div>
            @endif

            @if ($usuario->datos_generales->curp)
            <div class="info-item">
                <span class="info-label">CURP:</span>
                <span class="info-value">{{ $usuario->datos_generales->curp }}</span>
            </div>
            @endif

            @if ($usuario->datos_generales->rfc)
            <div class="info-item">
                <span class="info-label">RFC:</span>
                <span class="info-value">{{ $usuario->datos_generales->rfc }}</span>
            </div>
            @endif

            @if ($usuario->datos_generales->telefono)
            <div class="info-item">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $usuario->datos_generales->telefono }}</span>
            </div>
            @endif

            @if ($usuario->datos_generales->sexo)
            <div class="info-item">
                <span class="info-label">Sexo:</span>
                <span class="info-value">{{ $usuario->datos_generales->sexo }}</span>
            </div>
            @endif

            @if ($usuario->instructor && $usuario->instructor->cvu)
            <div class="info-item">
                <span class="info-label">CVU:</span>
                <span class="info-value">
                    <a href="{{ asset('uploads/' . $usuario->instructor->cvu) }}" target="_blank"
                        class="text-blue-600 hover:text-blue-800 underline">
                        Ver CVU
                    </a>
                </span>
            </div>
            @endif

            @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
            <div class="actions-container">
                <form action="{{ route('usuario.edit', $usuario->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('GET')
                    <button type="submit" class="btn-action btn-edit">Editar</button>
                </form>

                @if ($usuario->estatus == 1)
                    <form action="{{ route('usuario.desactivar', $usuario->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-action btn-deactivate"
                                onclick="return confirm('¿Estás seguro de que quieres poner como inactivo a este usuario?');">
                            Desactivar
                        </button>
                    </form>
                @else
                    <form action="{{ route('usuario.activar', $usuario->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-action btn-activate"
                                onclick="return confirm('¿Estás seguro de que quieres poner como activo a este usuario?');">
                            Activar
                        </button>
                    </form>
                @endif
            </div>
            @endif
        </div>
    </div>

    <h1 class="section-title">Historial de cursos</h1>

    <div class="optimized-container bg-white shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-hover compact-table">
                <thead>
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Fecha de inicio</th>
                        <th class="text-center">Fecha final</th>
                        <th class="text-center">Clase</th>
                        <th class="text-center">Competencias Digitales</th>
                        <th class="text-center">Formación Tutorial</th>
                        <th class="text-center">Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cursos as $curso)
                        <tr class="align-middle">
                            <td class="text-center">{{ $curso->curso->nombre }}</td>
                            <td class="text-center">{{ $curso->curso->fdi }}</td>
                            <td class="text-center">{{ $curso->curso->fdf }}</td>
                            <td class="text-center">{{ $curso->curso->clase }}</td>
                            <td class="text-center">
                                @if ($curso->curso->es_tics)
                                    <span class="text-green-600 font-semibold">Sí</span>
                                @else
                                    <span class="text-gray-500">No</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($curso->curso->es_tutorias)
                                    <span class="text-green-600 font-semibold">Sí</span>
                                @else
                                    <span class="text-gray-500">No</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @foreach ($curso->curso->cursos_instructores as $instructor)
                                    {{ $instructor->instructor->user->datos_generales->nombre }}
                                    {{ $instructor->instructor->user->datos_generales->apellido_paterno }}
                                    {{ $instructor->instructor->user->datos_generales->apellido_materno }}<br>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-4">
                                No tiene ningún historial de cursos registrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
