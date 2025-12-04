<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Departamentos') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
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
        }

        .compact-table th {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        /* Estilo del header igual al de Diplomados */
        .table thead th {
            background-color: #e3f2fd !important;
            color: #333 !important;
            font-weight: 600 !important;
            border: none !important;
            padding: 15px 12px !important;
            font-size: 14px !important;
        }

        .btn-action {
            width: 50px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            margin: 0 3px;
            text-decoration: none;
            font-size: 1rem;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .btn-view { background: #3b82f6; color: white; }
        .btn-view:hover { background: #2563eb; color: white; }

        .btn-edit { background: #10b981; color: white; }
        .btn-edit:hover { background: #059669; color: white; }

        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; color: white; }
    </style>
    <div class="optimized-container bg-white shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-hover compact-table">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Jefe departamento</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departamentos as $departamento)
                    <tr class="align-middle">
                        <td class="text-center">{{ $departamento->id }}</td>
                        <td class="text-center">{{ $departamento->nombre }}</td>
                        @if ($departamento->user)
                        <td class="text-center">
                            {{ $departamento->user->datos_generales->nombre }}
                            {{ $departamento->user->datos_generales->apellido_paterno }}
                            {{ $departamento->user->datos_generales->apellido_materno }}
                        </td>
                        @else
                        <td class="text-center"><span class="text-muted">Sin asignar</span></td>
                        @endif

                        <td class="text-center">
                            <a href="{{route('departamentos.show', $departamento->id)}}"
                               class="btn-action btn-view"
                               title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                            <a href="{{route('departamentos.edit', $departamento->id)}}"
                               class="btn-action btn-edit"
                               title="Editar departamento">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            <form action="{{ route('departamentos.destroy', $departamento->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-action btn-delete"
                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este departamento?')"
                                        title="Eliminar departamento">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                            @empty
                            <tr class="bg-white border-b">
                                <td colspan="4" class="text-center py-4 text-gray-500">
                                    No hay departamentos disponibles.
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
