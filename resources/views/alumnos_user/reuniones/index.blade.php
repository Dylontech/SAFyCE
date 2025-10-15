@extends('layouts.app-alumno')

@section('title', 'Reuniones Virtuales')

@section('content')
<div class="container-fluid">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Page Header -->
    <div class="page-header d-print-none mb-4">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <i class="fas fa-video me-2"></i>
                        Reuniones Virtuales
                    </h2>
                    <div class="text-muted mt-1">
                        Aquí puedes ver y unirte a las reuniones virtuales programadas
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="filtro-fecha" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" id="filtro-tipo">
                        <option value="">Todos los tipos</option>
                        <option value="clase">Clase Regular</option>
                        <option value="tutorial">Tutoría</option>
                        <option value="reunion">Reunión de Padres</option>
                        <option value="examen">Examen</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Plataforma</label>
                    <select class="form-select" id="filtro-plataforma">
                        <option value="">Todas las plataformas</option>
                        <option value="meet">Google Meet</option>
                        <option value="zoom">Zoom</option>
                        <option value="teams">Microsoft Teams</option>
                        <option value="webex">Cisco Webex</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="filtro-buscar" placeholder="Título o descripción...">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="filtrarReuniones()">
                        <i class="fas fa-search me-1"></i>
                        Buscar
                    </button>
                    <button type="button" class="btn btn-outline-secondary ms-2" onclick="limpiarFiltros()">
                        <i class="fas fa-times me-1"></i>
                        Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reuniones de Hoy -->
    @if($reunionesHoy->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-day me-2 text-primary"></i>
                Reuniones de Hoy
            </h3>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($reunionesHoy as $reunion)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 reunion-card {{ $reunion->estaEnCurso() ? 'border-warning' : '' }}" data-reunion-id="{{ $reunion->id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="badge bg-{{ $reunion->estado_color }} mb-2">
                                    {{ ucfirst($reunion->tipo) }}
                                </div>
                                @if($reunion->estaEnCurso())
                                    <span class="badge bg-warning text-dark">En Curso</span>
                                @elseif($reunion->haTerminado())
                                    <span class="badge bg-secondary">Finalizada</span>
                                @endif
                            </div>
                            
                            <h5 class="card-title">{{ $reunion->titulo }}</h5>
                            
                            @if($reunion->descripcion)
                                <p class="card-text text-muted small">{{ Str::limit($reunion->descripcion, 80) }}</p>
                            @endif
                            
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock me-2 text-muted"></i>
                                <span class="text-muted">{{ $reunion->hora_formateada }} ({{ $reunion->duracion_formateada }})</span>
                            </div>
                            
                            <div class="d-flex align-items-center mb-2">
                                <i class="{{ $reunion->plataforma_icono }} me-2 text-muted"></i>
                                <span class="text-muted">{{ ucfirst($reunion->plataforma) }}</span>
                            </div>
                            
                            @if($reunion->sala)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-door-open me-2 text-muted"></i>
                                <span class="text-muted">{{ $reunion->sala->nombre }}</span>
                            </div>
                            @endif
                            
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user me-2 text-muted"></i>
                                <span class="text-muted">{{ $reunion->creador->name }}</span>
                            </div>
                            
                            @if($reunion->puedeUnirse())
                                <a href="{{ $reunion->enlace_reunion }}" target="_blank" class="btn btn-success w-100">
                                    <i class="fas fa-video me-2"></i>
                                    Unirse a la Reunión
                                </a>
                            @elseif($reunion->haTerminado())
                                <button type="button" class="btn btn-secondary w-100" disabled>
                                    <i class="fas fa-clock me-2"></i>
                                    Reunión Finalizada
                                </button>
                            @else
                                <button type="button" class="btn btn-outline-primary w-100" disabled>
                                    <i class="fas fa-clock me-2"></i>
                                    Disponible en {{ \Carbon\Carbon::parse($reunion->fecha_hora)->diffForHumans() }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Próximas Reuniones -->
    @if($reunionesProximas->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-alt me-2 text-info"></i>
                Próximas Reuniones
            </h3>
        </div>
        <div class="card-body">
            <div class="row g-3" id="proximas-reuniones">
                @foreach($reunionesProximas as $reunion)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 reunion-card" data-reunion-id="{{ $reunion->id }}">
                        <div class="card-body">
                            <div class="badge bg-{{ $reunion->estado_color }} mb-2">
                                {{ ucfirst($reunion->tipo) }}
                            </div>
                            
                            <h5 class="card-title">{{ $reunion->titulo }}</h5>
                            
                            @if($reunion->descripcion)
                                <p class="card-text text-muted small">{{ Str::limit($reunion->descripcion, 80) }}</p>
                            @endif
                            
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                <span class="text-muted">{{ $reunion->fecha_formateada }}</span>
                            </div>
                            
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock me-2 text-muted"></i>
                                <span class="text-muted">{{ $reunion->hora_formateada }} ({{ $reunion->duracion_formateada }})</span>
                            </div>
                            
                            <div class="d-flex align-items-center mb-2">
                                <i class="{{ $reunion->plataforma_icono }} me-2 text-muted"></i>
                                <span class="text-muted">{{ ucfirst($reunion->plataforma) }}</span>
                            </div>
                            
                            @if($reunion->sala)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-door-open me-2 text-muted"></i>
                                <span class="text-muted">{{ $reunion->sala->nombre }}</span>
                            </div>
                            @endif
                            
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user me-2 text-muted"></i>
                                <span class="text-muted">{{ $reunion->creador->name }}</span>
                            </div>
                            
                            <button type="button" class="btn btn-outline-info w-100">
                                <i class="fas fa-info-circle me-2"></i>
                                Programada para {{ \Carbon\Carbon::parse($reunion->fecha_hora)->diffForHumans() }}
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Mensaje si no hay reuniones -->
    @if($reunionesHoy->count() === 0 && $reunionesProximas->count() === 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-video fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay reuniones programadas</h4>
            <p class="text-muted">No se encontraron reuniones virtuales para los próximos días.</p>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners para botones de unirse
    document.querySelectorAll('.btn-unirse').forEach(button => {
        button.addEventListener('click', function() {
            const reunionId = this.dataset.reunionId;
            unirseReunion(reunionId);
        });
    });
});

function unirseReunion(reunionId) {
    // Cambiar estado del botón
    const btn = document.querySelector(`[data-reunion-id="${reunionId}"].btn-unirse`);
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
                // Abrir enlace en nueva ventana
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
        // Restaurar botón
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function filtrarReuniones() {
    const filtros = {
        fecha: document.getElementById('filtro-fecha').value,
        tipo: document.getElementById('filtro-tipo').value,
        plataforma: document.getElementById('filtro-plataforma').value,
        buscar: document.getElementById('filtro-buscar').value
    };
    
    const params = new URLSearchParams();
    Object.keys(filtros).forEach(key => {
        if (filtros[key]) {
            params.append(key, filtros[key]);
        }
    });
    
    fetch(`/alumnos/reuniones/buscar?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            // Actualizar la vista con los resultados filtrados
            mostrarReuniones(data.reuniones);
        })
        .catch(error => {
            console.error('Error al filtrar:', error);
        });
}

function limpiarFiltros() {
    document.getElementById('filtro-fecha').value = '{{ date('Y-m-d') }}';
    document.getElementById('filtro-tipo').value = '';
    document.getElementById('filtro-plataforma').value = '';
    document.getElementById('filtro-buscar').value = '';
    location.reload();
}

function mostrarReuniones(reuniones) {
    // Implementar la actualización de la vista con los resultados filtrados
    // Por simplicidad, recargar la página con los filtros aplicados
    console.log('Reuniones filtradas:', reuniones);
}

// Auto-refresh cada 30 segundos para actualizar el estado de las reuniones
setInterval(() => {
    location.reload();
}, 30000);
</script>

<style>
.reunion-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.reunion-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.border-warning {
    border-left: 4px solid #ffc107 !important;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem;
    padding: 2rem;
    margin-bottom: 2rem;
}

.page-header .page-title,
.page-header .text-muted {
    color: white !important;
}
</style>
@endsection
