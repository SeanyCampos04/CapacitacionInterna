<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Crear Periodo') }}
        </h2>
    </x-slot>

    <style>
        /* Estilo del botón igual al de otros formularios */
        .w-full {
            width: 100%;
        }
        .bg-indigo-600 {
            background-color: #4f46e5;
        }
        .bg-indigo-600:hover {
            background-color: #4338ca;
        }
        .text-white {
            color: white;
        }
        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .rounded-md {
            border-radius: 0.375rem;
        }
        .focus\:ring-2:focus {
            --tw-ring-width: 2px;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5);
        }
        .focus\:ring-indigo-500:focus {
            --tw-ring-color: rgb(99 102 241);
        }
    </style>

    <div class="min-h-screen flex flex-col  items-center pt-6  bg-gray-100">
        <form action="{{ route('periodos.store') }}" method="POST" enctype="multipart/form-data"
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
            <div class="mt-4">
    <x-input-label for="archivo" value="Subir plantilla oficial del periodo (PDF o Imagen)" />
    <input
        type="file"
        id="archivo"
        name="archivo"
        accept=".pdf,.jpg,.jpeg,.png"
        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        onchange="previewFile()"
    />

    <!-- Vista previa del archivo seleccionado -->
    <div id="preview-container" class="mt-4 p-4 border rounded-lg bg-gray-50" style="display: none;">
        <h4 class="font-semibold text-sm text-gray-700 mb-2">Vista previa:</h4>
        <div id="preview-content" class="text-center"></div>
    </div>
</div>

<script>
function previewFile() {
    const fileInput = document.getElementById('archivo');
    const previewContainer = document.getElementById('preview-container');
    const previewContent = document.getElementById('preview-content');

    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        const fileType = file.type;
        const fileName = file.name;

        previewContainer.style.display = 'block';

        if (fileType.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContent.innerHTML = `
                    <img src="${e.target.result}" style="max-width: 300px; max-height: 400px;" class="mx-auto rounded shadow-md" />
                    <p class="text-sm text-gray-600 mt-2">Archivo seleccionado: ${fileName}</p>
                `;
            };
            reader.readAsDataURL(file);
        } else if (fileType === 'application/pdf') {
            previewContent.innerHTML = `
                <div class="p-4">
                    <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-600 mt-2">PDF seleccionado: ${fileName}</p>
                    <p class="text-xs text-gray-500">Se podrá ver después de guardar</p>
                </div>
            `;
        }
    } else {
        previewContainer.style.display = 'none';
    }
}
</script>

            <div class="mt-4">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                    {{ __('Registrar') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
