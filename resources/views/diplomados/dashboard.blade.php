<x-app-diplomados-layout>
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
            <span class="font-semibold text-lg text-purple-600 dark:text-purple-400">
                Gestión de Diplomados
            </span>
        </div>
    </x-slot>

    <!-- Agregar Bootstrap CSS -->
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

            /* Botón púrpura para instructor */
            .btn-purple {
                background: linear-gradient(45deg, #8b5cf6, #7c3aed);
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn-purple:hover {
                background: linear-gradient(45deg, #7c3aed, #6d28d9);
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
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
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Selecciona una de las opciones de funcionalidad del módulo de diplomados</p>

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

                            // Definir roles por ID específicos
                            $is_admin = in_array(1, $user_role_ids);           // Admin (id=1)
                            $is_jefe_departamento = in_array(2, $user_role_ids); // Jefe Departamento (id=2)
                            $is_subdirector = in_array(3, $user_role_ids);     // Subdirector Académico (id=3)
                            $is_cad = in_array(4, $user_role_ids);             // CAD (id=4)
                            $is_instructor = in_array(5, $user_role_ids);      // Instructor (id=5)
                        @endphp

                        <!-- OPCIONES PARA ADMIN (id=1) Y CAD (id=4) -->
                        @if ($is_admin || $is_cad)
                            <!-- Registrar Diplomado -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-plus-circle fa-3x text-success"></i>
                                        </div>
                                        <h5 class="card-title">Registrar Diplomado</h5>
                                        <p class="card-text">Crear un nuevo diplomado</p>
                                        <a href="{{ route('diplomados.diplomados.create') }}" class="btn btn-success">
                                            Registrar
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Diplomados Registrados -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                                        </div>
                                        <h5 class="card-title">Diplomados Registrados</h5>
                                        <p class="card-text">Gestionar diplomados existentes</p>
                                        <a href="{{ route('diplomados.diplomados.index') }}" class="btn btn-primary">
                                            Ver Diplomados
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- En Oferta -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-bullhorn fa-3x text-warning"></i>
                                        </div>
                                        <h5 class="card-title">En Oferta</h5>
                                        <p class="card-text">Ver ofertas disponibles</p>
                                        <a href="{{ route('diplomados.oferta') }}" class="btn btn-warning">
                                            Ver Ofertas
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Mis Solicitudes -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-file-alt fa-3x text-info"></i>
                                        </div>
                                        <h5 class="card-title">Mis Solicitudes</h5>
                                        <p class="card-text">Estado de mis solicitudes</p>
                                        <a href="{{ route('diplomados.solicitudes') }}" class="btn btn-info">
                                            Ver Solicitudes
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- En Curso - Participante -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-play-circle fa-3x text-success"></i>
                                        </div>
                                        <h5 class="card-title">En Curso - Participante</h5>
                                        <p class="card-text">Diplomados donde participo</p>
                                        <a href="{{ route('diplomados.curso_docente') }}" class="btn btn-success">
                                            Ver En Curso
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Terminado - Participante -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-check-circle fa-3x text-secondary"></i>
                                        </div>
                                        <h5 class="card-title">Terminado - Participante</h5>
                                        <p class="card-text">Diplomados completados</p>
                                        <a href="{{ route('diplomados.terminado_docente') }}" class="btn btn-secondary">
                                            Ver Terminados
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- OPCIONES PARA JEFE DEPARTAMENTO (id=2) Y SUBDIRECTOR ACADÉMICO (id=3) -->
                        @if ($is_jefe_departamento || $is_subdirector)
                            <!-- Diplomados Registrados -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                                        </div>
                                        <h5 class="card-title">Diplomados Registrados</h5>
                                        <p class="card-text">Ver diplomados existentes</p>
                                        <a href="{{ route('diplomados.diplomados.index') }}" class="btn btn-primary">
                                            Ver Diplomados
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- En Oferta -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-bullhorn fa-3x text-warning"></i>
                                        </div>
                                        <h5 class="card-title">En Oferta</h5>
                                        <p class="card-text">Ver ofertas disponibles</p>
                                        <a href="{{ route('diplomados.oferta') }}" class="btn btn-warning">
                                            Ver Ofertas
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Mis Solicitudes -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-file-alt fa-3x text-info"></i>
                                        </div>
                                        <h5 class="card-title">Mis Solicitudes</h5>
                                        <p class="card-text">Estado de mis solicitudes</p>
                                        <a href="{{ route('diplomados.solicitudes') }}" class="btn btn-info">
                                            Ver Solicitudes
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- En Curso - Participante -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-play-circle fa-3x text-success"></i>
                                        </div>
                                        <h5 class="card-title">En Curso - Participante</h5>
                                        <p class="card-text">Diplomados donde participo</p>
                                        <a href="{{ route('diplomados.curso_docente') }}" class="btn btn-success">
                                            Ver En Curso
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Terminado - Participante -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-check-circle fa-3x text-secondary"></i>
                                        </div>
                                        <h5 class="card-title">Terminado - Participante</h5>
                                        <p class="card-text">Diplomados completados</p>
                                        <a href="{{ route('diplomados.terminado_docente') }}" class="btn btn-secondary">
                                            Ver Terminados
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- OPCIONES PARA INSTRUCTOR (id=5) -->
                        @if ($is_instructor)
                            <!-- En Oferta -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-bullhorn fa-3x text-warning"></i>
                                        </div>
                                        <h5 class="card-title">En Oferta</h5>
                                        <p class="card-text">Ver ofertas disponibles</p>
                                        <a href="{{ route('diplomados.oferta') }}" class="btn btn-warning">
                                            Ver Ofertas
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Mis Solicitudes -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-file-alt fa-3x text-info"></i>
                                        </div>
                                        <h5 class="card-title">Mis Solicitudes</h5>
                                        <p class="card-text">Estado de mis solicitudes</p>
                                        <a href="{{ route('diplomados.solicitudes') }}" class="btn btn-info">
                                            Ver Solicitudes
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- En Curso - Instructor -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-chalkboard-teacher fa-3x text-purple"></i>
                                        </div>
                                        <h5 class="card-title">En Curso - Instructor</h5>
                                        <p class="card-text">Diplomados donde instruyo</p>
                                        <a href="{{ route('diplomados.curso_instructor') }}" class="btn btn-purple">
                                            Ver Como Instructor
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Terminado - Instructor -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-medal fa-3x text-dark"></i>
                                        </div>
                                        <h5 class="card-title">Terminado - Instructor</h5>
                                        <p class="card-text">Diplomados completados como instructor</p>
                                        <a href="{{ route('diplomados.terminado_instructor') }}" class="btn btn-dark">
                                            Ver Terminados
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- OPCIONES PARA TODOS LOS USUARIOS (sin roles específicos o docentes generales) -->
                        @if (!$is_admin && !$is_cad && !$is_jefe_departamento && !$is_subdirector && !$is_instructor)
                            <!-- En Oferta -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-bullhorn fa-3x text-warning"></i>
                                        </div>
                                        <h5 class="card-title">Ofertas de Diplomados</h5>
                                        <p class="card-text">Ver ofertas disponibles</p>
                                        <a href="{{ route('diplomados.oferta') }}" class="btn btn-warning">
                                            Ver Ofertas
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Mis Solicitudes -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-file-alt fa-3x text-info"></i>
                                        </div>
                                        <h5 class="card-title">Mis Solicitudes</h5>
                                        <p class="card-text">Estado de mis solicitudes</p>
                                        <a href="{{ route('diplomados.solicitudes') }}" class="btn btn-info">
                                            Ver Solicitudes
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- En Curso - Participante -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-play-circle fa-3x text-success"></i>
                                        </div>
                                        <h5 class="card-title">En Curso - Participante</h5>
                                        <p class="card-text">Diplomados donde participo</p>
                                        <a href="{{ route('diplomados.curso_docente') }}" class="btn btn-success">
                                            Ver En Curso
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Terminado - Participante -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-check-circle fa-3x text-secondary"></i>
                                        </div>
                                        <h5 class="card-title">Terminado - Participante</h5>
                                        <p class="card-text">Diplomados completados</p>
                                        <a href="{{ route('diplomados.terminado_docente') }}" class="btn btn-secondary">
                                            Ver Terminados
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

    <!-- Agregar Bootstrap JS y Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Mostrar el modal automáticamente si hay un mensaje de éxito -->
    @if(session('success'))
        <script>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        </script>
    @endif

    <!-- Footer solo para la vista de inicio -->
    <x-footer></x-footer>
</x-app-diplomados-layout>
