<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Registrar periodo') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col  items-center pt-6  bg-gray-100">
        <form action="{{ route('periodos.store') }}" method="POST"
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @csrf
            <!-- Periodo -->
            <div class="mt-4">
                <x-input-label for="periodo" value="Periodo" />
                <x-text-input id="periodo" name="periodo" type="text" class="mt-1 block w-full" />
                @error('periodo')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <!-- Año -->
            <div class="mt-4">
                <x-input-label for="anio" value="Año" />
                <x-text-input id="anio" name="anio" type="number" class="mt-1 block w-full" />
                @error('anio')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <!-- Trimestre -->
            <div class="mt-4">
                <x-input-label for="trimestre" value="Trimestre" />
                <select name="trimestre" id="trimestre"
                        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="1">Enero - Marzo</option>
                        <option value="2">Abril - Junio</option>
                        <option value="3">Julio - Septiembre</option>
                        <option value="4">Octubre - Diciembre</option>
                </select>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Registrar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
