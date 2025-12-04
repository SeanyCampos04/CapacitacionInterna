<x-app-diplomados-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Módulo') }}
        </h2>
    </x-slot>

    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
            <form action="{{ route('diplomados.modulos.store') }}" method="POST">
                @csrf
                <input type="hidden" name="diplomado_id" value="{{$id}}">
                <div class="mb-4">
                    <label for="moduleNumber" class="block text-sm font-medium text-gray-700">
                        Número del Módulo:
                    </label>
                    <input
                        type="number"
                        id="moduleNumber"
                        name="moduleNumber"
                        readonly
                        value="{{$modulo_numero}}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>

                <div class="mb-4">
                    <label for="moduleName" class="block text-sm font-medium text-gray-700">
                        Nombre del Módulo:
                    </label>
                    <input
                        type="text"
                        id="moduleName"
                        name="moduleName"
                        required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>

                <div class="mb-4">
                    <label for="startDate" class="block text-sm font-medium text-gray-700">
                        Fecha de Inicio:
                    </label>
                    <input
                        type="date"
                        id="startDate"
                        name="startDate"
                        required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>

                <div class="mb-4">
                    <label for="endDate" class="block text-sm font-medium text-gray-700">
                        Fecha de Término:
                    </label>
                    <input
                        type="date"
                        id="endDate"
                        name="endDate"
                        required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>

                <div class="mb-4">
                    <label for="instructor" class="block text-sm font-medium text-gray-700">
                        Instructor:
                    </label>
                    <select
                        id="instructor"
                        name="instructor"
                        required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="" disabled selected>Seleccione un instructor</option>
                        @foreach($instructores as $instructor)
                            <option value="{{ $instructor->instructore->id }}">{{ $instructor->instructore->user->datos_generales->nombre}} {{ $instructor->instructore->user->datos_generales->apellido_paterno}} {{ $instructor->instructore->user->datos_generales->apellido_materno}}</option>
                        @endforeach
                    </select>
                </div>


                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Registrar Módulo
                </button>
            </form>
        </div>
    </div>
</x-app-diplomados-layout>
