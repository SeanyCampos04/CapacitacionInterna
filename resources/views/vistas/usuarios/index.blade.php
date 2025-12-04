<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .optimized-container {
            width: 98%;
            max-width: 1400px !important;
            margin: 1rem auto !important;
            padding: 1.25rem;
        }

        .compact-table {
            font-size: 0.95rem;
        }

        .compact-table th,
        .compact-table td {
            padding: 0.5rem 0.75rem !important;
            vertical-align: middle;
            white-space: nowrap;
        }

        .compact-table .badge {
            font-size: 0.85rem !important;
            padding: 0.4em 0.8em !important;
            font-weight: 600 !important;
        }

        .compact-table th {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        /* Estilo del header igual al de Diplomados */
        .table thead th {
            background-color: #e3f2fd !important;
            color: #333 !important;
            font-weight: 600 !important;
            border: none !important;
            padding: 15px 12px !important;
            font-size: 14px !important;
        }

        .btn-action {
            width: 50px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            margin: 0 3px;
            text-decoration: none;
            font-size: 1rem;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .btn-view {
            background: #3b82f6;
            color: white;
        }

        .btn-view:hover {
            background: #2563eb;
            color: white;
        }
    </style>

    <div class="optimized-container bg-white shadow-lg rounded-lg">

                   <!-- Buscador con filtros -->
<form method="GET" action="{{ route('usuarios.index') }}" class="mb-6" id="buscarForm">
    <div class="flex flex-row flex-wrap gap-4 w-full items-center">
    <!-- Input de búsqueda -->
    <div class="flex-1 min-w-[350px]">
            <input
                type="text"
                name="busqueda"
                value="{{ old('busqueda', $busqueda ?? '') }}"
                placeholder="Buscar por nombre del usuario o email..."
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
        </div>
        <!-- Filtro por Departamento -->
        <div class="flex-1 min-w-[180px]">
            <select name="departamento" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="">Todos los departamentos</option>
                @foreach($departamentos as $dep)
                    <option value="{{ $dep->id }}" {{ request('departamento') == $dep->id ? 'selected' : '' }}>
                        {{ $dep->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    <!-- Filtro por Estatus -->
    <div class="flex-none w-[180px]">
    <select name="estatus"
        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
        <option value="">Todos los estatus</option>
        <option value="1" {{ request('estatus') === '1' ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ request('estatus') === '0' ? 'selected' : '' }}>Inactivo</option>
    </select>
</div>

        <!-- Filtro por Rol -->
        <div class="flex-none w-[230px]">
            <select name="rol" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="">Todos los roles</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}" {{ request('rol') == $rol->id ? 'selected' : '' }}>
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Botón buscar -->
        <div class="flex-none">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800">
                Buscar
            </x-primary-button>
        </div>
    </div>
</form>


<!-- Javascript: submit automático (input con debounce y cambios en selects) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('buscarForm');
        if (!form) return;

        const input = form.querySelector('input[name="busqueda"]');
        const selects = Array.from(form.querySelectorAll('select[name="departamento"], select[name="estatus"], select[name="rol"]'));

        // Debounce helper
        function debounce(fn, delay) {
            let t;
            return function (...args) {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        if (input) {
            // Enviar tras 500ms sin teclear
            const submitDebounced = debounce(() => form.submit(), 500);
            input.addEventListener('input', submitDebounced);
        }

        // Enviar inmediatamente cuando cambie cualquiera de los selects
        selects.forEach(s => s.addEventListener('change', () => form.submit()));
    });
</script>

        <!-- Resultados -->
        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                @php
                    $__results_count = method_exists($usuarios, 'total') ? $usuarios->total() : $usuarios->count();
                @endphp
                Resultados:
                <span class="font-medium text-gray-800">
                    {{ $__results_count }}
                </span>
                {{ $__results_count === 1 ? 'usuario' : 'usuarios' }}
            </div>
            <div></div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table table-hover compact-table">
                <thead>
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Departamento</th>
                        <th class="text-center">Rol</th>
                        <th class="text-center">Estatus</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr class="align-middle">
                            <td class="text-center">
                                {{ $usuario->datos_generales->nombre }}
                                {{ $usuario->datos_generales->apellido_paterno }}
                                {{ $usuario->datos_generales->apellido_materno }}
                            </td>
                            <td class="text-center">{{ $usuario->email }}</td>
                            <td class="text-center">
                                {{ $usuario->datos_generales->departamento->nombre ?? '—' }}
                            </td>
                            <td class="text-center">
                                @if($usuario->roles->count() > 0)
                                    @foreach($usuario->roles as $role)
                                        <span class="badge bg-primary">
                                            {{ $role->nombre }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">Sin rol</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($usuario->estatus)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('usuario_datos.index', $usuario->id) }}"
                                   class="btn-action btn-view"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
