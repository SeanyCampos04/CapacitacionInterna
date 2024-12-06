<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles del Curso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .section-title {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Detalles del Curso: {{ $curso->nombre }}</h2>
        <p><strong>Fecha de Inicio:</strong> {{ $curso->fdi }}</p>
        <p><strong>Fecha Final:</strong> {{ $curso->fdf }}</p>
        <p><strong>Duración:</strong> {{ $curso->duracion }} horas</p>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>NOMBRE</th>
                <th>INSTRUCTOR/PARTICIPANTE</th>
                <th>NO. REGISTRO DE CONSTANCIA</th>
            </tr>
        </thead>
        <tbody>
            @php
            $numero = 1;
            $numeroInstructor = 1;
            $numeroParticipante = 1;
            @endphp
            @foreach ($instructores as $instructor)
                <tr>
                    <td>{{ $numero++ }}</td>
                    <td>{{ $instructor->user->datos_generales->nombre }}
                        {{ $instructor->user->datos_generales->apellido_paterno }}
                        {{ $instructor->user->datos_generales->apellido_materno }}</td>
                    <td>INSTRUCTOR</td>
                    <td>TNM-169-{{ sprintf('%02d', $numeroDelCurso) }}-{{$curso->periodo->anio}}/I-{{ sprintf('%02d', $numeroInstructor++)}}</td>
                </tr>
            @endforeach
            @foreach ($participantes as $participanteInscrito)
            @if ($participanteInscrito->acreditado == 2)
            <tr>
                <td>{{ $numero++ }}</td>
                <td>{{ $participanteInscrito->participante->user->datos_generales->nombre }}
                    {{ $participanteInscrito->participante->user->datos_generales->apellido_paterno }}
                    {{ $participanteInscrito->participante->user->datos_generales->apellido_materno }}</td>
                <td>PARTICIPANTE</td>
                <td>TNM-169-{{ sprintf('%02d', $numeroDelCurso) }}-{{$curso->periodo->anio}}/{{ sprintf('%02d', $numeroParticipante++)}}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</body>

</html>
