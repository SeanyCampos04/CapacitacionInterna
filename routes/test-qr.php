<?php

use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Rutas de prueba para el sistema QR (agregar temporalmente)
Route::get('/test-qr', function() {
    $numeroRegistro = 'TNM-169-01-2024/01';
    $url = route('verificacion.constancia', $numeroRegistro);

    try {
        $qrCode = QrCode::size(200)->generate($url);
        $status = 'QR generado correctamente';
    } catch (Exception $e) {
        $qrCode = '<div style="border: 1px solid red; padding: 20px;">Error: ' . $e->getMessage() . '</div>';
        $status = 'Error al generar QR';
    }

    return view('test-qr', compact('qrCode', 'url', 'numeroRegistro', 'status'));
});

Route::get('/test-qr-simple', function() {
    try {
        $qrCode = QrCode::size(200)->generate('https://www.google.com');
        return '<h1>Prueba QR Simple</h1><div>' . $qrCode . '</div><p>Si ves el QR arriba, la librería funciona correctamente.</p>';
    } catch (Exception $e) {
        return '<h1>Error</h1><p>' . $e->getMessage() . '</p>';
    }
});

Route::get('/test-qr-instructor', function() {
    $numeroRegistro = 'TNM-169-01-2024/I-01';
    $url = route('verificacion.reconocimiento', $numeroRegistro);
    $qrCode = QrCode::size(200)->generate($url);

    return view('test-qr', compact('qrCode', 'url', 'numeroRegistro'));
});

Route::get('/test-qr-externa', function() {
    $numeroRegistro = 'TNM-169-TEST-2024/01';
    $url = route('verificacion.constancia', $numeroRegistro);
    $qrCode = QrCode::size(200)->generate($url);

    return view('test-qr', compact('qrCode', 'url', 'numeroRegistro'));
});

// Ruta de prueba directa para verificar que funciona
Route::get('/test-verificacion-directa', function() {
    return app(App\Http\Controllers\VerificacionPublicaController::class)->verificarConstancia('TNM-169-01-2024/01');
});

// Debug de URL generada
Route::get('/debug-url', function() {
    $numeroRegistro = 'TNM-169-01-2024/01';
    $url = route('verificacion.constancia', $numeroRegistro);

    return [
        'numero_registro' => $numeroRegistro,
        'url_generada' => $url,
        'url_encoded' => urlencode($numeroRegistro),
        'url_con_encoded' => route('verificacion.constancia', urlencode($numeroRegistro))
    ];
});

// Debug de datos en base de datos
Route::get('/debug-datos', function() {
    // Cursos disponibles
    $cursos = \App\Models\Curso::select('id', 'nombre', 'created_at')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get()
        ->map(function($c) {
            return [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'fecha' => $c->created_at->format('Y-m-d'),
                'año' => $c->created_at->year
            ];
        });

    // Cursos de 2025
    $cursos2025 = \App\Models\Curso::whereYear('created_at', 2025)->count();

    // Participantes acreditados
    $participantes = \App\Models\cursos_participante::where('acreditado', 2)
        ->with('curso')
        ->take(3)
        ->get()
        ->map(function($p) {
            return [
                'id' => $p->id,
                'curso' => $p->curso->nombre ?? 'Sin curso',
                'acreditado' => $p->acreditado
            ];
        });

    return [
        'cursos_recientes' => $cursos,
        'total_cursos_2025' => $cursos2025,
        'participantes_acreditados' => $participantes,
        'mensaje' => 'Si no hay cursos de 2025, usa un número de 2024 o el año que tengas datos'
    ];
});

