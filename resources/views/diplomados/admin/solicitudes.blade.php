<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitudes de inscripción al diplomado ') }} {{ $solicitudes->nombre }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">

        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Nombre
                    </th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Correo
                        Institucional</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">
                        Departamento</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Estatus
                    </th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Como</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Acciones
                    </th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-blue-100 text-left text-sm font-semibold">Ver
                        Documento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudes->solicitudesParticipantes as $solicitud_participante)
                    @if ($solicitud_participante->estatus == 0)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                {{ $solicitud_participante->participante->user->datos_generales->nombre }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                {{ $solicitud_participante->participante->user->email }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                {{ $solicitud_participante->participante->user->datos_generales->departamento->nombre }}
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                @if ($solicitud_participante->estatus == 0)
                                    En espera
                                @elseif ($solicitud_participante->estatus == 1)
                                    Negado
                                @else
                                    Aceptado
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">Participante</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                <!-- Botones Aceptar y Negar -->
                                <form action="{{ route('diplomados.solicitudes_aceptar_docente', $solicitud_participante->id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600" onclick="return confirm('¿Estás seguro de aceptar esta solicitud como participante?');">Aceptar</button>
                                </form>

                                <form action="{{ route('diplomados.solicitudes_negar_docente', $solicitud_participante->id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('¿Estás seguro de negar esta solicitud como participante?');">Negar</button>
                                </form>
                            </td>
                            <!-- Ver archivo PDF -->
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                @if ($solicitud_participante->carta_compromiso)
                                    <a href="{{ asset('archivos/' . $solicitud_participante->carta_compromiso) }}"
                                        target="_blank" class="text-blue-500">Ver PDF</a>
                                @else
                                    <span>No disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach

                @foreach ($solicitudes->solicitudesInstructores as $solicitud_instructor)
                    @if ($solicitud_instructor->estatus == 0)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                {{ $solicitud_instructor->instructore->user->datos_generales->nombre }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                {{ $solicitud_instructor->instructore->user->email }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                {{ $solicitud_instructor->instructore->user->datos_generales->departamento->nombre }}
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                @if ($solicitud_instructor->estatus == 0)
                                    En espera
                                @elseif ($solicitud_instructor->estatus == 1)
                                    Negado
                                @else
                                    Aceptado
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">Instructor</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                <!-- Botones Aceptar y Negar -->
                                <form action="{{ route('diplomados.solicitudes_aceptar_instructor', $solicitud_instructor->id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"  onclick="return confirm('¿Estás seguro de aceptar esta solicitud como instructor?');">Aceptar</button>
                                </form>

                                <form action="{{ route('diplomados.solicitudes_negar_instructor', $solicitud_instructor->id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('¿Estás seguro de negar esta solicitud como instructor?');">Negar</button>
                                </form>
                            </td>
                            <!-- Ver archivo PDF -->
                            <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                @if ($solicitud_instructor->carta_terminacion)
                                    <a href="{{ asset('archivos/' . $solicitud_instructor->carta_terminacion) }}"
                                        target="_blank" class="text-blue-500">Ver PDF</a>
                                @else
                                    <span>No disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-diplomados-layout>
