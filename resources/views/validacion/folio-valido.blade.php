<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Validación de Folio
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col items-center pt-10 bg-gray-100">

        <div class="w-full sm:max-w-2xl bg-white shadow-lg rounded-lg p-8">

            <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-green-600 mx-auto" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75l2.25 2.25L15 9.75m6 2.25a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>

                <h3 class="text-3xl font-bold text-green-700 mt-4">¡Folio válido!</h3>
                <p class="text-gray-600 mt-1">
                    El folio corresponde a un documento oficial registrado.
                </p>
            </div>

            <div class="mt-6 space-y-3 bg-gray-50 p-6 rounded-lg border">
                <p><strong>Folio:</strong> {{ $folio }}</p>
                <p><strong>Nombre completo:</strong> {{ $nombre }}</p>
                <p><strong>Curso / Diplomado:</strong> {{ $curso }}</p>
                <p><strong>Descripción:</strong> {{ $descripcion }}</p>
                <p><strong>Fecha de emisión:</strong> {{ $fecha_emision }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
