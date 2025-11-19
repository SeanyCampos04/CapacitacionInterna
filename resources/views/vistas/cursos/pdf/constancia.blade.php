<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Curso</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        .header img {
            height: 60px;
        }

        .title {
            font-size: 20px;
            margin-top: 20px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .subtitle {
            font-size: 16px;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .recipient-name {
            font-size: 24px;
            margin: 20px 0;
            font-weight: bold;
            text-transform: uppercase;
            color: #2c3e50;
        }

        .details {
            font-size: 16px;
            margin-top: 10px;
            text-align: justify;
            text-transform: uppercase;
        }

        .date {
            font-size: 16px;
            margin-top: 10px;
            text-align: center;
            text-transform: uppercase;
        }

        .footer {
            position: absolute;
            /* aumentar bottom para subir la firma hacia arriba del documento */
            bottom: 290px;
            left: 0;
            width: 100%;
            text-align: center;
        }

        .footer .director {
            /* eliminar margen extra para que la firma quede más compacta y más arriba */
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .date-location {
            position: absolute;
            bottom: 120px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 16px;
            text-transform: uppercase;
        }

        /* Logos en la parte inferior izquierda */
        .bottom-logos {
            position: absolute;
            bottom: 20px;
            left: 40px; /* desplazar a la izquierda con un pequeño padding */
            width: auto;
            text-align: left;
        }

        .bottom-logos img {
            height: 60px; /* logos más grandes */
            margin-right: 8px; /* más juntos */
            vertical-align: middle;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado con logos -->
        <div class="header">
            <img src="{{ public_path('edu.png') }}" alt="Logo izquierdo">
            <img src="{{ public_path('linea.png') }}" alt="Logo medio">
            <img src="{{ public_path('logo_tecnm.png') }}" alt="Logo derecho">
        </div>

        <!-- Título principal -->
        <p class="title">El Tecnológico Nacional de México</p>
        <p class="title">A través del Instituto Tecnológico de Ciudad Valles</p>

        <!-- Texto de la constancia -->
        <p class="subtitle">Otorga
        @if($tipoUsuario === 'Instructor')
            el presente
        @else
            la presente
        @endif
        </p>
        <p class="title">
        @if($tipoUsuario === 'Instructor')
            Reconocimiento
        @else
            Constancia
        @endif
        </p>
        <p class="subtitle">A</p>
        <p class="recipient-name">
            {{ $participante->user->datos_generales->nombre }}
            {{ $participante->user->datos_generales->apellido_paterno }}
            {{ $participante->user->datos_generales->apellido_materno }}
        </p>

        <!-- Detalles del curso -->
        <p class="details">
            Por participar y acreditar satisfactoriamente el curso de capacitación
            <strong>"{{ strtoupper($curso->nombre) }}"</strong>
            impartido por
            @foreach($curso->instructores as $instructor)
                {{ $instructor->user->datos_generales->nombre }}
                {{ $instructor->user->datos_generales->apellido_paterno }}
                {{ $instructor->user->datos_generales->apellido_materno }}@if(!$loop->last), @endif
            @endforeach
            del {{ \Carbon\Carbon::parse($curso->fdi)->format('d') }} de {{ \Carbon\Carbon::parse($curso->fdi)->translatedFormat('F') }}
            al {{ \Carbon\Carbon::parse($curso->fdf)->format('d') }} de {{ \Carbon\Carbon::parse($curso->fdf)->translatedFormat('F') }}
            del {{ \Carbon\Carbon::parse($curso->fdi)->format('Y') }},
            con una duración de {{ $curso->duracion }} horas, con la modalidad {{ strtoupper($curso->modalidad) }},
            realizado en {{ strtoupper($curso->lugar) }}, en el departamento de {{ strtoupper($curso->departamento->nombre) }}@if($calificacion), con la calificación obtenida de {{ $calificacion }}@endif.
        </p>

        <!-- Pie de página con firma y director -->
        <div class="footer">
            <p class="director">MAP. Héctor Aguilar Ponce<br>Director</p>
        </div>

        <!-- Fecha y ubicación -->
        <div class="date-location">
            @php
                use Carbon\Carbon;
                Carbon::setLocale('es');
                $fecha = $fecha_actual;
            @endphp
            <p>Ciudad Valles, San Luis Potosí, a {{ $fecha->day }} de {{ $fecha->translatedFormat('F') }} del {{ $fecha->year }}</p>
        </div>

        <!-- Logos inferiores -->
        <div class="bottom-logos">
            <img src="{{ public_path('bottom_logo1.png') }}" alt="Logo inferior 1">
            <img src="{{ public_path('bottom_logo2.png') }}" alt="Logo inferior 2">
            <img src="{{ public_path('bottom_logo3.png') }}" alt="Logo inferior 3">
        </div>
    </div>
</body>
</html>
