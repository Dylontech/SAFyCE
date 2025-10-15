@extends('tablar::page')

@section('title', 'Reuniones Activas')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-video me-2"></i>
                    Reuniones Activas
                    <span class="badge bg-success ms-2" id="status-indicator">EN VIVO</span>
                </h2>
                <div class="text-muted mt-1">
                    Reuniones disponibles para unirse ahora mismo
                    <span class="text-muted small ms-2" id="last-update">
                        Última actualización: {{ now()->format('H:i:s') }}
                    </span>
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('alumnos_user.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Portal Estudiantil
                    </a>
                    <button onclick="refreshData()" class="btn btn-primary" id="refresh-btn">
                        <i class="ti ti-refresh me-1"></i>
                        Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Estadísticas en tiempo real -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h3 class="card-title" id="total-activas">{{ $totalActivas }}</h3>
                        <p class="card-text">Reuniones Activas</p>
                        <i class="ti ti-video-plus"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h3 class="card-title" id="total-hoy">{{ $totalHoy }}</h3>
                        <p class="card-text">Reuniones de Hoy</p>
                        <i class="ti ti-calendar-event"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-warning text-white">
                    <div class="card-body">
                        <h3 class="card-title" id="en-curso">{{ $reunionesEnCurso->count() }}</h3>
                        <p class="card-text">En Curso Ahora</p>
                        <i class="ti ti-play"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-purple text-white">
                    <div class="card-body">
                        <h3 class="card-title" id="proximas">{{ $reunionesProximas->count() }}</h3>
                        <p class="card-text">Próximas (15 min)</p>
                        <i class="ti ti-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <div id="reuniones-container">
            <!-- Reuniones en curso AHORA -->
            @if($reunionesEnCurso->count() > 0)
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title text-white">
                            <i class="ti ti-broadcast me-2"></i>
                            Reuniones en Curso - ¡Únete Ahora!
                            <span class="badge bg-light text-success ms-2">{{ $reunionesEnCurso->count() }}</span>
                        </h3>
                    </div>
                    <div class="row g-3 p-3">
                        @foreach($reunionesEnCurso as $reunion)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-success h-100 reunion-card">
                                    <div class="card-header bg-success-subtle">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="badge bg-success">
                                                <i class="ti ti-live-photo me-1"></i>
                                                EN VIVO
                                            </span>
                                            <span class="badge bg-{{ $reunion->plataforma === 'meet' ? 'warning' : ($reunion->plataforma === 'zoom' ? 'primary' : 'info') }}">
                                                {{ $reunion->plataforma_nombre }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-success">{{ $reunion->titulo }}</h5>
                                        <p class="card-text text-muted small">
                                            <i class="ti ti-user me-1"></i>{{ $reunion->creador->name }}
                                        </p>
                                        @if($reunion->sala)
                                            <p class="card-text text-muted small">
                                                <i class="ti ti-door me-1"></i>{{ $reunion->sala->nombre }}
                                            </p>
                                        @endif
                                        <p class="card-text">
                                            {{ Str::limit($reunion->descripcion, 80) }}
                                        </p>
                                        <div class="row text-muted small mb-2">
                                            <div class="col-6">
                                                <i class="ti ti-clock me-1"></i>
                                                {{ $reunion->hora_formateada }}
                                            </div>
                                            <div class="col-6">
                                                <i class="ti ti-hourglass me-1"></i>
                                                {{ $reunion->duracion_formateada }}
                                            </div>
                                        </div>
                                        @if($reunion->participantes_actuales)
                                            <div class="text-muted small mb-2">
                                                <i class="ti ti-users me-1"></i>
                                                {{ $reunion->participantes_actuales }} participante(s)
                                                @if($reunion->max_participantes)
                                                    / {{ $reunion->max_participantes }} máx.
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-light">
                                        <form action="{{ route('estudiantes.reuniones.unirse', $reunion) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100 btn-pulse">
                                                <i class="ti ti-external-link me-1"></i>
                                                Unirse Ahora
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reuniones próximas (empiezan en 15 minutos) -->
            @if($reunionesProximas->count() > 0)
                <div class="card mb-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="card-title">
                            <i class="ti ti-clock-hour-4 me-2"></i>
                            Próximas Reuniones - Prepárate
                            <span class="badge bg-dark ms-2">{{ $reunionesProximas->count() }}</span>
                        </h3>
                    </div>
                    <div class="row g-3 p-3">
                        @foreach($reunionesProximas as $reunion)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-warning h-100 reunion-card">
                                    <div class="card-header bg-warning-subtle">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="badge bg-warning text-dark">
                                                <i class="ti ti-clock me-1"></i>
                                                @php
                                                    $minutosRestantes = now()->diffInMinutes(Carbon\Carbon::parse($reunion->fecha_hora), false);
                                                @endphp
                                                {{ $minutosRestantes > 0 ? $minutosRestantes . ' min' : 'Iniciando...' }}
                                            </span>
                                            <span class="badge bg-{{ $reunion->plataforma === 'meet' ? 'warning' : ($reunion->plataforma === 'zoom' ? 'primary' : 'info') }}">
                                                {{ $reunion->plataforma_nombre }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-warning">{{ $reunion->titulo }}</h5>
                                        <p class="card-text text-muted small">
                                            <i class="ti ti-user me-1"></i>{{ $reunion->creador->name }}
                                        </p>
                                        @if($reunion->sala)
                                            <p class="card-text text-muted small">
                                                <i class="ti ti-door me-1"></i>{{ $reunion->sala->nombre }}
                                            </p>
                                        @endif
                                        <p class="card-text">
                                            {{ Str::limit($reunion->descripcion, 80) }}
                                        </p>
                                        <div class="row text-muted small mb-2">
                                            <div class="col-6">
                                                <i class="ti ti-calendar me-1"></i>
                                                {{ $reunion->hora_formateada }}
                                            </div>
                                            <div class="col-6">
                                                <i class="ti ti-hourglass me-1"></i>
                                                {{ $reunion->duracion_formateada }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <form action="{{ route('estudiantes.reuniones.unirse', $reunion) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning w-100">
                                                <i class="ti ti-external-link me-1"></i>
                                                Unirse (Pre-acceso)
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reuniones de hoy (programadas) -->
            @if($reunionesHoy->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title text-white">
                            <i class="ti ti-calendar-event me-2"></i>
                            Otras Reuniones de Hoy
                            <span class="badge bg-light text-info ms-2">{{ $reunionesHoy->count() }}</span>
                        </h3>
                    </div>
                    <div class="row g-3 p-3">
                        @foreach($reunionesHoy as $reunion)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-info h-100 reunion-card">
                                    <div class="card-header bg-info-subtle">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="badge bg-info">
                                                <i class="ti ti-calendar me-1"></i>
                                                {{ $reunion->hora_formateada }}
                                            </span>
                                            <span class="badge bg-{{ $reunion->plataforma === 'meet' ? 'warning' : ($reunion->plataforma === 'zoom' ? 'primary' : 'info') }}">
                                                {{ $reunion->plataforma_nombre }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-info">{{ $reunion->titulo }}</h5>
                                        <p class="card-text text-muted small">
                                            <i class="ti ti-user me-1"></i>{{ $reunion->creador->name }}
                                        </p>
                                        @if($reunion->sala)
                                            <p class="card-text text-muted small">
                                                <i class="ti ti-door me-1"></i>{{ $reunion->sala->nombre }}
                                            </p>
                                        @endif
                                        <p class="card-text">
                                            {{ Str::limit($reunion->descripcion, 80) }}
                                        </p>
                                        <div class="row text-muted small">
                                            <div class="col-6">
                                                <i class="ti ti-calendar me-1"></i>
                                                {{ $reunion->hora_formateada }}
                                            </div>
                                            <div class="col-6">
                                                <i class="ti ti-hourglass me-1"></i>
                                                {{ $reunion->duracion_formateada }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <button class="btn btn-outline-info w-100" disabled>
                                            <i class="ti ti-clock me-1"></i>
                                            Disponible más tarde
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Estado sin reuniones -->
            @if($totalActivas == 0 && $reunionesHoy->count() == 0)
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="empty">
                            <div class="empty-icon">
                                <i class="ti ti-video-off" style="font-size: 4rem; color: #ccc;"></i>
                            </div>
                            <p class="empty-title">No hay reuniones activas</p>
                            <p class="empty-subtitle text-muted">
                                No hay reuniones programadas para hoy o disponibles en este momento.
                            </p>
                            <div class="empty-action">
                                <button onclick="refreshData()" class="btn btn-primary">
                                    <i class="ti ti-refresh me-1"></i>
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@section('css')
<style>
    .btn-pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
    
    .reunion-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .reunion-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    #status-indicator {
        animation: blink 1.5s infinite;
    }
    
    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0.5; }
    }
    
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    
    .text-purple {
        color: #6f42c1 !important;
    }
    
    .border-purple {
        border-color: #6f42c1 !important;
    }
    
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .bg-info-subtle {
        background-color: rgba(13, 202, 240, 0.1);
    }
</style>
@endsection

@section('js')
<script>
    // Actualización automática cada 30 segundos
    let autoRefreshInterval;
    
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(function() {
            refreshData();
        }, 30000); // 30 segundos
    }
    
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    }
    
    function refreshData() {
        const refreshBtn = document.getElementById('refresh-btn');
        const lastUpdate = document.getElementById('last-update');
        
        // Mostrar loading
        refreshBtn.innerHTML = '<i class="ti ti-loader ti-spin me-1"></i>Actualizando...';
        refreshBtn.disabled = true;
        
        // Hacer petición AJAX
        fetch('{{ route("estudiantes.reuniones.activas") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Actualizar el contenedor
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById('reuniones-container');
            
            if (newContainer) {
                document.getElementById('reuniones-container').innerHTML = newContainer.innerHTML;
            }
            
            // Actualizar estadísticas
            const totalActivas = doc.getElementById('total-activas');
            const totalHoy = doc.getElementById('total-hoy');
            const enCurso = doc.getElementById('en-curso');
            const proximas = doc.getElementById('proximas');
            
            if (totalActivas) document.getElementById('total-activas').textContent = totalActivas.textContent;
            if (totalHoy) document.getElementById('total-hoy').textContent = totalHoy.textContent;
            if (enCurso) document.getElementById('en-curso').textContent = enCurso.textContent;
            if (proximas) document.getElementById('proximas').textContent = proximas.textContent;
            
            // Actualizar timestamp
            lastUpdate.textContent = 'Última actualización: ' + new Date().toLocaleTimeString();
            
            // Mostrar notificación de éxito
            showNotification('Datos actualizados correctamente', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al actualizar datos', 'error');
        })
        .finally(() => {
            // Restaurar botón
            refreshBtn.innerHTML = '<i class="ti ti-refresh me-1"></i>Actualizar';
            refreshBtn.disabled = false;
        });
    }
    
    function showNotification(message, type) {
        // Crear notificación simple
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-eliminar después de 3 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }
    
    // Iniciar auto-refresh cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        startAutoRefresh();
        
        // Pausar auto-refresh cuando la pestaña no está visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
            }
        });
    });
    
    // Limpiar interval al salir de la página
    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
</script>
@endsection
@endsection
