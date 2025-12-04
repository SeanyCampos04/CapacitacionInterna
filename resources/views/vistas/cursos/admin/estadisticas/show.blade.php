<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Estadísticas de Cursos por Año') }} ({{ $anio }})
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

        .compact-table td::-webkit-scrollbar {
            height: 6px;
        }

        .compact-table td::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .compact-table td::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .compact-table td::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .compact-table th {
            background-color: #e3f2fd !important;
            color: #333 !important;
            font-weight: 600 !important;
            border: none !important;
            padding: 15px 12px !important;
            font-size: 14px !important;
        }
    </style>

    <div class="optimized-container bg-white shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-hover compact-table">
                <thead>
                    <tr>
                        <th class="text-center">Trimestre</th>
                        <th class="text-center">Total de docentes</th>
                        <th class="text-center">Capacitado en formación docente</th>
                        <th class="text-center">Capacitado en actualización profesional</th>
                        <th class="text-center">Competencias digitales</th>
                        <th class="text-center">Formación tutorial</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (['1', '2', '3', '4'] as $trimestre)
                        @php
                            // Asignar sufijo adecuado al trimestre
                            $trimestreTexto = match($trimestre) {
                                '1' => '1er',
                                '2' => '2do',
                                '3' => '3er',
                                '4' => '4to',
                            };
                        @endphp
                        <tr class="align-middle">
                            <td class="text-center">{{ $trimestreTexto }}</td>
                            <td class="text-center">{{ $estadisticas["trimestre_$trimestre"]['total_participantes'] }}</td>
                            <td class="text-center">{{ $estadisticas["trimestre_$trimestre"]['total_docente'] }}</td>
                            <td class="text-center">{{ $estadisticas["trimestre_$trimestre"]['total_profesional'] }}</td>
                            <td class="text-center">{{ $estadisticas["trimestre_$trimestre"]['total_tics'] }}</td>
                            <td class="text-center">{{ $estadisticas["trimestre_$trimestre"]['total_tutorias'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
