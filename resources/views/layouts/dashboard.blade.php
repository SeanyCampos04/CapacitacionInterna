<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bienvenido') }}
                @if(Auth::user() && Auth::user()->datos_generales)
                    {{ Auth::user()->datos_generales->nombre }} {{ Auth::user()->datos_generales->apellido_paterno }}
                @else
                    {{ Auth::user()->name }}
                @endif
            </h2>
            <span class="font-semibold text-lg text-green-600">
                @php
                    $user_tipo = auth()->user()->tipo;
                    $user_roles_count = auth()->user()->roles->count();
                @endphp
                @if($user_tipo == 3 && $user_roles_count == 0)
                    Capacitación
                @else
                    Capacitación Interna
                @endif
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 bg-white">
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ __("Menú de Opciones") }}</h3>
                    <p class="text-gray-600 mb-6">Selecciona una opción para gestionar las capacitaciones internas.</p>

                    <!-- Agregar Bootstrap CSS y Font Awesome -->
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

                        /* Botón verde para instructor */
                        .btn-green {
                            background: linear-gradient(45deg, #10b981, #059669);
                            color: white;
                            border: none;
                            padding: 10px 20px;
                            border-radius: 6px;
                            font-weight: 500;
                            transition: all 0.3s ease;
                        }

                        .btn-green:hover {
                            background: linear-gradient(45deg, #059669, #047857);
                            color: white;
                            transform: translateY(-1px);
                            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
                        }

                        /* Icono verde para instructor */
                        .text-green {
                            color: #10b981 !important;
                        }

                        /* Hacer que los dropdowns se abran hacia arriba */
                        .dropup .dropdown-menu {
                            top: auto !important;
                            bottom: 100% !important;
                            margin-bottom: 0.125rem !important;
                            transform: translate3d(0px, -2px, 0px) !important;
                        }
                    </style>

                    <!-- Tarjetas de acciones rápidas -->
                    <div class="row">
                        @php
                            $user = auth()->user();
                            $user_role_ids = [];

                            // Obtener IDs de roles del usuario usando consulta directa
                            if ($user) {
                                $user_role_ids = \Illuminate\Support\Facades\DB::table('user_roles')
                                    ->where('user_id', $user->id)
                                    ->pluck('role_id')
                                    ->toArray();
                            }

                            // Definir roles por ID
                            $is_admin = in_array(1, $user_role_ids); // admin
                            $is_jefe_departamento = in_array(2, $user_role_ids); // Jefe Departamento
                            $is_subdirector = in_array(3, $user_role_ids); // Subdirector Academico
                            $is_cad = in_array(4, $user_role_ids); // CAD
                            $is_instructor = in_array(5, $user_role_ids); // Instructor

                            // Usuario sin rol específico (usuario regular)
                            $is_regular_user = empty($user_role_ids);
                        @endphp

                        <!-- PARA USUARIOS SIN ROL ESPECÍFICO - Solo mostrar opciones de cursos -->
                        @if($is_regular_user)
                            <!-- CURSOS DISPONIBLES -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-list-alt fa-3x text-primary"></i>
                                        </div>
                                        <h5 class="card-title">Disponibles</h5>
                                        <p class="card-text">Cursos disponibles para inscripción</p>
                                        <a href="{{ route('cursos_disponibles.index') }}" class="btn btn-primary">
                                            Ver Disponibles
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- CURSOS CURSANDO -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-play-circle fa-3x text-warning"></i>
                                        </div>
                                        <h5 class="card-title">Cursando</h5>
                                        <p class="card-text">Los cursos que está cursando actualmente</p>
                                        <a href="{{ route('cursos_cursando.index') }}" class="btn btn-warning">
                                            Ver Cursando
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- CURSOS TERMINADOS -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-check-circle fa-3x text-success"></i>
                                        </div>
                                        <h5 class="card-title">Terminados</h5>
                                        <p class="card-text">Cursos ya finalizados por el usuario</p>
                                        <a href="{{ route('cursos_terminados.index') }}" class="btn btn-success">
                                            Ver Terminados
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- PARA INSTRUCTORES - Solo mostrar opción de Instructor -->
                        @if($is_instructor)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-chalkboard-teacher fa-3x text-green"></i>
                                        </div>
                                        <h5 class="card-title">Instructor</h5>
                                        <p class="card-text">Gestión de cursos como instructor</p>
                                        <a href="{{ route('instructor.index') }}" class="btn btn-green">
                                            Ver Cursos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- PARA USUARIOS CON ROLES ESPECÍFICOS (EXCEPTO INSTRUCTOR) -->
                        @if(!$is_regular_user && !$is_instructor)
                            <!-- USUARIOS - Admin, CAD, Jefe Departamento, Subdirector -->
                        @if($is_admin || $is_cad || $is_jefe_departamento || $is_subdirector)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-users fa-3x text-primary"></i>
                                        </div>
                                        <h5 class="card-title">Usuarios</h5>
                                        <p class="card-text">Gestión de usuarios del sistema</p>
                                        <a href="{{ route('usuarios.index') }}" class="btn btn-primary">
                                            Ver Usuarios
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- DEPARTAMENTOS - Admin, CAD, Jefe Departamento, Subdirector -->
                        @if($is_admin || $is_cad || $is_jefe_departamento || $is_subdirector)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-building fa-3x text-success"></i>
                                        </div>
                                        <h5 class="card-title">Departamentos</h5>
                                        <p class="card-text">Gestión de departamentos</p>
                                        <a href="{{ route('departamentos.index') }}" class="btn btn-success">
                                            Ver Departamentos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- DNC - Solo Admin y CAD -->
                        @if($is_admin || $is_cad)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-clipboard-list fa-3x text-warning"></i>
                                        </div>
                                        <h5 class="card-title">DNC</h5>
                                        <p class="card-text">Detección de Necesidades de Capacitación</p>
                                        <a href="{{ route('admin_solicitarcursos.index') }}" class="btn btn-warning">
                                            Solicitudes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- CURSOS - Admin, CAD, Jefe Departamento, Subdirector (NO Instructores) -->
                        @if($is_admin || $is_cad || $is_jefe_departamento || $is_subdirector)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-graduation-cap fa-3x text-info"></i>
                                        </div>
                                        <h5 class="card-title">Cursos</h5>
                                        <p class="card-text">Gestión y consulta de cursos</p>
                                        <a href="{{ route('cursos.index') }}" class="btn btn-info">
                                            Ver Cursos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- PERIODOS - Admin, CAD, Jefe Departamento, Subdirector -->
                        @if($is_admin || $is_cad || $is_jefe_departamento || $is_subdirector)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-calendar-alt fa-3x text-secondary"></i>
                                        </div>
                                        <h5 class="card-title">Períodos</h5>
                                        <p class="card-text">Gestión de períodos académicos</p>
                                        <a href="{{ route('periodos.index') }}" class="btn btn-secondary">
                                            Ver Períodos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @endif <!-- Fin del condicional para usuarios con roles específicos -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agregar Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <x-footer>

    </x-footer>

</x-app-layout>

