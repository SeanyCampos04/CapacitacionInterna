<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
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

            <x-primary-button class="mt-4">Registrar</x-primary-button>
        </form>
    </div>
</x-app-layout>
