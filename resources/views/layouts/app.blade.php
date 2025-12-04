<x-logo>
</x-logo>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(Auth::user() && Auth::user()->datos_generales)
            {{ Auth::user()->datos_generales->nombre }} {{ Auth::user()->datos_generales->apellido_paterno }} {{ Auth::user()->datos_generales->apellido_materno }}
        @else
            {{ Auth::user()->name ?? Auth::user()->nombre ?? '' }}
        @endif
    </title>
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
    </script>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @if (session('success'))
                <div id="alert-success"
                    class="bg-green-500 text-white p-2 rounded-md shadow-md mb-4 max-w-sm mx-auto text-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="document.getElementById('alert-success').remove();"
                            class="ml-2 text-white focus:outline-none">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div id="alert-error"
                    class="bg-red-600 text-white p-4 rounded-lg shadow-lg mb-4 max-w-sm mx-auto text-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="document.getElementById('alert-error').remove();"
                            class="ml-3 text-white focus:outline-none">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            @if (session('info'))
                <div id="alert-info"
                    class="bg-blue-500 text-white p-2 rounded-md shadow-md mb-4 max-w-sm mx-auto text-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span>{{ session('info') }}</span>
                        </div>
                        <button onclick="document.getElementById('alert-info').remove();"
                            class="ml-2 text-white focus:outline-none">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            {{ $slot }}


        </main>
    </div>
</body>

<script>
    // Esperar a que la página se haya cargado
    window.addEventListener('load', function() {
        // Verifica si existe un mensaje de alerta (success, error, information)
        const alertSuccess = document.getElementById('alert-success');
        const alertError = document.getElementById('alert-error');
        const alertInfo = document.getElementById('alert-info');

        // Función para ocultar la alerta con desvanecimiento
        function fadeOutAlert(alertElement) {
            if (alertElement) {
                // Configura un temporizador para ocultar el mensaje después de 5 segundos
                setTimeout(function() {
                    alertElement.classList.add(
                    'opacity-0'); // Aplica la clase para hacer que se desvanezca
                    alertElement.classList.add(
                    'transition-opacity'); // Agrega transición de desvanecimiento
                    alertElement.classList.add('duration-500'); // Duración de la transición (500ms)

                    // Después de que la transición termine, elimina el elemento del DOM
                    setTimeout(function() {
                        alertElement.remove();
                    }, 500); // Tiempo de espera para eliminar (igual que la duración de la transición)
                }, 5000); // Tiempo antes de iniciar el desvanecimiento (5 segundos)
            }
        }

        // Llama a la función para cada tipo de alerta
        fadeOutAlert(alertSuccess);
        fadeOutAlert(alertError);
        fadeOutAlert(alertInfo);
    });
</script>

</html>
