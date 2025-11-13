<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Diplomado</title>
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

        .footer {
            position: absolute;
            /* subir aún más la firma del director */
            bottom: 320px;
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

        .diplomado-info {
            font-size: 14px;
            margin: 15px 0;
            text-align: center;
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
        <p class="subtitle">Otorga el presente</p>
        <p class="title">Reconocimiento</p>
        <p class="subtitle">A</p>
        <p class="recipient-name">
            {{ $usuario->datos_generales->nombre }}
            {{ $usuario->datos_generales->apellido_paterno }}
            {{ $usuario->datos_generales->apellido_materno }}
        </p>

        <!-- Detalles del diplomado -->
        <p class="details">
            Por participar
            @if($tipoRegistro === 'Instructor')
                como instructor
            @endif
            @if($tipoRegistro === 'Participante')
                y acreditar satisfactoriamente
            @endif
            en el diplomado
            <strong>"{{ strtoupper($diplomado->nombre) }}"</strong>
            realizado del {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('d') }} de {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->translatedFormat('F') }}
            al {{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->format('d') }} de {{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->translatedFormat('F') }}
            del {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('Y') }},
            con una duración de {{ $duracionDias }} días, de tipo {{ strtoupper($diplomado->tipo) }},
            realizado en {{ strtoupper($diplomado->sede) }}.
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
