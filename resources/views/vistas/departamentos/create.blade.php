<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Registrar departamento') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col  items-center pt-6  bg-gray-100">
        <form action="{{ route('departamentos.store') }}" method="POST"
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @csrf
            <!-- Nombre -->
            <div class="mt-4">
                <x-input-label for="nombre" value="Nombre" />
                <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" required/>
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>
            <!-- jefe departamento -->
            <div class="mt-4">
                <x-input-label for="jefe_departamento" value="Jefe Departamento" />
                <select name="jefe_departamento" id="jefe_departamento"
                        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach ($usuarios as $usuario)
                        <option value="{{$usuario->user->id}}">{{$usuario->user->datos_generales->nombre}} {{$usuario->user->datos_generales->apellido_paterno}} {{$usuario->user->datos_generales->apellido_materno}}</option>
                        @endforeach
                </select>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 mt-4">
                Registrar
            </button>
        </form>
    </div>

    <style>
        /* Estilo del botón igual al de Externa */
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
</x-app-layout>
