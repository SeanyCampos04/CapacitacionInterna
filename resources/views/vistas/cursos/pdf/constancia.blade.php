<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Curso</title>
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
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 2cm); /* Asegurar que ocupe toda la altura */
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
            padding: 0.3cm 0;
            text-align: justify;
            text-transform: uppercase;
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
            bottom: 3.5cm;
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
                la presente
            @endif
            </p>
        </div>

        <!-- Constancia/Reconocimiento/Diploma (parte rosa - grande) -->
        <div>
            <p class="subtitle-large">
            @if($tipoUsuario === 'Instructor')
                Reconocimiento
            @else
                Constancia
            @endif
            </p>
        </div>

        <!-- Sección A -->
        <div class="a-section">
            <p class="subtitle">A</p>
        </div>

        <!-- Nombre completo -->
        @if($tipoUsuario === 'Instructor')
            <p class="recipient-name">{{ $participante->user->datos_generales->nombre ?? 'Sin nombre' }} {{ $participante->user->datos_generales->apellido_paterno ?? '' }} {{ $participante->user->datos_generales->apellido_materno ?? '' }}</p>
        @else
            <p class="recipient-name">{{ $participante->participante->user->datos_generales->nombre ?? 'Sin nombre' }} {{ $participante->participante->user->datos_generales->apellido_paterno ?? '' }} {{ $participante->participante->user->datos_generales->apellido_materno ?? '' }}</p>
        @endif

        <!-- Detalles del curso -->
        <p class="details">
            Por {{ $tipoUsuario === 'Instructor' ? 'impartir' : 'participar y acreditar satisfactoriamente' }} el curso de capacitación
            <strong>"{{ strtoupper($curso->nombre) }}"</strong>
            impartido por
            @foreach($curso->instructores as $instructorCurso)
                {{ $instructorCurso->user->datos_generales->nombre ?? 'Sin nombre' }}
                {{ $instructorCurso->user->datos_generales->apellido_paterno ?? '' }}
                {{ $instructorCurso->user->datos_generales->apellido_materno ?? '' }}@if(!$loop->last), @endif
            @endforeach
            del {{ \Carbon\Carbon::parse($curso->fdi)->format('d') }} de {{ \Carbon\Carbon::parse($curso->fdi)->translatedFormat('F') }}
            al {{ \Carbon\Carbon::parse($curso->fdf)->format('d') }} de {{ \Carbon\Carbon::parse($curso->fdf)->translatedFormat('F') }}
            del {{ \Carbon\Carbon::parse($curso->fdi)->format('Y') }},
            con una duración de {{ $curso->duracion }} horas, con la modalidad {{ strtoupper($curso->modalidad) }},
            realizado en {{ strtoupper($curso->lugar) }}, en el departamento de {{ strtoupper($curso->departamento->nombre) }}@if($tipoUsuario === 'Participante' && isset($calificacion)), con la calificación obtenida de {{ $calificacion }}@endif.
        </p>

        <!-- Pie de página con firma y director -->
        <div class="signature-section">
            <div class="footer">
                <p class="director">MAP. Héctor Aguilar Ponce<br>Director</p>
            </div>
        </div>

        <!-- Código QR para verificación -->
        <div class="qr-placeholder" style="margin-bottom: 0.1cm;">
            @if(isset($codigoQR) && !empty($codigoQR))
                <img src="{{ $codigoQR }}" alt="Código QR" width="80" height="80" style="display: block; margin: 0 auto;">
            @else
                <div style="width: 80px; height: 80px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                    <span style="font-size: 8px; color: #666;">QR no disponible</span>
                </div>
            @endif
        </div>

        <!-- Número de registro -->
        <div class="status" style="margin-top: -0.1cm;">
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
