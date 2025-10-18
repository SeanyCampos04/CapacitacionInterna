<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-2xl text-white">
                {{ __('Bienvenido') }}
                @if(Auth::user() && Auth::user()->datos_generales)
                    {{ Auth::user()->datos_generales->nombre }} {{ Auth::user()->datos_generales->apellido_paterno }}
                @else
                    {{ Auth::user()->name }}
                @endif
            </h2>
            <span class="font-semibold text-lg text-white">
                Interna
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">{{ __("Menú de Opciones") }}</h3>
                    <p class="text-gray-600 mb-6">Selecciona una opción para gestionar las capacitaciones internas.</p>

                    <!-- Agregar Bootstrap CSS y Font Awesome -->
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

                    <style>
                        .card {
                            transition: transform 0.2s;
                        }
                        .card:hover {
                            transform: translateY(-5px);
                        }
                        a {
                            text-decoration: none !important;
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
                        @endphp                        <!-- USUARIOS - Admin, CAD, Jefe Departamento, Subdirector -->
                        @if($is_admin || $is_cad || $is_jefe_departamento || $is_subdirector)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-users fa-3x text-primary"></i>
                                        </div>
                                        <h5 class="card-title">Usuarios</h5>
                                        <p class="card-text">Gestión de usuarios del sistema</p>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Opciones
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($is_admin || $is_cad)
                                                    <li><a class="dropdown-item" href="{{ route('register_user') }}">Registrar</a></li>
                                                @endif
                                                <li><a class="dropdown-item" href="{{ route('usuarios.index') }}">Ver Usuarios</a></li>
                                            </ul>
                                        </div>
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
                                        <div class="dropdown">
                                            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Opciones
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($is_admin || $is_cad)
                                                    <li><a class="dropdown-item" href="{{ route('departamentos.create') }}">Registrar</a></li>
                                                @endif
                                                <li><a class="dropdown-item" href="{{ route('departamentos.index') }}">Ver Departamentos</a></li>
                                            </ul>
                                        </div>
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

                        <!-- CURSOS - Todos los roles tienen acceso -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-graduation-cap fa-3x text-info"></i>
                                    </div>
                                    <h5 class="card-title">Cursos</h5>
                                    <p class="card-text">Gestión y consulta de cursos</p>
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Opciones
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($is_admin || $is_cad || $is_jefe_departamento || $is_subdirector)
                                                <li><a class="dropdown-item" href="{{ route('cursos_disponibles.index') }}">Disponibles</a></li>
                                                <li><a class="dropdown-item" href="{{ route('cursos_cursando.index') }}">Cursando</a></li>
                                                <li><a class="dropdown-item" href="{{ route('cursos_terminados.index') }}">Terminados</a></li>
                                                @if($is_jefe_departamento)
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="{{ route('solicitarcursos.create') }}">Solicitar Curso</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('jefe_solicitarcursos.index') }}">Mis Solicitudes</a></li>
                                                @endif
                                                @if($is_admin || $is_cad)
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="{{ route('cursos_estadisticas.index') }}">Estadísticas</a></li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="{{ route('cursos.index') }}">Ver Cursos</a></li>
                                            @endif
                                            @if($is_instructor)
                                                <li><a class="dropdown-item" href="{{ route('instructor.index') }}">Instructor</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Opciones
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($is_admin || $is_cad)
                                                    <li><a class="dropdown-item" href="{{ route('periodos.create') }}">Registrar</a></li>
                                                @endif
                                                <li><a class="dropdown-item" href="{{ route('periodos.index') }}">Ver Períodos</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

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

