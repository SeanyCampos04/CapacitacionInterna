@php
    use Illuminate\Support\Facades\DB;

    // Obtener roles usando consulta directa a la base de datos
    $user_id = auth()->user()->id;
    $user_role_ids = DB::table('user_roles')->where('user_id', $user_id)->pluck('role_id')->toArray();

    // Obtener nombres de roles
    $user_roles = [];
    if (!empty($user_role_ids)) {
        $user_roles = DB::table('roles')->whereIn('id', $user_role_ids)->pluck('nombre')->toArray();
    }

    // Definir roles por ID y nombre
    $is_instructor = in_array(5, $user_role_ids) || in_array('Instructor', $user_roles);
    $is_admin = in_array(1, $user_role_ids) || in_array('admin', $user_roles);
    $is_cad = in_array(4, $user_role_ids) || in_array('CAD', $user_roles);
    $is_jefe_departamento = in_array(2, $user_role_ids) || in_array('Jefe Departamento', $user_roles);
    $is_subdirector = in_array(3, $user_role_ids) || in_array('Subdirector Academico', $user_roles);

    $tipo_usuario = auth()->user()->tipo_usuario;
@endphp

<nav x-data="{ open: false }" style="background:rgb(27,57,106); border-bottom: 1px solid #535353">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('externa.index') }}">
                        <img style="height: 55px" src="{{ asset('images/logo.png') }}" alt="logo">
                    </a>
                </div>

                <!-- Navigation Links -->

                <!-- Selector de Módulos -->
                <div class="hidden sm:flex sm:items-center sm:ms-2">
                    <x-dropdown align="top" width="20">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-1 py-4 text-sm leading-5 font-medium rounded-md text-gray-100 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>Módulos</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('inicio')">
                                {{ __('Capacitación Interna') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('externa.index')" :active="request()->routeIs('externa.*')">
                                {{ __('Capacitación Externa') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('diplomados.index')">
                                {{ __('Diplomados') }}
                            </x-dropdown-link>
                            <!-- <x-dropdown-link href="#" onclick="alert('Módulo en desarrollo')">
                                {{ __('Estadías') }}
                            </x-dropdown-link> -->
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Inicio -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('externa.index')" :active="request()->routeIs('externa.index')">
                        {{ __('Inicio') }}
                    </x-nav-link>
                </div>

                <!-- Visualizar Capacitaciones - Para Admin, CAD, Jefe Departamento y Subdirector -->
                @if ($is_admin or $is_cad or $is_jefe_departamento or $is_subdirector)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                        <x-nav-link :href="route('externa.datos')" :active="request()->routeIs('externa.datos')">
                            {{ __('Visualizar') }}
                        </x-nav-link>
                    </div>
                @endif

                <!-- Registrar Capacitación - Visible para todos los usuarios autenticados -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                    <x-nav-link :href="route('externa.formulario')" :active="request()->routeIs('externa.formulario')">
                        {{ __('Registrar') }}
                    </x-nav-link>
                </div>

                <!-- Mis Capacitaciones - Visible para todos los usuarios autenticados -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                    <x-nav-link :href="route('externa.mis_capacitaciones')" :active="request()->routeIs('externa.mis_capacitaciones')">
                        {{ __('Mis Capacitaciones') }}
                    </x-nav-link>
                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-1 py-4 text-sm leading-5 font-medium rounded-md text-gray-100 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>
                                @if(Auth::user() && Auth::user()->datos_generales)
                                    {{ Auth::user()->datos_generales->nombre }}
                                    {{ Auth::user()->datos_generales->apellido_paterno }}
                                    {{ Auth::user()->datos_generales->apellido_materno }}
                                @else
                                    {{ Auth::user()->name ?? Auth::user()->nombre ?? '' }}
                                @endif
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Finalizar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('externa.index')" :active="request()->routeIs('externa.index')">
                {{ __('Inicio') }}
            </x-responsive-nav-link>

            @if ($is_admin or $is_cad or $is_jefe_departamento or $is_subdirector)
                <x-responsive-nav-link :href="route('externa.datos')" :active="request()->routeIs('externa.datos')">
                    {{ __('Visualizar') }}
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('externa.formulario')" :active="request()->routeIs('externa.formulario')">
                {{ __('Registrar') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('externa.mis_capacitaciones')" :active="request()->routeIs('externa.mis_capacitaciones')">
                {{ __('Mis Capacitaciones') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
