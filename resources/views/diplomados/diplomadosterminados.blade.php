<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Diplomados Terminados') }}
        </h2>
    </x-slot>

    <style>
        .optimized-container {
            width: 95%;
            max-width: 1200px !important;
            margin: 2rem auto !important;
            padding: 1.5rem;
        }
    </style>

    <div class="optimized-container bg-white shadow-lg rounded-lg">
        <h1 class="text-center text-2xl font-bold text-gray-800 mb-6">Diplomados registrados</h1>

        <!-- Artículo 1 -->
        <div class="diplomado flex justify-between items-center border-b border-gray-300 py-4">
            <div class="descripcion flex-1 ml-4">
                <p class="nombre text-lg font-semibold text-gray-800">Diplomado 1</p>
                <p class="tipo text-md text-gray-600">Dreava 3.3</p>
                <p class="text-sm text-gray-500">Descripcion del diplomado</p>
            </div>
            <div class="text-center mt-4">
                <!-- Aquí puedes agregar botones u otros elementos si lo deseas -->
            </div>
        </div>

        <!-- Artículo 2 -->
        <div class="diplomado flex justify-between items-center border-b border-gray-300 py-4">
            <div class="descripcion flex-1 ml-4">
                <p class="nombre text-lg font-semibold text-gray-800">Diplomado 2</p>
                <p class="tipo text-md text-gray-600">Educación Inclusiva</p>
                <p class="text-sm text-gray-500">Descripción del diplomado</p>
            </div>
            <div class="text-center mt-4">
                <!-- Aquí puedes agregar botones u otros elementos si lo deseas -->
            </div>
        </div>
    </div>

</x-app-diplomados-layout>
