<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Estadísticas por año') }}
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
    </style>

    <div class="optimized-container bg-white shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-hover compact-table">
                <thead>
                    <tr>
                        <th class="text-center">Año</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periodos as $anio => $groupedPeriodos)
                        <tr class="align-middle">
                            <td class="text-center">{{ $anio }}</td>
                            <td class="text-center">
                                <a href="{{ route('cursos_estadisticas.show', $anio) }}"
                                   class="btn-action btn-view"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
