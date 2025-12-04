<nav x-data="{ open: false }" style="background:rgb(27,57,106); border-bottom: 1px solid #535353">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('inicio') }}">
                        <img style="height: 55px" src="{{ asset('images/logo.png') }}" alt="logo">
                    </a>
                </div>

                <!-- Navigation Links -->

                <!-- Selector de Módulos - Oculto para usuarios tipo Otro sin rol -->
                @php
                    $user_tipo = auth()->user()->tipo;
                    $user_roles_count = auth()->user()->roles->count();
                    $hide_modules = ($user_tipo == 3 && $user_roles_count == 0); // Tipo Otro sin roles
                @endphp

                @unless($hide_modules)
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
                            <x-dropdown-link :href="route('externa.index')">
                                {{ __('Capacitación Externa') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('diplomados.index')">
                                {{ __('Diplomados') }}
                            </x-dropdown-link>

                        </x-slot>
                    </x-dropdown>
                </div>
                @endunless

                <!-- Inicio -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('inicio')" :active="request()->routeIs('dashboard')">
                        {{ __('Inicio') }}
                    </x-nav-link>
                </div>

                @if (in_array('admin', $user_roles) or
                        in_array('CAD', $user_roles) or
                        in_array('Jefe Departamento', $user_roles) or
                        in_array('Subdirector Academico', $user_roles))
                    <!-- Usuarios -->
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="top" width="20">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-1 py-4 text-sm leading-5 font-medium rounded-md text-gray-100 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>Usuarios</div>

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
                                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <x-dropdown-link :href="route('register_user')">
                                        {{ __('Registrar') }}
                                    </x-dropdown-link>
                                @endif

                                <x-dropdown-link :href="route('usuarios.index')">
                                    {{ __('Usuarios') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    @if (in_array('admin', $user_roles) or
                            in_array('CAD', $user_roles) or
                            in_array('Jefe Departamento', $user_roles) or
                            in_array('Subdirector Academico', $user_roles))
                        <!-- Departamentos -->
                        <div class="hidden sm:flex sm:items-center sm:ms-2">
                            <x-dropdown align="top" width="">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-1 py-4 text-sm leading-5 font-medium rounded-md text-gray-100 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>Departamentos</div>

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
                                    @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                        <x-dropdown-link :href="route('departamentos.create')">
                                            {{ __('Registrar') }}
                                        </x-dropdown-link>
                                    @endif
                                    <x-dropdown-link :href="route('departamentos.index')">
                                        {{ __('Ver') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                @endif

                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                    <!-- DNC -->
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="top" width="20">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-1 py-4 text-sm leading-5 font-medium rounded-md text-gray-100 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>DNC</div>

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
                                <x-dropdown-link :href="route('admin_solicitarcursos.index')">
                                    {{ __('Solicitudes') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Cursos -->
                <div class="hidden sm:flex sm:items-center sm:ms-2">
                    <x-dropdown align="top" width="20">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-1 py-4 text-sm leading-5 font-medium rounded-md text-gray-100 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>Cursos</div>
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
                            <!--Cursos para cualquier usuario autenticado-->
                             @if (Auth::check())

                                @if ($estatus_usuario == 1)
                                    <x-dropdown-link :href="route('cursos_disponibles.index')">
                                        {{ __('Disponibles') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('cursos_cursando.index')">
                                        {{ __('Cursando') }}
                                    </x-dropdown-link>
                                @endif
                                <x-dropdown-link :href="route('cursos_terminados.index')">
                                    {{ __('Terminados') }}
                                </x-dropdown-link>
                            @endif
                            @if (in_array('Instructor', $user_roles))
                                <x-dropdown-link :href="route('instructor.index')">
                                    {{ __('Instructor') }}
                                </x-dropdown-link>
                            @endif
                            @if (in_array('Jefe Departamento', $user_roles))
                                <x-dropdown-link :href="route('solicitarcursos.create')">
                                    {{ __('Solicitar Curso') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('jefe_solicitarcursos.index')">
                                    {{ __('Mis solicitudes') }}
                                </x-dropdown-link>
                            @endif
                            @if (in_array('admin', $user_roles) or
                                    in_array('CAD', $user_roles) or
                                    in_array('Jefe Departamento', $user_roles) or
                                    in_array('Subdirector Academico', $user_roles))
                                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <x-dropdown-link :href="route('cursos_estadisticas.index')">
                                        {{ __('Estadisticas') }}
                                    </x-dropdown-link>
                                    <!-- <x-dropdown-link :href="route('cursos.create', ['id' => 0])">
                                        {{ __('Registrar curso') }}
                                    </x-dropdown-link> -->
                                @endif
                                <x-dropdown-link :href="route('cursos.index')">
                                    {{ __('Ver cursos') }}
                                </x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>

                @if (in_array('admin', $user_roles) or
                        in_array('CAD', $user_roles) or
                        in_array('Jefe Departamento', $user_roles) or
                        in_array('Subdirector Academico', $user_roles))
                    <!-- Periodos -->
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="top" width="">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-1 py-4 text-sm leading-5 font-medium rounded-md text-gray-100 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>Periodos</div>

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
                                @if (in_array('admin', $user_roles) or in_array('CAD', $user_roles))
                                    <x-dropdown-link :href="route('periodos.create')">
                                        {{ __('Registrar') }}
                                    </x-dropdown-link>
                                @endif
                                <x-dropdown-link :href="route('periodos.index')">
                                    {{ __('Ver') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif
            </div>
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-1 py-4 text-sm leading-5 font-medium rounded-md text-gray-100 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
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
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('inicio')" :active="request()->routeIs('dashboard')">
                {{ __('Inicio') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('register_user')" :active="request()->routeIs('dashboard')">
                {{ __('Registrar usuario') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('jefe_solicitarcursos.index')" :active="request()->routeIs('dashboard')">
                {{ __('Solicitudes de curso') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('docente_cursos.index')" :active="request()->routeIs('dashboard')">
                {{ __('Ver cursos') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('periodos.create')" :active="request()->routeIs('dashboard')">
                {{ __('Registrar periodo') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('periodos.index')" :active="request()->routeIs('dashboard')">
                {{ __('Ver periodos') }}
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