// Generar un documento de prueba válido
Route::get('/generar-prueba', function() {
    $curso = \App\Models\Curso::first();
    if (!$curso) {
        return ['error' => 'No hay cursos en la base de datos'];
    }

    // Buscar participante existente o usar el primero disponible
    $participante = \App\Models\cursos_participante::where('curso_id', $curso->id)
        ->first();

    if (!$participante) {
        return ['error' => 'No hay participantes registrados en este curso'];
    }

    // Generar número para el curso
    $año = $curso->created_at->year;

    // Si ya tiene número, lo mostramos
    if ($participante->numero_registro) {
        $numeroRegistro = $participante->numero_registro;
    } else {
        // Generar nuevo número
        $ultimoConsecutivo = \App\Models\cursos_participante::whereNotNull('numero_registro')
            ->whereHas('curso', function($q) use ($año) {
                $q->whereYear('created_at', $año);
            })
            ->count();
        $nuevoConsecutivo = str_pad($ultimoConsecutivo + 1, 2, '0', STR_PAD_LEFT);
        $numeroRegistro = "TNM-169-{$nuevoConsecutivo}-{$año}/01";

        // Actualizar el participante
        $participante->update(['numero_registro' => $numeroRegistro]);
    }

    return [
        'curso' => $curso->nombre,
        'año_curso' => $año,
        'numero_generado' => $numeroRegistro,
        'url_verificacion' => route('verificacion.constancia', $numeroRegistro),
        'mensaje' => 'Usa este número para probar la verificación'
    ];
});

// Debug específico del número problema
Route::get('/debug-numero/{numero}', function($numero) {
    // Decodificar si viene codificado
    $numeroDecodificado = urldecode($numero);

    // Análisis del formato
    $esInstructor = preg_match('/TNM-169-\d{2}-\d{4}\/I-\d{2}/', $numeroDecodificado);
    $esParticipante = preg_match('/TNM-169-\d{2}-\d{4}\/\d{2}/', $numeroDecodificado);

    // Extraer partes
    if (preg_match('/TNM-169-(\d{2})-(\d{4})\/(I?)-?(\d{2})/', $numeroDecodificado, $matches)) {
        $consecutivo = $matches[1];
        $año = $matches[2];
        $esInstructorMatch = !empty($matches[3]);
        $numeroFinal = $matches[4];

        // Buscar en base de datos
        if ($esInstructorMatch) {
            // Para instructores, buscar en la tabla cursos_instructores
            // y generar el número basado en el año del curso
            $resultado = \App\Models\Curso::whereYear('created_at', $año)
                ->whereHas('instructores')
                ->get()
                ->filter(function($curso) use ($numeroDecodificado) {
                    // Simular la generación del número de instructor
                    $añoCurso = $curso->created_at->year;
                    $consecutivo = str_pad($curso->id, 2, '0', STR_PAD_LEFT);
                    $numeroGenerado = "TNM-169-{$consecutivo}-{$añoCurso}/I-01";
                    return $numeroGenerado === $numeroDecodificado;
                })
                ->first();
        } else {
            $resultado = \App\Models\cursos_participante::where('numero_registro', $numeroDecodificado)->first();
        }

        return [
            'numero_original' => $numero,
            'numero_decodificado' => $numeroDecodificado,
            'es_instructor' => $esInstructor,
            'es_participante' => $esParticipante,
            'partes_extraidas' => [
                'consecutivo' => $consecutivo,
                'año' => $año,
                'es_instructor_match' => $esInstructorMatch,
                'numero_final' => $numeroFinal
            ],
            'encontrado_en_bd' => $resultado ? 'SÍ' : 'NO',
            'total_cursos_año' => \App\Models\Curso::whereYear('created_at', $año)->count(),
            'sugerencia' => $resultado ? 'Número válido' : 'Número no existe en BD'
        ];
    }

    return [
        'numero_original' => $numero,
        'numero_decodificado' => $numeroDecodificado,
        'error' => 'Formato no reconocido'
    ];
})->where('numero', '.*');

// Mostrar datos simples para debug
Route::get('/datos-simples', function() {
    $curso = \App\Models\Curso::first();
    $participante = \App\Models\cursos_participante::first();

    return "CURSO: " . ($curso ? $curso->nombre : "NO HAY CURSOS") . "<br>" .
           "AÑO: " . ($curso ? $curso->created_at->year : "N/A") . "<br>" .
           "PARTICIPANTE: " . ($participante ? $participante->id : "NO HAY PARTICIPANTES") . "<br>" .
           "ACREDITADO: " . ($participante ? $participante->acreditado : "N/A") . "<br>" .
           "NUMERO_REGISTRO: " . ($participante ? ($participante->numero_registro ?: "SIN ASIGNAR") : "N/A");
});
