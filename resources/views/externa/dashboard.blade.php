<x-app-externa-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Capacitación Externa - Dashboard') }}
        </h2>
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
        </style>
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">{{ __("Bienvenido al Módulo de Capacitación Externa") }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Gestiona y registra las capacitaciones externas de la institución.</p>

                    <!-- Tarjetas de acciones rápidas -->
                    <div class="row">
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

                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-user fa-3x text-info"></i>
                                    </div>
                                    <h5 class="card-title">Mis Capacitaciones</h5>
                                    <p class="card-text">Ve tus capacitaciones registradas</p>
                                    <a href="{{ route('externa.mis_capacitaciones') }}" class="btn btn-info">
                                        Ver Mis Registros
                                    </a>
                                </div>
                            </div>
                        </div>
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
</x-app-externa-layout>
