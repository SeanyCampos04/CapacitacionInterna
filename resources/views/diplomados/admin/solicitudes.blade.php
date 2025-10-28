@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitudes de inscripción al diplomado ') }} {{ $solicitudes->nombre }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
            padding: 8px 16px;
            border-radius: 6px;
            display: inline-block;
            align-items: center;
            justify-content: center;
            border: none;
            margin: 0 2px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-accept {
            background-color: #28a745;
            color: white;
        }
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
        .btn-view {
            background-color: #007bff;
            color: white;
        }
        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: white;
        }
        .btn-accept:hover {
            background-color: #218838;
            color: white;
        }
        .btn-reject:hover {
            background-color: #c82333;
            color: white;
        }
        .btn-view:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>

    <div class="container-fluid px-4 py-4">
        <div class="card-container">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 w-100">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo Institucional</th>
                                <th>Departamento</th>
                                <th>Estatus</th>
                                <th>Como</th>
                                <th>Acciones</th>
                                <th>Ver Documento</th>
                            </tr>
                        </thead>
                        <tbody>
                @foreach ($solicitudes->solicitudesParticipantes as $solicitud_participante)
                    @if ($solicitud_participante->estatus == 0)
                        <tr>
                            <td>{{ $solicitud_participante->participante->user->datos_generales->nombre }}</td>
                            <td>{{ $solicitud_participante->participante->user->email }}</td>
                            <td>{{ $solicitud_participante->participante->user->datos_generales->departamento->nombre }}</td>
                            <td>
                                @if ($solicitud_participante->estatus == 0)
                                    En espera
                                @elseif ($solicitud_participante->estatus == 1)
                                    Negado
                                @else
                                    Aceptado
                                @endif
                            </td>
                            <td>Participante</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!-- Botón Aceptar -->
                                    <form action="{{ route('diplomados.solicitudes_aceptar_docente', $solicitud_participante->id) }}"
                                        method="POST" style="display: inline;"
                                        onsubmit="return confirm('¿Estás seguro de aceptar esta solicitud como participante?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-action btn-accept" title="Aceptar">
                                            Aceptar
                                        </button>
                                    </form>

                                    <!-- Botón Negar -->
                                    <form action="{{ route('diplomados.solicitudes_negar_docente', $solicitud_participante->id) }}"
                                        method="POST" style="display: inline;"
                                        onsubmit="return confirm('¿Estás seguro de negar esta solicitud como participante?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-action btn-reject" title="Negar">
                                            Negar
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                @if ($solicitud_participante->carta_compromiso)
                                    <a href="{{ asset('archivos/' . $solicitud_participante->carta_compromiso) }}"
                                       target="_blank" class="btn-action btn-view" title="Ver PDF">Ver PDF</a>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach

                @foreach ($solicitudes->solicitudesInstructores as $solicitud_instructor)
                    @if ($solicitud_instructor->estatus == 0)
                        <tr>
                            <td>{{ $solicitud_instructor->instructore->user->datos_generales->nombre }}</td>
                            <td>{{ $solicitud_instructor->instructore->user->email }}</td>
                            <td>{{ $solicitud_instructor->instructore->user->datos_generales->departamento->nombre }}</td>
                            <td>
                                @if ($solicitud_instructor->estatus == 0)
                                    En espera
                                @elseif ($solicitud_instructor->estatus == 1)
                                    Negado
                                @else
                                    Aceptado
                                @endif
                            </td>
                            <td>Instructor</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!-- Botón Aceptar -->
                                    <form action="{{ route('diplomados.solicitudes_aceptar_instructor', $solicitud_instructor->id) }}"
                                        method="POST" style="display: inline;"
                                        onsubmit="return confirm('¿Estás seguro de aceptar esta solicitud como instructor?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-action btn-accept" title="Aceptar">
                                            Aceptar
                                        </button>
                                    </form>

                                    <!-- Botón Negar -->
                                    <form action="{{ route('diplomados.solicitudes_negar_instructor', $solicitud_instructor->id) }}"
                                        method="POST" style="display: inline;"
                                        onsubmit="return confirm('¿Estás seguro de negar esta solicitud como instructor?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-action btn-reject" title="Negar">
                                            Negar
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                @if ($solicitud_instructor->carta_terminacion)
                                    <a href="{{ asset('archivos/' . $solicitud_instructor->carta_terminacion) }}"
                                       target="_blank" class="btn-action btn-view" title="Ver PDF">Ver PDF</a>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-diplomados-layout>
