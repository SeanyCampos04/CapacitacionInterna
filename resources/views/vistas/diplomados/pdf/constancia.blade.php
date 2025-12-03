<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Diplomado</title>
    <style>
        @page {
            size: letter portrait; /* también puede ser: letter landscape */
            margin: 0; /* Sin márgenes para que la imagen de fondo cubra toda la página */
        }
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            @if(isset($imagenFondo) && $imagenFondo && file_exists($imagenFondo))
            background-image: url('{{ $imagenFondo }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            @endif
        }

        .container {
            margin-top: 1.5cm;
            margin-bottom: 0.5cm;
            margin-left: 2cm;
            margin-right: 2cm;
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 2cm); /* Asegurar que ocupe toda la altura */
            max-width: calc(21.59cm - 4cm); /* Ancho carta menos márgenes */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0;
            height: auto;
            margin-bottom: 1cm;
        }

        .header img {
            height: 60px;
        }

        .title {
            font-size: 18px;
            margin: 0.15cm 0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .title-main {
            font-size: 20px;
            margin: 0.2cm 0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .title-institute {
            font-size: 16px;
            margin: 0.1cm 0;
            text-transform: uppercase;
            font-weight: normal;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 16px;
            margin: 0.2cm 0;
            text-transform: uppercase;
        }

        .subtitle-large {
            font-size: 36px;
            margin: 0.4cm 0;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .title-section {
            margin-bottom: 0.3cm;
        }

        .recognition-section {
            margin: 0.5cm 0;
        }

        .a-section {
            margin: 0.4cm 0;
        }

        .recipient-name {
            font-size: 28px;
            margin: 0.6cm 0;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.1;
            word-wrap: break-word;
            hyphens: auto;
        }

        .details {
            font-size: 16px;
            margin: 0.6cm 0;
            padding: 0.3cm 0.5cm;
            text-align: justify;
            text-transform: uppercase;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            line-height: 1.3;
            max-width: 100%;
            box-sizing: border-box;
        }
        .date {
            position: absolute;
            bottom: 1.5cm;
            left: 0;
            right: 0;
            font-size: 16px;
            text-align: center;
            text-transform: uppercase;
            width: 100%;
        }
        .status {
            position: absolute;
            bottom: 2.5cm;
            right: 1.5cm;
            font-size: 12px;
            text-align: right;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .status p {
            margin: 0;
            padding: 0;
            display: inline;
        }

        .signature-section {
            position: relative;
            margin-top: 2.5cm;
            margin-bottom: 0.5cm;
            height: 2.5cm;
        }

        .footer {
            width: 100%;
            text-align: center;
        }

        .footer .director {
            margin-top: 1.5cm;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer .sign {
            height: 80px;
        }

        .logos-afiliaciones {
            position: absolute;
            bottom: 0.3cm;
            left: 0;
            text-align: left;
        }

        .qr-placeholder {
            position: absolute;
            bottom: 3.2cm;
            right: 1.5cm;
            width: 2cm;
            height: 2cm;
            text-align: center;
            font-size: 10px;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-placeholder svg {
            width: 2cm;
            height: 2cm;
        }    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado con logos -->
        <div class="header">
            <img src="{{ public_path('edu.png') }}" alt="Logo izquierdo">
            <img src="{{ public_path('linea.png') }}" alt="Logo medio">
            <img src="{{ public_path('logo_tecnm.png') }}" alt="Logo derecho">
        </div>

        <!-- Título principal (parte roja - reducida) -->
        <div class="title-section">
            <p class="title-main">El Tecnológico Nacional de México</p>
        </div>

        <!-- Instituto (parte verde - sin negritas, un solo renglón) -->
        <div>
            <p class="title-institute">A través del Instituto Tecnológico de Ciudad Valles</p>
        </div>

        <!-- Otorga presente (parte azul) -->
        <div class="recognition-section">
            <p class="subtitle">Otorga
            @if($tipoUsuario === 'Instructor')
                el presente
            @else
                @if($tipoUsuario === 'Participante')
                    el presente
                @else
                    la presente
                @endif
            @endif
            </p>
        </div>

        <!-- Constancia/Reconocimiento/Diploma (parte rosa - grande) -->
        <div>
            <p class="subtitle-large">
            @if($tipoUsuario === 'Instructor')
                Reconocimiento
            @else
                @if($tipoUsuario === 'Participante')
                    Diploma
                @else
                    Constancia
                @endif
            @endif
            </p>
        </div>

        <!-- Sección A -->
        <div class="a-section">
            <p class="subtitle">A</p>
        </div>

        <!-- Nombre completo -->
        <p class="recipient-name">{{ $participante->nombre }} {{ $participante->apellido_paterno }} {{ $participante->apellido_materno }}</p>

        <!-- Detalles del diplomado -->
        <p class="details">
            @if($tipoUsuario === 'Instructor')
                Por impartir como instructor el diplomado
                <strong>"{{ strtoupper($diplomado->nombre) }}"</strong>
                realizado del {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('d') }} de {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->translatedFormat('F') }}
                al {{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->format('d') }} de {{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->translatedFormat('F') }}
                del {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('Y') }},
                con una duración de {{ $duracionDias }} días, de tipo {{ strtoupper($diplomado->tipo) }},
                realizado en {{ strtoupper($diplomado->sede) }}.
            @else
                Por participar y acreditar satisfactoriamente en el diplomado
                <strong>"{{ strtoupper($diplomado->nombre) }}"</strong>
                realizado del {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('d') }} de {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->translatedFormat('F') }}
                al {{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->format('d') }} de {{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->translatedFormat('F') }}
                del {{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('Y') }},
                con una duración de {{ $duracionDias }} días, de tipo {{ strtoupper($diplomado->tipo) }},
                realizado en {{ strtoupper($diplomado->sede) }}.
            @endif
        </p>

        <!-- Pie de página con firma y director -->
        <div class="signature-section">
            <div class="footer">
                <p class="director">MAP. Héctor Aguilar Ponce<br>Director</p>
            </div>
        </div>

        <!-- Código QR para verificación -->
        <div class="qr-placeholder">
            @if(isset($codigoQR) && $codigoQR)
                <img src="{{ $codigoQR }}" alt="QR Code" style="width: 2cm; height: 2cm;">
            @else
                <div style="border: 1px solid #ccc; width: 2cm; height: 2cm; display: flex; align-items: center; justify-content: center; font-size: 8px;">
                    QR no generado
                </div>
            @endif
        </div>

        <!-- Número de registro -->
        <div class="status">
            <p>{{ $numeroRegistro ?? 'Sin asignar' }}</p>
        </div>

        <!-- Fecha y lugar -->
        <div class="date">
            @php
                use Carbon\Carbon;
                Carbon::setLocale('es');
                $fecha = Carbon::now();
            @endphp
            <p>Ciudad Valles, San Luis Potosí, a {{ $fecha->day }} de {{ $fecha->translatedFormat('F') }} del {{ $fecha->year }}</p>
        </div>

        <!-- Logos de afiliaciones -->
        <div class="logos-afiliaciones">
            <img src="{{ public_path('libre.png') }}" style="width: 100px; height: auto;">
        </div>

    </div>
</body>
</html>
