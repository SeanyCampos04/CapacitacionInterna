<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calificar modulo') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">

        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Docente</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Calificacion</th>
                </tr>
           </thead>
            <tbody>
                <tr class="hover:bg-gray-50">
                <td class="py-2 px-4 border-b border-gray-200 text-sm">Cythia</td>
                <td class="py-2 px-4 border-b border-gray-200 text-sm">Calificar</td>
            </tr>
            </tbody>
        </table>
    </div>
</x-app-diplomados-layout>
