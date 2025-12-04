<x-app-externa-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registro de Capacitaciones Externas') }}
        </h2>
    </x-slot>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Capacitaciones Externas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Roboto', sans-serif;
        }
        .container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 850px;
            margin-top: 60px;
            margin-bottom: 60px;
        }
        h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: left;
            color: #495057;
        }
        label {
            font-weight: 500;
            color: #495057;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px;
        }
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
        .text-center {
            margin-top: 20px;
        }
        .form-text {
            color: #6c757d;
        }
        .alert {
            border-radius: 8px;
        }
        input[type="file"] {
            padding: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-close {
            position: absolute;
            right: 10px;
            top: 10px;
            border-color: #0056b3;
        }
        .text-center {
            margin-top: 20px;
        }
        .form-text {
            color: #6c757d;
        }
        .alert {
            border-radius: 8px;
        }
        input[type="file"] {
            padding: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-close {
            position: absolute;
            right: 10px;
            top: 10px;
            color: #000;
        }
        /* Quitar el subrayado de todos los enlaces */
        a {
            text-decoration: none !important;
        }
    </style>
</head>
<body>
    <div class="container">
    <p class="text-center text-muted">Complete el formulario para registrar su capacitación y generar constancias.</p>
    <form action="{{ route('capacitacionesext.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Correo -->
        <div class="form-group">
            <label for="correo">Correo <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="correo" name="correo" value="{{ auth()->user()->email }}" readonly>
            <small class="form-text">Este correo se usará para identificar su capacitación.</small>
        </div>

        <!-- Nombre Completo -->
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
            </div>

            <div class="col-md-4 form-group">
                <label for="apellido_paterno">Apellido Paterno <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Ingrese su apellido paterno" required>
            </div>
            <div class="col-md-4 form-group">
                <label for="apellido_materno">Apellido Materno</label>
                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Ingrese su apellido materno">
            </div>
        </div>

        <!-- Tipo de capacitación -->
        <div class="form-group">
            <label for="tipo_capacitacion">Tipo de capacitación <span class="text-danger">*</span></label>
            <select class="form-control" id="tipo_capacitacion" name="tipo_capacitacion" required onchange="toggleOtherOption()">
                <option value="">Seleccione una opción</option>
                <option value="diplomado">Diplomado</option>
                <option value="taller_curso">Taller o curso</option>
                <option value="mooc">Mooc (TecNM)</option>
                <option value="otro">Otro</option>
            </select>
        </div>

        <!-- Nombre de la capacitación -->
        <div class="form-group">
            <label for="nombre_capacitacion">Nombre de la capacitación <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre_capacitacion" name="nombre_capacitacion" placeholder="Ingrese el nombre de la capacitación" required>
        </div>

        <!-- Fecha de inicio y término -->
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="fecha_inicio">Fecha de inicio <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="col-md-6 form-group">
                <label for="fecha_termino">Fecha de término <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_termino" name="fecha_termino" required>
            </div>
        </div>

        <!-- Año de realización -->
        <div class="form-group">
            <label for="anio">Año en que se realizó <span class="text-danger">*</span></label>
            <select class="form-control" id="anio" name="anio" required>
                <option value="">Seleccione un año</option>
                @php
                    $currentYear = date('Y');
                    $startYear = $currentYear - 5;
                @endphp
                @for($year = $currentYear; $year >= $startYear; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>

        <!-- Organismo o institución formadora -->
        <div class="form-group">
            <label for="organismo">Organismo o institución formadora <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="organismo" name="organismo" placeholder="Ingrese la institución formadora" required>
        </div>

        <!-- Número de horas -->
        <div class="form-group">
            <label for="horas">Número de horas <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="horas" name="horas" placeholder="Ingrese el número de horas" min="30" required>
            <small class="form-text">Debe ser igual o mayor a 30 horas.</small>
        </div>

        <!-- Archivo de evidencia -->
        <div class="form-group">
            <label for="evidencia">Evidencia de la capacitación (PDF, máximo 1 MB) <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="evidencia" name="evidencia" accept="application/pdf" required>
        </div>

        <!-- Botón -->
        <div class="mt-4">
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                Registrar Capacitación
            </button>
        </div>
    </form>
</div>

<!-- JavaScript -->
<script>
    function toggleOtherOption() {
        const selectField = document.getElementById('tipo_capacitacion');

        if (selectField.value === "otro") {
            const inputField = document.createElement("input");
            inputField.type = "text";
            inputField.className = "form-control";
            inputField.name = "tipo_capacitacion";
            inputField.id = "tipo_capacitacion";
            inputField.placeholder = "Especifique otro tipo de capacitación";
            inputField.required = true;

            selectField.replaceWith(inputField);
            inputField.focus();

            inputField.addEventListener("blur", () => {
                if (inputField.value.trim() === "") {
                    inputField.replaceWith(selectField);
                    selectField.value = "";
                }
            });
        }
    }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</x-app-externa-layout>
