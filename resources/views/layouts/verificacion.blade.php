<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verificación de Documento - TecNM Instituto Tecnológico de Ciudad Valles</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: white;
            min-height: 100vh;
        }
        .verification-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        .header-section {
            background: linear-gradient(135deg, rgb(27, 57, 107) 0%, rgb(34, 67, 117) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .status-valid {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .status-invalid {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        .status-not-implemented {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }
        .info-section {
            padding: 2rem;
        }
        .info-row {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .verification-code {
            background: rgb(27, 57, 107);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            text-align: center;
            margin-top: 1rem;
        }
        .title-header {
            background: rgb(27, 57, 107);
            color: white;
            padding: 1.5rem 0;
        }
        .main-footer {
            background: rgb(27, 57, 107);
            color: white;
            padding: 3rem 0 2rem 0;
            margin-top: 4rem;
        }
        .main-footer h5 {
            color: white;
            margin-bottom: 1rem;
        }
        .main-footer p, .main-footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        .main-footer a:hover {
            color: white;
        }
    </style>
</head>

<body class="font-sans antialiased bg-white">
    <div class="min-h-screen">
        <!-- Header con logos oficiales -->
        <div class="w-full flex items-center justify-center" style="background:rgb(255, 255, 255);">
            <img src="/images/banner_tec.jpg" alt="Banner Tecnológico" style="width: 100%; max-width: 1200px; height: auto;">
        </div>

        <!-- Barra de título -->
        <div class="title-header">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-semibold text-2xl text-white">
                            Verificación de Documentos
                        </h2>
                    </div>
                    <div>
                        <span class="font-semibold text-lg text-white">
                            Capacitación Interna
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="container mx-auto px-4">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Dirección</h5>
                        <p>Carr. al Ingenio Plan de Ayala Km. 2, Col. Vista Hermosa, Cd. Valles, S.L.P. C.P. 79010</p>

                        <h5 class="mt-4">Contacto</h5>
                        <p>Email: escolares@tecvalles.mx</p>
                        <p>Conmutador: tel. (481) 381 20 44, 381 46 05 y 383 21 51</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Enlaces</h5>
                        <p><a href="#" class="text-light">Portal de Obligaciones de Transparencia INAI</a></p>

                        <h5 class="mt-4">Buzón de Sugerencias</h5>
                        <p>Número de Visitas:</p>

                        <!-- Mapa -->
                        <div class="mt-3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3698.6!2d-99.0167!3d21.9833!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d65c8b5c8b5c8b%3A0x5c8b5c8b5c8b5c8b!2sTecnol%C3%B3gico%20Nacional%20de%20M%C3%A9xico%20Campus%20Ciudad%20Valles!5e0!3m2!1ses!2smx!4v1234567890"
                                    width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <p class="mb-0">© Copyright 2024 TecNM/CdValles – Todos los Derechos Reservados</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
