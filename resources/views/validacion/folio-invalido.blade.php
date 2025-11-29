<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            Validación de Folio
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col items-center pt-10 bg-gray-100">

        <div class="w-full sm:max-w-lg bg-white shadow-lg rounded-lg p-8">

            <div class="text-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-20 w-20 text-red-600 mx-auto"
                    fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3.75m0 3.75h.007v.007H12V16.5zm0-12a9 9 0 11-9 9 9 9 0 019-9z" />
                </svg>

                <h3 class="text-3xl font-bold text-red-700 mt-4">Folio inválido</h3>

                <p class="text-gray-600 mt-2">
                    El folio ingresado no existe en el sistema o fue marcado como no válido.
                </p>
            </div>

            <div class="mt-6 bg-red-50 border border-red-300 text-red-700 p-4 rounded-lg">
                <p><strong>Folio consultado:</strong> {{ $folioIngresado }}</p>
            </div>

            <div class="text-center mt-6">
                <a href="/"
                   class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-md">
                    Volver a consultar
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
