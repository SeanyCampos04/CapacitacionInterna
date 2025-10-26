<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Diplomados Registrados') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">

        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Nombre
                    </th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Tipo</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Sede</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Inicio
                        Oferta</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Término
                        Oferta</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Inicio
                        Realización</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Término
                        Realización</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($diplomados as $diplomado)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $diplomado->nombre }}</td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $diplomado->tipo }}</td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $diplomado->sede }}</td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($diplomado->inicio_oferta)->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($diplomado->termino_oferta)->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->format('d/m/Y') }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 text-sm flex gap-2">
                        <head>
                        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
                        </head>

                        <a href="{{ route('diplomados.detalle', $diplomado->id) }}"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center gap-2">
                            <i class="fas fa-eye"></i></a>

                        @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                            <a href="{{ route('diplomados.diplomados.edit', $diplomado->id) }}"
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 flex items-center gap-2">
                                <i class="fas fa-pencil-alt"></i></a>

                            <form action="{{ route('diplomados.diplomados.destroy', $diplomado->id) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar este diplomado?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 flex items-center gap-2">
                                    <i class="fas fa-trash"></i></button>
                            </form>

                            <form action="{{ route('diplomados.solicitudes_diplomado.index', $diplomado->id) }}" method="GET" class="inline">
                                @csrf
                                @method('GET')
                                <button type="submit"
                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 flex items-center gap-2">
                                    <i class="fas fa-book"></i></button>
                            </form>
                        @endif


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Scripts necesarios para Bootstrap Modal -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</x-app-diplomados-layout>
