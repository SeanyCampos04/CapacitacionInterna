<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Editar usuario: ') }} {{ $usuario->datos_generales->nombre }} {{ $usuario->datos_generales->apellido_paterno }} {{ $usuario->datos_generales->apellido_materno }}
        </h2>
    </x-slot>

    <style>
        /* Estilo del botón igual al de registrar usuario */
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

    <div class="min-h-screen flex flex-col items-center pt-6 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('user.update', $usuario->id) }}">
                @csrf
                @method('PUT') {{-- Método PUT para la actualización --}}

                <div>
                    <x-input-label for="nombre" :value="__('Nombre')" />
                    <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre"
                        :value="old('nombre', $usuario->datos_generales->nombre)" required autofocus autocomplete="nombre" />
                    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="apellidoP" :value="__('Apellido paterno')" />
                    <x-text-input id="apellidoP" class="block mt-1 w-full" type="text" name="apellidoP"
                        :value="old('apellidoP', $usuario->datos_generales->apellido_paterno)" autofocus autocomplete="apellidoP" />
                    <x-input-error :messages="$errors->get('apellidoP')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="apellidoM" :value="__('Apellido materno')" />
                    <x-text-input id="apellidoM" class="block mt-1 w-full" type="text" name="apellidoM"
                        :value="old('apellidoM', $usuario->datos_generales->apellido_materno)" autofocus autocomplete="apellidoM" />
                    <x-input-error :messages="$errors->get('apellidoM')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="departamento" :value="__('Departamento')" />
                    <select name="departamento" id="departamento"
                        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento->id }}"
                                {{ $departamento->id ==  $usuario->datos_generales->departamento->id ? 'selected' : '' }}>
                                {{ $departamento->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('departamento')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label :value="__('Tipo de usuario')" />
                    <div class="flex space-x-4 mt-2">
                        <label class="flex items-center">
                            <input type="radio" name="tipo_usuario" value="1"
                                class="form-radio h-4 w-4 text-indigo-600"
                                {{ old('tipo_usuario', $usuario->tipo) == 1 ? 'checked' : '' }}  onchange="toggleRoles()" />
                            <span class="ml-2 text-gray-700">Docente</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="tipo_usuario" value="2"
                                class="form-radio h-4 w-4 text-indigo-600"
                                {{ old('tipo_usuario', $usuario->tipo) == 2 ? 'checked' : '' }}  onchange="toggleRoles()" />
                            <span class="ml-2 text-gray-700">Administrativo</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="tipo_usuario" value="3"
                                class="form-radio h-4 w-4 text-indigo-600"
                                {{ old('tipo_usuario', $usuario->tipo) == 3 ? 'checked' : '' }}  onchange="toggleRoles()" />
                            <span class="ml-2 text-gray-700">Otro</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('tipo_usuario')" class="mt-2" />
                </div>

                <div class="mt-4" id="roles-container">
                    <x-input-label for="roles" :value="__('Rol')" />
                    <div class="mt-2">
                        @foreach ($roles as $role)
                            @if ($role->nombre != 'admin')
                                <label class="block mb-2 role-option">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        class="form-checkbox h-4 w-4 text-indigo-600"
                                        {{ in_array($role->id, $usuario->roles->pluck('id')->toArray()) ? 'checked' : '' }} />
                                    <span class="ml-2 text-gray-700">{{ $role->nombre }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
                    <x-text-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $usuario->datos_generales->fecha_nacimiento) }}" />
                    <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="curp" :value="__('CURP')" />
                    <x-text-input id="curp" class="block mt-1 w-full" type="text" name="curp" value="{{ old('curp', $usuario->datos_generales->curp) }}" />
                    <x-input-error :messages="$errors->get('curp')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="rfc" :value="__('RFC')" />
                    <x-text-input id="rfc" class="block mt-1 w-full" type="text" name="rfc" value="{{ old('rfc', $usuario->datos_generales->rfc) }}" />
                    <x-input-error :messages="$errors->get('rfc')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="telefono" :value="__('Teléfono')" />
                    <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" value="{{ old('telefono', $usuario->datos_generales->telefono) }}" />
                    <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="sexo" :value="__('Sexo')" />
                    <select id="sexo" name="sexo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="masculino" {{ old('sexo', $usuario->datos_generales->sexo) == 'masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="femenino" {{ old('sexo', $usuario->datos_generales->sexo) == 'femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="otro" {{ old('sexo', $usuario->datos_generales->sexo) == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    <x-input-error :messages="$errors->get('sexo')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" :value="__('Correo')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email', $usuario->email)" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                        {{ __('Actualizar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función para controlar la visibilidad de los roles y mantener los seleccionados
        function toggleRoles() {
            const tipoUsuario = document.querySelector('input[name="tipo_usuario"]:checked').value;
            const rolesContainer = document.getElementById('roles-container');
            const roleOptions = rolesContainer.querySelectorAll('.role-option');

            // Obtenemos los roles seleccionados antes de ocultarlos
            const selectedRoles = [];
            roleOptions.forEach(option => {
                const checkbox = option.querySelector('input');
                if (checkbox.checked) {
                    selectedRoles.push(checkbox.value);
                }
                checkbox.checked = false; // Desmarcar todos los checkboxes
                option.style.display = 'none'; // Ocultar todas las opciones
            });

            // Mostrar y mantener seleccionados los roles válidos para el tipo de usuario
            roleOptions.forEach(option => {
                const checkbox = option.querySelector('input');
                const roleId = checkbox.value;

                if (tipoUsuario == '1') { // Docente
                    option.style.display = 'block';
                    if (selectedRoles.includes(roleId)) {
                        checkbox.checked = true; // Mantener seleccionado si era previamente seleccionado
                    }
                } else if (tipoUsuario == '2') { // Administrativo
                    // Mostrar solo roles válidos para "Administrativo"
                    if (roleId == '2' || roleId == '5') { // Jefe de departamento e Instructor
                        option.style.display = 'block';
                        if (selectedRoles.includes(roleId)) {
                            checkbox.checked = true; // Mantener seleccionado si era previamente seleccionado
                        }
                    }
                } else if (tipoUsuario == '3') { // Otro
                    // Mostrar solo el rol "Instructor" para "Otro"
                    if (roleId == '5') { // Instructor
                        option.style.display = 'block';
                        if (selectedRoles.includes(roleId)) {
                            checkbox.checked = true; // Mantener seleccionado si era previamente seleccionado
                        }
                    }
                }
            });
        }

        // Llamar a la función para configurar los roles correctamente cuando la página carga
        window.onload = function() {
            toggleRoles();
        };
    </script>

</x-app-layout>
