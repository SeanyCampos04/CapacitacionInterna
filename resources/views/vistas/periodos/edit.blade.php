<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Editar Periodo') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col  items-center pt-6  bg-gray-100">
        <!-- para subir plantilla-->
        <form action="{{ route('periodos.update', ['periodo' => $periodo]) }}"
      method="POST"
      enctype="multipart/form-data"
      class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @csrf
            @method('PATCH')

            <!-- Periodo -->
            <div class="mt-4">
                <x-input-label for="periodo" value="Periodo" />
                <x-text-input id="periodo" name="periodo" type="text" class="mt-1 block w-full" value="{{ $periodo->periodo }}"/>
            </div>
            <!-- año -->
            <div class="mt-4">
                <x-input-label for="anio" value="Año" />
                <x-text-input id="anio" name="anio" type="number" class="mt-1 block w-full" value="{{ $periodo->anio }}"/>
            </div>
            <!-- trimestre -->
            <div class="mt-4">
                <x-input-label for="trimestre" value="Trimestre" />
                <select name="trimestre" id="trimestre"
                    class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    @foreach([1 => 'Enero - Marzo', 2 => 'Abril - Junio', 3 => 'Julio - Septiembre', 4 => 'Octubre - Diciembre'] as $key => $label)
                        <option value="{{ $key }}" {{$periodo->trimestre == $key ? 'selected' : ''}}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Archivo -->
<div class="mt-4">
    <x-input-label for="archivo" value="Subir plantilla oficial del periodo (PDF o Imagen)" />
    <input
        type="file"
        id="archivo"
        name="archivo"
        accept=".pdf,.jpg,.jpeg,.png"
        class="block mt-1 w-full p-2 border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
</div>
@if ($periodo->archivo_fondo)
    <div class="mt-4">
        <h3 class="font-semibold text-lg">Archivo actual:</h3>
        @php
            $ruta = asset('storage/' . $periodo->archivo_fondo);
            $ext = strtolower(pathinfo($periodo->archivo_fondo, PATHINFO_EXTENSION));
        @endphp

        <div class="mt-2 p-4 border rounded-lg bg-gray-50">
            @if (in_array($ext, ['jpg','jpeg','png']))
                <div class="text-center">
                    <img src="{{ $ruta }}" style="max-width: 300px; max-height: 400px;" class="mx-auto rounded shadow-md" />
                    <p class="text-sm text-gray-600 mt-2">Vista previa de la imagen</p>
                </div>
            @elseif ($ext === 'pdf')
                <div class="text-center">
                    <iframe src="{{ $ruta }}" width="100%" height="400" class="rounded border"></iframe>
                    <p class="text-sm text-gray-600 mt-2">Vista previa del PDF</p>
                </div>
            @else
                <div class="text-center p-4">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-600 mt-2">Archivo: {{ basename($periodo->archivo_fondo) }}</p>
                    <a href="{{ $ruta }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Ver archivo</a>
                </div>
            @endif
        </div>
    </div>
@endif



            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Actualizar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
