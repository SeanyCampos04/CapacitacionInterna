<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial') }}
        </h2>
    </x-slot>

    <div class="container mx-auto p-6 bg-gray-100">

        <div class="diplomado bg-white p-6 mb-6 rounded-lg shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold text-blue-600 mb-4">Diplomado en Inteligencia Artificial</h2>
            <p class="text-gray-700"><span class="font-bold">Fecha de inicio:</span> Julio 2022</p>
            <p class="text-gray-700"><span class="font-bold">Fecha de finalización:</span> Diciembre 2022</p>
            <p class="text-gray-700"><span class="font-bold">Institución:</span> Instituto Tecnológico de Monterrey</p>
            <p class="text-gray-700"><span class="font-bold">Calificación:</span> NA</p>
        </div>

        <div class="diplomado bg-white p-6 mb-6 rounded-lg shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold text-blue-600 mb-4">Diplomado en Marketing Digital</h2>
            <p class="text-gray-700"><span class="font-bold">Fecha de inicio:</span> Marzo 2021</p>
            <p class="text-gray-700"><span class="font-bold">Fecha de finalización:</span> Agosto 2021</p>
            <p class="text-gray-700"><span class="font-bold">Institución:</span> Universidad de Marketing</p>
            <p class="text-gray-700"><span class="font-bold">Calificación:</span> 80</p>
        </div>
    </div>
</x-app-diplomados-layout>
