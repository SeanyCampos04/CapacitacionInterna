<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Periodos') }}
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

        .btn-view {
            background: #3b82f6;
            color: white;
        }

        .btn-view:hover {
            background: #2563eb;
            color: white;
        }

        .btn-edit {
            background: #10b981;
            color: white;
        }

        .btn-edit:hover {
            background: #059669;
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
    </style>

    <div class="optimized-container bg-white shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-hover compact-table">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Periodo</th>
                        <th class="text-center">Año</th>
                        <th class="text-center">Trimestre</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($periodos as $periodo)
                        <tr class="align-middle">
                            <td class="text-center">{{ $periodo->id }}</td>
                            <td class="text-center">{{ $periodo->periodo }}</td>
                            <td class="text-center">{{ $periodo->anio }}</td>
                            @if ($periodo->trimestre == 1)
                                <td class="text-center">Enero - Marzo</td>
                            @endif
                            @if ($periodo->trimestre == 2)
                                <td class="text-center">Abril - Junio</td>
                            @endif
                            @if ($periodo->trimestre == 3)
                                <td class="text-center">Julio - Septiembre</td>
                            @endif
                            @if ($periodo->trimestre == 4)
                                <td class="text-center">Octubre - Diciembre</td>
                            @endif
                            <td class="text-center">
                                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <form action="{{ route('periodos.destroy', $periodo->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn-action btn-delete"
                                                title="Eliminar"
                                                onclick="return confirm('¿Estás seguro de que quieres eliminar este periodo?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('periodos.show', $periodo->id) }}"
                                   class="btn-action btn-view"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <a href="{{ route('periodos.edit', $periodo->id) }}"
                                       class="btn-action btn-edit"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                No hay periodos registrados.
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
