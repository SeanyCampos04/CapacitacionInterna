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
            background: linear-gradient(135deg, rgb(27, 57, 107) 0%, rgb(45, 80, 130) 100%);
            min-height: 100vh;
        }
        .verification-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
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
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen py-6">
        <div class="container mx-auto px-4">
            <!-- Header con logo y título -->
            <div class="text-center mb-6">
                <img class="mx-auto h-20 mb-4" src="{{ asset('images/logo.png') }}" alt="Logo TecNM">
                <h1 class="text-white text-2xl font-bold">Tecnológico Nacional de México</h1>
                <p class="text-white opacity-90">Instituto Tecnológico de Ciudad Valles</p>
            </div>

            <!-- Contenido principal -->
            <div class="max-w-4xl mx-auto">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <div class="text-white opacity-75">
                    <img class="mx-auto h-12 mb-2" src="{{ asset('edu.png') }}" alt="SEP">
                    <p class="text-sm">
                        <i class="fas fa-calendar-alt"></i>
                        Verificación realizada el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
