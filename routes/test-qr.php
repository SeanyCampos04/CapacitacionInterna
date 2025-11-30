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

// Probar generación de constancia
Route::get('/test-constancia', function() {
    $participante = \App\Models\cursos_participante::with([
        'participante.user.datos_generales',
        'curso'
    ])->where('acreditado', 2)->first();

    if (!$participante) {
        return "No hay participantes acreditados";
    }

    $datos = [
        'participante_id' => $participante->id,
        'curso_id' => $participante->curso_id,
        'tiene_participante' => $participante->participante ? 'SÍ' : 'NO',
        'tiene_user' => ($participante->participante && $participante->participante->user) ? 'SÍ' : 'NO',
        'tiene_datos_generales' => ($participante->participante && $participante->participante->user && $participante->participante->user->datos_generales) ? 'SÍ' : 'NO'
    ];

    if ($participante->participante && $participante->participante->user && $participante->participante->user->datos_generales) {
        $datos['nombre'] = $participante->participante->user->datos_generales->nombre;
    }

    return $datos;
});

// Debug para verificar generación de QR
Route::get('/debug-qr-generation', function() {
    $participante = \App\Models\cursos_participante::with(['curso', 'participante.user.datos_generales'])
        ->where('acreditado', 2)
        ->first();

    if (!$participante) {
        return ['error' => 'No hay participantes acreditados'];
    }

    // Simular la generación del número como en el controlador
    $curso = $participante->curso;
    $numeroDelCurso = 1; // Simplificado para debug
    $numeroRegistro = sprintf('TNM-169-%02d-%s/%02d',
        $numeroDelCurso,
        $curso->created_at->format('Y'),
        1
    );

    // Generar QR
    $urlVerificacion = route('verificacion.constancia', $numeroRegistro);
    $codigoQR = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($urlVerificacion);
    $codigoQRBase64 = base64_encode($codigoQR);

    return [
        'participante_id' => $participante->id,
        'curso_nombre' => $curso->nombre,
        'numero_registro_generado' => $numeroRegistro,
        'url_verificacion' => $urlVerificacion,
        'qr_generado' => !empty($codigoQR) ? 'SÍ' : 'NO',
        'qr_length' => strlen($codigoQR),
        'qr_base64_length' => strlen($codigoQRBase64),
        'numero_registro_en_bd' => $participante->numero_registro
    ];
});

// Debug del QR en el contexto real del controlador
Route::get('/debug-qr-real', function() {
    $participante = \App\Models\cursos_participante::with(['curso', 'participante.user.datos_generales'])
        ->where('acreditado', 2)
        ->first();

    if (!$participante) {
        return ['error' => 'No hay participantes acreditados'];
    }

    // Simular exactamente lo que hace el controlador
    $numeroRegistro = 'TNM-169-01-2025/01'; // Usar un número fijo para debug
    $urlVerificacion = route('verificacion.constancia', $numeroRegistro);

    $codigoQRBase64 = null;
    $errorMsg = null;

    try {
        $codigoQRData = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->backgroundColor(255,255,255)->color(0,0,0)->generate($urlVerificacion);
        $codigoQRBase64 = base64_encode($codigoQRData);
    } catch (\Exception $e) {
        $errorMsg = $e->getMessage();
        try {
            $codigoQRData = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($urlVerificacion);
            $codigoQRBase64 = base64_encode($codigoQRData);
        } catch (\Exception $e2) {
            $errorMsg .= ' | SVG Error: ' . $e2->getMessage();
        }
    }

    return [
        'url_verificacion' => $urlVerificacion,
        'qr_base64_existe' => !empty($codigoQRBase64),
        'qr_base64_length' => $codigoQRBase64 ? strlen($codigoQRBase64) : 0,
        'error_msg' => $errorMsg,
        'qr_preview' => $codigoQRBase64 ? substr($codigoQRBase64, 0, 100) . '...' : 'VACÍO'
    ];
});

// Debug del nuevo método con API externa
Route::get('/debug-qr-api', function() {
    $numeroRegistro = 'TNM-169-01-2025/01';
    $urlVerificacion = route('verificacion.constancia', $numeroRegistro);
    $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($urlVerificacion);

    return [
        'numero_registro' => $numeroRegistro,
        'url_verificacion' => $urlVerificacion,
        'qr_api_url' => $qrApiUrl,
        'url_encoded' => urlencode($urlVerificacion),
        'mensaje' => 'Visita qr_api_url en tu navegador para ver si genera el QR'
    ];
});

// Debug del método final con descarga
Route::get('/debug-qr-final', function() {
    $numeroRegistro = 'TNM-169-01-2025/01';
    $urlVerificacion = route('verificacion.constancia', $numeroRegistro);
    $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($urlVerificacion);

    try {
        $qrImageData = file_get_contents($qrApiUrl);
        $codigoQR = 'data:image/png;base64,' . base64_encode($qrImageData);
        $success = true;
        $error = null;
    } catch (\Exception $e) {
        $codigoQR = null;
        $success = false;
        $error = $e->getMessage();
    }

    return [
        'success' => $success,
        'error' => $error,
        'qr_api_url' => $qrApiUrl,
        'data_uri_length' => $codigoQR ? strlen($codigoQR) : 0,
        'data_uri_preview' => $codigoQR ? substr($codigoQR, 0, 100) . '...' : null
    ];
});
