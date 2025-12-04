<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cursos docentes</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<!--<body class="font-sans text-gray-900 antialiased"> -->

<body class="font-sans antialiased flex items-center justify-center min-h-screen bg-gray-200">
    <div class="container mx-auto">
        <h1 class="text-center text-4xl font-bold my-8">Capacitación y Actualización Docente</h1>
        <div class="flex flex-col md:flex-row rounded-lg overflow-hidden shadow-md bg-white">
            <div class="md:w-1/2 flex items-center justify-center" style="background:rgb(27, 57, 107)">
                <img class="mx-auto h-64 rounded-full" src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
            <div class="md:w-1/2 flex items-center justify-center">
                <div class="w-full px-6 py-4 overflow-hidden">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
