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
                            $user_roles = [];

                            // Obtener roles del usuario
                            if ($user && $user->user_roles) {
                                $user_roles = $user->user_roles->pluck('role.nombre')->toArray();
                            }

                            $is_admin = in_array('admin', $user_roles);
                            $is_cad = in_array('CAD', $user_roles);
                            $is_jefe_departamento = in_array('Jefe Departamento', $user_roles);
                            $is_subdirector = in_array('Subdirector Academico', $user_roles);
                            $is_docente = in_array('Docente', $user_roles);
                        @endphp

                        <!-- CURSOS - Visible para todos los usuarios autenticados -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Cursos</h5>
                                    <p class="card-text">Gestiona y consulta los cursos disponibles</p>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Opciones
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('cursos_disponibles.index') }}">Cursos Disponibles</a></li>
                                            <li><a class="dropdown-item" href="{{ route('cursos_cursando.index') }}">Cursos Cursando</a></li>
                                            <li><a class="dropdown-item" href="{{ route('cursos_terminados.index') }}">Cursos Terminados</a></li>
                                            @if($is_admin || $is_cad || $is_jefe_departamento || $is_subdirector)
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="{{ route('cursos.index') }}">Ver Todos los Cursos</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- GESTIÓN ADMINISTRATIVA - Solo Admin y CAD -->
                        @if($is_admin || $is_cad)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-cog fa-3x text-success"></i>
                                        </div>
                                        <h5 class="card-title">Administración</h5>
                                        <p class="card-text">Herramientas administrativas del sistema</p>
                                        <div class="dropdown">
                                            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Opciones
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('usuarios.index') }}">Gestionar Usuarios</a></li>
                                                <li><a class="dropdown-item" href="{{ route('register_user') }}">Registrar Usuario</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin_solicitarcursos.index') }}">Solicitudes de Cursos</a></li>
                                                <li><a class="dropdown-item" href="{{ route('cursos_estadisticas.index') }}">Estadísticas</a></li>
                                                <li><a class="dropdown-item" href="{{ route('periodos.index') }}">Gestionar Períodos</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- SOLICITUDES - Solo Jefes de Departamento -->
                        @if($is_jefe_departamento)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-file-alt fa-3x text-warning"></i>
                                        </div>
                                        <h5 class="card-title">Solicitudes</h5>
                                        <p class="card-text">Gestiona solicitudes de cursos</p>
                                        <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Opciones
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('solicitarcursos.create') }}">Solicitar Curso</a></li>
                                                <li><a class="dropdown-item" href="{{ route('jefe_solicitarcursos.index') }}">Mis Solicitudes</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- CURSOS DOCENTE - Solo para usuarios con función docente -->
                        @if($is_docente || $is_admin || $is_cad)
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-chalkboard-teacher fa-3x text-info"></i>
                                        </div>
                                        <h5 class="card-title">Mis Cursos Docente</h5>
                                        <p class="card-text">Cursos donde eres instructor</p>
                                        <a href="{{ route('docente_cursos.index') }}" class="btn btn-info">
                                            Ver Mis Cursos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- ENCUESTAS - Visible para todos -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-poll fa-3x text-secondary"></i>
                                    </div>
                                    <h5 class="card-title">Encuestas</h5>
                                    <p class="card-text">Evaluaciones de cursos completados</p>
                                    <small class="text-muted">Disponible al terminar cursos</small>
                                </div>
                            </div>
                        </div>

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

