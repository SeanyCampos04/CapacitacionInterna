<x-verificacion-layout>
    <div class="verification-card">
        <!-- Header -->
        <div class="header-section">
            <h2 class="mb-2">Sistema de Validación de Constancias y Reconocimientos</h2>
            <p class="mb-0 opacity-90">Verificación de Documentos Oficiales</p>
        </div>

        <!-- Estado del documento -->
        @if($estado === 'VÁLIDO')
            <div class="status-valid p-4 text-center">
                <i class="fas fa-check-circle fa-4x mb-3"></i>
                <h3 class="mb-2">DOCUMENTO VÁLIDO</h3>
                <p class="mb-0">El documento ha sido verificado correctamente</p>
            </div>
        @elseif($estado === 'INVÁLIDO')
            <div class="status-invalid p-4 text-center">
                <i class="fas fa-times-circle fa-4x mb-3"></i>
                <h3 class="mb-2">DOCUMENTO INVÁLIDO</h3>
                <p class="mb-0">El número de registro no corresponde a ningún documento válido</p>
            </div>
        @elseif($estado === 'NO IMPLEMENTADO')
            <div class="status-not-implemented p-4 text-center">
                <i class="fas fa-exclamation-triangle fa-4x mb-3"></i>
                <h3 class="mb-2">FUNCIONALIDAD EN DESARROLLO</h3>
                <p class="mb-0">{{ $mensaje ?? 'Esta funcionalidad está en desarrollo' }}</p>
            </div>
        @endif

        <!-- Información del documento -->
        <div class="info-section">
            @if($estado === 'VÁLIDO' && $documento)
                <h4 class="text-center mb-3" style="color: rgb(27, 57, 107);">
                    <i class="fas fa-certificate"></i> {{ $documento['tipo_documento'] }}
                </h4>

                <!-- Número de registro y participante -->
                <div class="verification-code mb-4">
                    <div style="font-size: 1.3rem; font-weight: bold; margin-bottom: 10px;">
                        {{ $documento['nombre_completo'] }}
                    </div>
                    <div>
                        <i class="fas fa-shield-alt"></i> NÚMERO DE REGISTRO: {{ $numeroRegistro }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <strong class="text-muted">Curso:</strong><br>
                            <span>{{ $documento['nombre_programa'] }}</span>
                        </div>

                        <div class="info-row">
                            <strong class="text-muted">Duración:</strong><br>
                            <span><i class="fas fa-clock"></i> {{ $documento['horas'] }} horas</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-row">
                            <strong class="text-muted">
                                @if(isset($modulo) && $modulo === 'Externa')
                                    Tipo de capacitación:
                                @else
                                    Modalidad:
                                @endif
                            </strong><br>
                            <span><i class="fas fa-desktop"></i> {{ $documento['modalidad'] }}</span>
                        </div>

                        @if(isset($documento['lugar']))
                            <div class="info-row">
                                <strong class="text-muted">Lugar:</strong><br>
                                <span><i class="fas fa-map-marker-alt"></i> {{ $documento['lugar'] }}</span>
                            </div>
                        @endif

                        @if(isset($documento['departamento']))
                            <div class="info-row">
                                <strong class="text-muted">Departamento:</strong><br>
                                <span>{{ $documento['departamento'] }}</span>
                            </div>
                        @endif

                        @if(isset($documento['organismo']))
                            <div class="info-row">
                                <strong class="text-muted">Organismo:</strong><br>
                                <span>{{ $documento['organismo'] }}</span>
                            </div>
                        @endif

                        {{-- Instructor(es) - Comentado temporalmente --}}
                        {{-- @if(isset($documento['instructores']))
                            <div class="info-row">
                                <strong class="text-muted">Instructor(es):</strong><br>
                                <span><i class="fas fa-user-tie"></i> {{ $documento['instructores'] }}</span>
                            </div>
                        @endif --}}

                        {{-- Calificación - Comentada temporalmente --}}
                        {{-- @if(isset($documento['calificacion']))
                            <div class="info-row">
                                <strong class="text-muted">Calificación:</strong><br>
                                <span><i class="fas fa-star"></i> {{ $documento['calificacion'] }}</span>
                            </div>
                        @endif --}}
                    </div>
                </div>

                @if(isset($documento['fecha_inicio']) && isset($documento['fecha_termino']))
                    <div class="info-row text-center">
                        <strong class="text-muted">Período de realización:</strong><br>
                        <span class="badge bg-primary">
                            <i class="fas fa-calendar-alt"></i>
                            {{ \Carbon\Carbon::parse($documento['fecha_inicio'])->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($documento['fecha_termino'])->format('d/m/Y') }}
                        </span>
                    </div>
                @endif

                @if(isset($documento['anio']))
                    <div class="info-row text-center">
                        <strong class="text-muted">Año:</strong><br>
                        <span class="badge bg-secondary">{{ $documento['anio'] }}</span>
                    </div>
                @endif

            @elseif($estado === 'INVÁLIDO')
                <div class="text-center">
                    <h4 class="text-danger mb-3">
                        <i class="fas fa-exclamation-triangle"></i> Documento No Encontrado
                    </h4>
                    <div class="alert alert-danger">
                        <strong>Número de registro consultado:</strong>
                        <div class="verification-code mt-2">{{ $numeroRegistro }}</div>
                    </div>
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Verifique que el número de registro sea correcto o contacte con la institución emisora.
                    </p>
                </div>

            @elseif($estado === 'NO IMPLEMENTADO')
                <div class="text-center">
                    <h4 class="text-warning mb-3">
                        <i class="fas fa-tools"></i> Funcionalidad en Desarrollo
                    </h4>
                    <div class="alert alert-warning">
                        <strong>Módulo:</strong> {{ $modulo }}
                        <div class="verification-code mt-2">{{ $numeroRegistro }}</div>
                    </div>
                    <p class="text-muted">
                        {{ $mensaje ?? 'Esta funcionalidad estará disponible próximamente.' }}
                    </p>
                </div>
            @endif
        </div>

    </div>
</x-verificacion-layout>
