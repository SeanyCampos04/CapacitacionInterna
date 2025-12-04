<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Registrar usuario') }}
        </h2>
    </x-slot>

    <style>
        /* Estilo del botón igual al de Diplomados */
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
            <form method="POST" action="{{ route('store_user') }}">
                @csrf

                <div>
                    <x-input-label for="nombre" :value="__('Nombre')" />
                    <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')"
                        required autofocus autocomplete="nombre" />
                    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="apellidoP" :value="__('Apellido paterno')" />
                    <x-text-input id="apellidoP" class="block mt-1 w-full" type="text" name="apellidoP"
                        :value="old('apellidoP')" autofocus autocomplete="apellidoP" />
                    <x-input-error :messages="$errors->get('apellidoP')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="apellidoM" :value="__('Apellido materno')" />
                    <x-text-input id="apellidoM" class="block mt-1 w-full" type="text" name="apellidoM"
                        :value="old('apellidoM')" autofocus autocomplete="apellidoM" />
                    <x-input-error :messages="$errors->get('apellidoM')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="departamento" :value="__('Departamento')" />
                    <select name="departamento" id="departamento"
                        class="block mt-1 w-full border-black focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <x-input-label :value="__('Tipo de usuario')" />
                    <div class="flex space-x-4 mt-2">
                        <label class="flex items-center">
                            <input type="radio" name="tipo_usuario" value="1"
                                class="form-radio h-4 w-4 text-indigo-600" onchange="toggleRoles()" />
                            <span class="ml-2 text-gray-700">Docente</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="tipo_usuario" value="2"
                                class="form-radio h-4 w-4 text-indigo-600" onchange="toggleRoles()" />
                            <span class="ml-2 text-gray-700">Administrativo</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="tipo_usuario" value="3"
                                class="form-radio h-4 w-4 text-indigo-600" onchange="toggleRoles()" />
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
                                        class="form-checkbox h-4 w-4 text-indigo-600" />
                                    <span class="ml-2 text-gray-700">{{ $role->nombre }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="plantel" :value="__('Plantel / Organización')" />
                    <x-text-input id="plantel" class="block mt-1 w-full" type="text" name="plantel"
                        :value="old('plantel')" required />
                    <x-input-error :messages="$errors->get('plantel')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="horas_nombramiento" :value="__('Horas de nombramiento')" />
                    <x-text-input id="horas_nombramiento" class="block mt-1 w-full" type="number"
                        name="horas_nombramiento" :value="old('horas_nombramiento')" required />
                    <x-input-error :messages="$errors->get('horas_nombramiento')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="puesto" :value="__('Puesto')" />
                    <x-text-input id="puesto" class="block mt-1 w-full" type="text" name="puesto"
                        :value="old('puesto')" required />
                    <x-input-error :messages="$errors->get('puesto')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" :value="__('Correo')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                        {{ __('Registrar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función para controlar la visibilidad de los roles
        function toggleRoles() {
            const tipoUsuario = document.querySelector('input[name="tipo_usuario"]:checked').value;
            const rolesContainer = document.getElementById('roles-container');
            const roleOptions = rolesContainer.querySelectorAll('.role-option');

            // Primero, desmarcar todos los checkboxes
            roleOptions.forEach(option => {
                const checkbox = option.querySelector('input');
                checkbox.checked = false; // Desmarcar checkbox
                option.style.display = 'none'; // Ocultar opción
            });

            // Mostrar roles dependiendo del tipo de usuario
            if (tipoUsuario == '1') { // Docente
                roleOptions.forEach(option => option.style.display = 'block');
            } else if (tipoUsuario == '2') { // Administrativo
                roleOptions.forEach(option => {
                    const roleId = option.querySelector('input').value;
                    if (roleId == '2' || roleId == '5') { // Jefe de departamento e Instructor
                        option.style.display = 'block';
                    }
                });
            } else if (tipoUsuario == '3') { // Otro
                roleOptions.forEach(option => {
                    const roleId = option.querySelector('input').value;
                    if (roleId == '5') { // Instructor
                        option.style.display = 'block';
                    }
                });
            }
        }

        // Ejecutar la función al cargar la página
        window.onload = toggleRoles;
    </script>

</x-app-layout>
