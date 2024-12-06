<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Estadísticas de Cursos por Año') }} ({{ $anio }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xm text-gray-700 bg-gray-50">
                            <tr>
                               <th scope="col" class="px-6 py-3 text-center">Trimestre</th>
                               <th scope="col" class="px-6 py-3 text-center">Total de docentes</th>
                               <th scope="col" class="px-6 py-3 text-center">Capacitado en formación docente</th>
                               <th scope="col" class="px-6 py-3 text-center">Capacitado en actualización profesional</th>
                               <th scope="col" class="px-6 py-3 text-center">Competencias digitales</th>
                               <th scope="col" class="px-6 py-3 text-center">Formación tutorial</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (['1', '2', '3', '4'] as $trimestre)
                                @php
                                    // Asignar sufijo adecuado al trimestre
                                    $trimestreTexto = match($trimestre) {
                                        '1' => '1er',
                                        '2' => '2do',
                                        '3' => '3er',
                                        '4' => '4to',
                                    };
                                @endphp
                                <tr class="border-b">
                                    <td class=" text-center">{{ $trimestreTexto }}</td>
                                    <td class=" text-center">{{ $estadisticas["trimestre_$trimestre"]['total_participantes'] }}</td>
                                    <td class=" text-center">{{ $estadisticas["trimestre_$trimestre"]['total_docente'] }}</td>
                                    <td class=" text-center">{{ $estadisticas["trimestre_$trimestre"]['total_profesional'] }}</td>
                                    <td class=" text-center">{{ $estadisticas["trimestre_$trimestre"]['total_tics'] }}</td>
                                    <td class=" text-center">{{ $estadisticas["trimestre_$trimestre"]['total_tutorias'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
