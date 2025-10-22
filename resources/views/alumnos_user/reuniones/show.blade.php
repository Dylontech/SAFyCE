@extends('layouts.app-alumno')

@section('title', 'Reunión: ' . $reunion->titulo)

@section('content')
<div class="container-fluid">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Page Header -->
    <div class="page-header d-print-none mb-4">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('alumnos.reuniones.index') }}" class="text-white">Reuniones</a></li>
                            <li class="breadcrumb-item active text-white-50" aria-current="page">{{ $reunion->titulo }}</li>
                        </ol>
                    </nav>
                    <h2 class="page-title">
                        <i class="fas fa-video me-2"></i>
                        {{ $reunion->titulo }}
                    </h2>
                    <div class="text-white-50 mt-1">
                        Detalles de la reunión virtual
                    </div>
                </div>
                <div class="col-auto">
                    @if($reunion->puedeUnirse())
                        <a href="{{ $reunion->enlace_reunion }}" target="_blank" class="btn btn-success btn-lg">
                            <i class="fas fa-video me-2"></i>
                            Unirse Ahora
                        </a>
                    @elseif($reunion->haTerminado())
                        <button type="button" class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-clock me-2"></i>
                            Reunión Finalizada
                        </button>
                    @else
                        <button type="button" class="btn btn-outline-light btn-lg" disabled>
                            <i class="fas fa-clock me-2"></i>
                            Próximamente
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Información de la Reunión
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Estado -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-{{ $reunion->estado_color }} fs-6 me-3">
                                {{ ucfirst($reunion->estado) }}
                            </span>
                            @if($reunion->estaEnCurso())
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="fas fa-circle text-danger"></i>
                                    En Vivo
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Descripción -->
                    @if($reunion->descripcion)
                    <div class="mb-4">
                        <h5>Descripción</h5>
                        <p class="text-muted">{{ $reunion->descripcion }}</p>
                    </div>
                    @endif

                    <!-- Fecha y Hora -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-primary text-white me-3">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <div class="font-weight-medium">Fecha</div>
                                    <div class="text-muted">{{ $reunion->fecha_formateada }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-success text-white me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <div class="font-weight-medium">Hora</div>
                                    <div class="text-muted">{{ $reunion->hora_formateada }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Duración y Tipo -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-info text-white me-3">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div>
                                    <div class="font-weight-medium">Duración</div>
                                    <div class="text-muted">{{ $reunion->duracion_formateada }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-warning text-white me-3">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div>
                                    <div class="font-weight-medium">Tipo</div>
                                    <div class="text-muted">{{ ucfirst($reunion->tipo) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Plataforma -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-dark text-white me-3">
                                    <i class="{{ $reunion->plataforma_icono }}"></i>
                                </div>
                                <div>
                                    <div class="font-weight-medium">Plataforma</div>
                                    <div class="text-muted">{{ ucfirst($reunion->plataforma) }}</div>
                                </div>
                            </div>
                        </div>
                        @if($reunion->sala)
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-secondary text-white me-3">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div>
                                    <div class="font-weight-medium">Sala</div>
                                    <div class="text-muted">{{ $reunion->sala->nombre }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Enlace de la reunión (solo si puede unirse) -->
                    @if($reunion->puedeUnirse())
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div>
                                <i class="fas fa-link me-2"></i>
                            </div>
                            <div>
                                <h4 class="alert-title">Enlace de la Reunión</h4>
                                <div class="text-muted">
                                    <p>Puedes unirte directamente usando este enlace:</p>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $reunion->enlace_reunion }}" readonly id="enlace-reunion">
                                        <button class="btn btn-outline-primary" type="button" onclick="copiarEnlace()">
                                            <i class="fas fa-copy me-1"></i>
                                            Copiar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Organizador -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie me-2"></i>
                        Organizador
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-primary text-white me-3">
                            {{ strtoupper(substr($reunion->creador->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-weight-medium">{{ $reunion->creador->name }}</div>
                            <div class="text-muted">{{ $reunion->creador->email }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle me-2"></i>
                        Instrucciones
                    </h3>
                </div>
                <div class="card-body">
                    <div class="steps">
                        <div class="step-item">
                            <div class="h4 m-0">1</div>
                            <div>Haz clic en "Unirse Ahora" cuando esté disponible</div>
                        </div>
                        <div class="step-item">
                            <div class="h4 m-0">2</div>
                            <div>Se abrirá una nueva ventana con la plataforma seleccionada</div>
                        </div>
                        <div class="step-item">
                            <div class="h4 m-0">3</div>
                            <div>Permitir acceso al micrófono y cámara si es necesario</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tiempo restante -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock me-2"></i>
                        Tiempo
                    </h3>
                </div>
                <div class="card-body text-center">
                    @if($reunion->puedeUnirse())
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <div class="h4">¡Disponible!</div>
                            <div class="text-muted">Puedes unirte ahora</div>
                        </div>
                    @elseif($reunion->haTerminado())
                        <div class="text-muted">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <div class="h4">Finalizada</div>
                            <div>La reunión ha terminado</div>
                        </div>
                    @else
                        <div class="text-info">
                            <i class="fas fa-hourglass-start fa-2x mb-2"></i>
                            <div class="h4" id="countdown">Calculando...</div>
                            <div class="text-muted">Hasta el inicio</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Botón unirse
    const btnUnirse = document.getElementById('btn-unirse');
    if (btnUnirse) {
        btnUnirse.addEventListener('click', function() {
            const reunionId = this.dataset.reunionId;
            unirseReunion(reunionId);
        });
    }

    // Countdown timer
    @if(!$reunion->puedeUnirse() && !$reunion->haTerminado())
    const fechaReunion = new Date('{{ $reunion->fecha_hora }}');
    updateCountdown();
    setInterval(updateCountdown, 1000);
    
    function updateCountdown() {
        const ahora = new Date();
        const diferencia = fechaReunion - ahora;
        
        if (diferencia <= 0) {
            document.getElementById('countdown').textContent = '¡Ya disponible!';
            setTimeout(() => location.reload(), 1000);
            return;
        }
        
        const days = Math.floor(diferencia / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diferencia % (1000 * 60)) / 1000);
        
        let countdown = '';
        if (days > 0) countdown += `${days}d `;
        if (hours > 0) countdown += `${hours}h `;
        countdown += `${minutes}m ${seconds}s`;
        
        document.getElementById('countdown').textContent = countdown;
    }
    @endif
});

function unirseReunion(reunionId) {
    const btn = document.getElementById('btn-unirse');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Conectando...';
    
    fetch(`/alumnos/reuniones/${reunionId}/unirse`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Conectando!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.open(data.enlace, '_blank');
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión. Por favor, intenta de nuevo.'
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function copiarEnlace() {
    const enlaceInput = document.getElementById('enlace-reunion');
    enlaceInput.select();
    document.execCommand('copy');
    
    Swal.fire({
        icon: 'success',
        title: '¡Copiado!',
        text: 'El enlace ha sido copiado al portapapeles',
        timer: 1500,
        showConfirmButton: false
    });
}
</script>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem;
    padding: 2rem;
    margin-bottom: 2rem;
}

.steps .step-item {
    display: flex;
    margin-bottom: 1rem;
}

.steps .step-item .h4 {
    min-width: 2rem;
    height: 2rem;
    background: var(--tblr-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 0.875rem;
}

.avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: 600;
}

.avatar.avatar-sm {
    width: 2rem;
    height: 2rem;
    font-size: 0.75rem;
}

.avatar.avatar-lg {
    width: 3rem;
    height: 3rem;
    font-size: 1rem;
}
</style>
@endsection
