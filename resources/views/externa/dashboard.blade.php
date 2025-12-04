<x-app-externa-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bienvenido') }}
                @if(Auth::user() && Auth::user()->datos_generales)
                    {{ Auth::user()->datos_generales->nombre }} {{ Auth::user()->datos_generales->apellido_paterno }}
                @else
                    {{ Auth::user()->name }}
                @endif
            </h2>
            <span class="font-semibold text-lg text-blue-600 dark:text-blue-400">
                Capacitación Externa
            </span>
        </div>
    </x-slot>

    <!-- Agregar Bootstrap CSS y Font Awesome -->
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            /* Quitar el subrayado de todos los enlaces */
            a {
                text-decoration: none !important;
            }
            .card {
                transition: transform 0.2s;
            }
            .card:hover {
                transform: translateY(-5px);
            }

            /* Botón azul para instructor */
            .btn-blue {
                background: linear-gradient(45deg, #3b82f6, #2563eb);
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn-blue:hover {
                background: linear-gradient(45deg, #2563eb, #1d4ed8);
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            }

            /* Hacer que los dropdowns se abran hacia arriba */
            .dropup .dropdown-menu {
                top: auto !important;
                bottom: 100% !important;
                margin-bottom: 0.125rem !important;
                transform: translate3d(0px, -2px, 0px) !important;
            }
        </style>
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">{{ __("Menú de Opciones") }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Selecciona una de las opciones de funcionalidad del módulo de capacitación externa</p>

                    <!-- Tarjetas de acciones rápidas -->
                    <div class="row">
                        @php
                            // Obtener roles usando consulta directa a la base de datos (misma lógica que navegación)
                            $user_id = auth()->user()->id;
                            $user_role_ids = \Illuminate\Support\Facades\DB::table('user_roles')->where('user_id', $user_id)->pluck('role_id')->toArray();

                            // Obtener nombres de roles
                            $user_roles = [];
                            if (!empty($user_role_ids)) {
                                $user_roles = \Illuminate\Support\Facades\DB::table('roles')->whereIn('id', $user_role_ids)->pluck('nombre')->toArray();
                            }

                            // Definir roles por ID y nombre
                            $is_instructor = in_array(5, $user_role_ids) || in_array('Instructor', $user_roles);
                            $is_admin = in_array(1, $user_role_ids) || in_array('admin', $user_roles);
                            $is_cad = in_array(4, $user_role_ids) || in_array('CAD', $user_roles);
                            $is_jefe_departamento = in_array(2, $user_role_ids) || in_array('Jefe Departamento', $user_roles);
                            $is_subdirector = in_array(3, $user_role_ids) || in_array('Subdirector Academico', $user_roles);
                        @endphp                        <!-- Tarjeta Registrar - Para todos los usuarios (incluyendo participantes sin rol) -->
                        @if ($is_instructor or $is_admin or $is_cad or $is_jefe_departamento or $is_subdirector or
                             in_array('Instructor', $user_roles) or
                             in_array('Docente', $user_roles) or
                             in_array('admin', $user_roles) or
                             in_array('CAD', $user_roles) or
                             in_array('Jefe Departamento', $user_roles) or
                             in_array('Subdirector Academico', $user_roles) or
                             empty($user_roles))
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-plus-circle fa-3x text-success"></i>
                                        </div>
                                        <h5 class="card-title">Registrar Capacitación</h5>
                                        <p class="card-text">Registra una nueva capacitación externa</p>
                                        <a href="{{ route('externa.formulario') }}" class="btn btn-success">
                                            Ir al Formulario
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Tarjeta Ver Capacitaciones - Visible solo para usuarios con roles (EXCEPTO Instructores y participantes sin rol) -->
                        @if (!$is_instructor && !empty($user_roles))
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-list fa-3x text-primary"></i>
                                        </div>
                                        <h5 class="card-title">Ver Capacitaciones</h5>
                                        <p class="card-text">Consulta todas las capacitaciones registradas</p>
                                        <a href="{{ route('externa.datos') }}" class="btn btn-primary">
                                            Ver Lista
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Tarjeta Mis Capacitaciones - Para todos los usuarios (incluyendo participantes sin rol) -->
                        @if ($is_instructor or $is_admin or $is_cad or $is_jefe_departamento or $is_subdirector or
                             in_array('Instructor', $user_roles) or
                             in_array('Docente', $user_roles) or
                             in_array('admin', $user_roles) or
                             in_array('CAD', $user_roles) or
                             in_array('Jefe Departamento', $user_roles) or
                             in_array('Subdirector Academico', $user_roles) or
                             empty($user_roles))
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-user fa-3x text-info"></i>
                                        </div>
                                        <h5 class="card-title">Mis Capacitaciones</h5>
                                        <p class="card-text">Consulta tus capacitaciones registradas</p>
                                        <a href="{{ route('externa.mis_capacitaciones') }}" class="btn btn-info">
                                            Ver Capacitaciones
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal centrado verticalmente -->
            <div class="modal" id="successModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Éxito</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            @if(session('success'))
                                {{ session('success') }}
                            @else
                                Modal body..
                            @endif
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <x-primary-button type="button" class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-0" data-bs-dismiss="modal">
                                Cerrar
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensaje de error -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <!-- Agregar Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mostrar el modal automáticamente si hay un mensaje de éxito -->
    @if(session('success'))
        <script>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        </script>
    @endif

    <!-- Footer solo para la vista de inicio -->
    <x-footer></x-footer>
</x-app-externa-layout>
