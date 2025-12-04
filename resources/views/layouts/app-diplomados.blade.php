<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Diplomados</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Forzar modo claro siempre -->
        <script>
            // Remover cualquier clase dark del documento
            document.documentElement.classList.remove('dark');
            // Asegurar que no se añada modo oscuro
            localStorage.setItem('darkMode', 'false');
            // Prevenir cambios automáticos por preferencias del sistema
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Variables para pasar a las vistas -->
        @php
            $user_roles = auth()->user()->user_roles->pluck('role.name')->toArray();
            $tipo_usuario = auth()->user()->tipo_usuario;
        @endphp
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Logo/Banner del Tecnológico -->
            <div class="bg-white">
                <x-logo />
            </div>

            @include('layouts.navigation-diplomados')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
