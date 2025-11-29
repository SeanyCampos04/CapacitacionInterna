<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Código QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .test-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 40px;
            text-align: center;
        }
        .qr-code {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            display: inline-block;
        }
        .url-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            margin: 20px 0;
            word-break: break-all;
        }
        .instructions {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1 class="mb-4">🧪 Prueba de Código QR</h1>

        <div class="alert alert-info">
            <strong>Número de Registro:</strong> {{ $numeroRegistro }}<br>
            @if(isset($status))
                <strong>Estado:</strong> {{ $status }}
            @endif
        </div>

        <div class="qr-code">
            {!! $qrCode !!}
        </div>

        <div class="url-info">
            <strong>URL generada:</strong><br>
            {{ $url }}
        </div>

        <div class="instructions">
            <h5><i class="fas fa-mobile-alt"></i> Instrucciones para probar:</h5>
            <ol>
                <li><strong>Desde móvil:</strong> Escanea el código QR con la cámara de tu teléfono</li>
                <li><strong>Desde PC:</strong> Haz clic en el enlace de arriba</li>
                <li><strong>Con ngrok:</strong> Si usas ngrok, el QR funcionará desde cualquier dispositivo</li>
                <li><strong>IP local:</strong> Solo funcionará si el dispositivo está en la misma red</li>
            </ol>
        </div>

        <div class="mt-4">
            <a href="{{ $url }}" class="btn btn-primary btn-lg" target="_blank">
                <i class="fas fa-external-link-alt"></i> Probar Verificación
            </a>
        </div>

        <div class="mt-3">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                Esta es una página de prueba. Los datos mostrados son de ejemplo.
            </small>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
