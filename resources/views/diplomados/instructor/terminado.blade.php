<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Diplomados en curso') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">
        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Diplomado</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Objetivo</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-center text-sm font-semibold">Detalles</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($diplomados as $solicitud)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b border-gray-200 text-sm font-semibold text-blue-600">
                            {{ $solicitud->diplomado->nombre }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">
                            {{ $solicitud->diplomado->objetivo }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 text-center">
                            <a href="{{ route('diplomados.detalles_instructor', $solicitud->diplomado->id) }}"
                               class="text-blue-500 hover:underline">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-2 px-4 text-center text-gray-500">
                            No hay diplomados terminados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-diplomados-layout>
