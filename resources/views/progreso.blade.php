<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Progreso del diplomado') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">
        <ul>
            <!-- Módulo 1 -->
            <li class="py-4 px-6 bg-gray-100 border-l-4 border-blue-500 rounded-lg flex justify-between items-center">
                <div>
                    <span class="text-sm font-medium">Módulo 1: Introducción a la Programación</span>
                    <div class="text-sm">
                        <p>Instructor: Juan Pérez</p>
                        <p>Inicio: 01/01/2024</p>
                        <p>Término: 15/01/2024</p>
                    </div>
                </div>
                <p class="text-green-500 font-semibold text-center">Completado<br>(Calificación: 9.5)</p>
            </li>
            <!-- Módulo 2 -->
            <li class="py-4 px-6 bg-gray-100 border-l-4 border-blue-500 rounded-lg flex justify-between items-center">
                <div>
                    <span class="text-sm font-medium">Módulo 2: Fundamentos de HTML y CSS</span>
                    <div class="text-sm">
                        <p>Instructor: Ana Gómez</p>
                        <p>Inicio: 16/01/2024</p>
                        <p>Término: 30/01/2024</p>
                    </div>
                </div>
                <p class="text-green-500 font-semibold text-center">Completado<br>(Calificación: 8.8)</p>
            </li>
            <!-- Módulo 3 -->
            <li class="py-4 px-6 bg-green-100 border-l-4 border-green-500 rounded-lg flex justify-between items-center">
                <div>
                    <span class="text-sm font-medium">Módulo 3: Programación en JavaScript</span>
                    <div class="text-sm">
                        <p>Instructor: Luis Martínez</p>
                        <p>Inicio: 01/02/2024</p>
                        <p>Término: 15/02/2024</p>
                    </div>
                </div>
                <a href="{{ route('calificar') }}" class="text-blue-500 hover:underline">Ver participantes</a>
            </li>
            <!-- Módulo 4 -->
            <li class="py-4 px-6 bg-gray-100 border-l-4 border-blue-500 rounded-lg flex justify-between items-center">
                <div>
                    <span class="text-sm font-medium">Módulo 4: Frameworks y Librerías</span>
                    <div class="text-sm">
                        <p>Instructor: María López</p>
                        <p>Inicio: 16/02/2024</p>
                        <p>Término: 01/03/2024</p>
                    </div>
                </div>
                <p class="text-gray-500 text-center">No Iniciado</p>
            </li>
            <!-- Módulo 5 -->
            <li class="py-4 px-6 bg-gray-100 border-l-4 border-blue-500 rounded-lg flex justify-between items-center">
                <div>
                    <span class="text-sm font-medium">Módulo 5: Proyecto Final</span>
                    <div class="text-sm">
                        <p>Instructor: Carmen Sánchez</p>
                        <p>Inicio: 02/03/2024</p>
                        <p>Término: 16/03/2024</p>
                    </div>
                </div>
                <p class="text-gray-500 text-center">No Iniciado</p>
            </li>
        </ul>
    </div>
</x-app-diplomados-layout>
