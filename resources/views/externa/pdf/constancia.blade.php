<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Capacitación</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 10;
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
        .status {
            position: absolute;
            bottom: 10px; /* Ajusta la distancia desde la parte inferior */
            right: 10px;  /* Ajusta la distancia desde la parte derecha */
            font-size: 16px;
            text-align: right;
            text-transform: uppercase;
        }

        .footer {
            position: middle;
            bottom: 40px;
            left: 0;
            width: 100%;
            text-align: center;
        }

        .footer .director {
            margin-top: 150px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer .sign {
            height: 80px;
        }

        .numero-registro {
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
        <p class="recipient-name">{{ $capacitacion->nombre }} {{ $capacitacion->apellido_paterno }} {{ $capacitacion->apellido_materno }}</p>

        <!-- Detalles de la capacitación -->
            <p class="details">
            Por participar y acreditar el {{ $capacitacion->tipo_capacitacion }}
            <strong>"{{ strtoupper($capacitacion->nombre_capacitacion) }}"</strong>
            en el periodo {{ $capacitacion->anio }} con un total de {{ $capacitacion->horas }} horas.
            </p>
        <div class="date">
            @php
                use Carbon\Carbon;

                Carbon::setLocale('es'); // Asegura que Carbon utilice el idioma español
                $fecha = Carbon::now();
            @endphp

            <p>Ciudad Valles, San Luis Potosí, a {{ $fecha->day }} de {{ $fecha->translatedFormat('F') }} del {{ $fecha->year }}</p>
        </div>

        <!-- Pie de página con firma y director -->
        <div class="footer">
            <p class="director">MAP. Héctor Aguilar Ponce<br>Director</p>
        </div>
        <div class="status">
            <p>Número de registro:</p>
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
            <img src="{{ public_path('libre.png') }}" style="width: 100px; height: auto;">
        </div>

    </div>
</body>
</html>
