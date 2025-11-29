<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Capacitación</title>
    <style>
        @page {
        size: letter portrait; /* también puede ser: letter landscape */
        margin: 1cm;
    }
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            @if(isset($imagenFondo) && $imagenFondo && file_exists($imagenFondo))
            background-image: url('{{ $imagenFondo }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            @endif
        }

        @if(isset($imagenFondo) && $imagenFondo && file_exists($imagenFondo))
        /* Overlay para mejorar legibilidad del texto sobre la imagen */
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.85);
            z-index: -1;
        }
        @endif

        .container {
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            position: relative;
            z-index: 1;
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
            border: 1px dashed #ccc;
            text-align: center;
            font-size: 10px;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
        }        .numero-registro {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 12px;
            font-weight: bold;
            color: #666;
            border: 1px solid #ccc;
            padding: 5px 10px;
            background-color: #f9f9f9;
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Número de registro - DESACTIVADO TEMPORALMENTE -->
        {{-- <div class="numero-registro">
            No. Registro: {{ $numeroRegistro ?? 'N/A' }}
        </div> --}}

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

        <!-- Nombre completo (2.4cm) -->
        <p class="recipient-name">{{ $capacitacion->nombre }} {{ $capacitacion->apellido_paterno }} {{ $capacitacion->apellido_materno }}</p>

        <!-- Detalles de la capacitación (parte verde) -->
        <p class="details">
            Por participar y acreditar el {{ $capacitacion->tipo_capacitacion }}
            <strong>"{{ strtoupper($capacitacion->nombre_capacitacion) }}"</strong>
            en el periodo {{ $capacitacion->anio }} con un total de {{ $capacitacion->horas }} horas.
        </p>

        <!-- Pie de página con firma y director (parte vino - espacio considerable) -->
        <div class="signature-section">
            <div class="footer">
                <p class="director">MAP. Héctor Aguilar Ponce<br>Director</p>
            </div>
        </div>

        <!-- Placeholder para código QR (arriba del número) -->
        <div class="qr-placeholder">
            <span>QR Code</span>
        </div>

        <!-- Número de registro (solo el número, sin texto) -->
        <div class="status">
            <p>
                @if($capacitacion->folio)
                    @if($capacitacion->folio == 'Rechazado')
                        {{ $capacitacion->folio }}
                    @else
                        @if(str_starts_with($capacitacion->folio, 'TNM-169-'))
                            {{ $capacitacion->folio }}
                        @else
                            TNM-169-{{ $capacitacion->folio }}
                        @endif
                    @endif
                @else
                    Sin asignar
                @endif
            </p>
        </div>

        <!-- Fecha y lugar (fila completa centrada) -->
        <div class="date">
            @php
                use Carbon\Carbon;

                Carbon::setLocale('es'); // Asegura que Carbon utilice el idioma español
                $fecha = Carbon::now();
            @endphp

            <p>Ciudad Valles, San Luis Potosí, a {{ $fecha->day }} de {{ $fecha->translatedFormat('F') }} del {{ $fecha->year }}</p>
        </div>

        <!-- Logos de afiliaciones (abajo a la izquierda) -->
        <div class="logos-afiliaciones">
            <img src="{{ public_path('libre.png') }}" style="width: 100px; height: auto;">
        </div>

    </div>
</body>
</html>
