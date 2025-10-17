@extends('tablar::page')

@section('title', 'Historial de Moderación')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-info">
                    <i class="ti ti-history me-2"></i>
                    Historial de Moderación
                </h2>
                <div class="text-muted mt-1">Registro completo de todas las acciones de moderación</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('moderacion.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Filtros</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('moderacion.historial.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Moderador</label>
                            <select name="moderador_id" class="form-select">
                                <option value="">Todos los moderadores</option>
                                @foreach(\App\Models\User::role(['maestro', 'control_escolar'])->get() as $moderador)
                                    <option value="{{ $moderador->id }}" {{ request('moderador_id') == $moderador->id ? 'selected' : '' }}>
                                        {{ $moderador->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Acción</label>
                            <select name="accion" class="form-select">
                                <option value="">Todas las acciones</option>
                                <option value="eliminar_publicacion" {{ request('accion') === 'eliminar_publicacion' ? 'selected' : '' }}>Eliminar Publicación</option>
                                <option value="eliminar_comentario" {{ request('accion') === 'eliminar_comentario' ? 'selected' : '' }}>Eliminar Comentario</option>
                                <option value="bloquear_usuario" {{ request('accion') === 'bloquear_usuario' ? 'selected' : '' }}>Bloquear Usuario</option>
                                <option value="desbloquear_usuario" {{ request('accion') === 'desbloquear_usuario' ? 'selected' : '' }}>Desbloquear Usuario</option>
                                <option value="advertencia" {{ request('accion') === 'advertencia' ? 'selected' : '' }}>Advertencia</option>
                                <option value="revision_reporte" {{ request('accion') === 'revision_reporte' ? 'selected' : '' }}>Revisar Reporte</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Fecha Desde</label>
                            <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Fecha Hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search me-1"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('moderacion.historial.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-refresh me-1"></i>
                            Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Historial -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Registro de Actividad ({{ $historial->total() }} acciones)
                </h3>
            </div>
            <div class="card-body p-0">
                @if($historial->count() > 0)
                    <div class="timeline timeline-vertical">
                        @foreach($historial as $actividad)
                            <div class="timeline-event">
                                <div class="timeline-event-icon bg-{{ 
                                    $actividad->accion === 'bloquear_usuario' ? 'danger' : 
                                    ($actividad->accion === 'desbloquear_usuario' ? 'success' : 
                                    ($actividad->accion === 'eliminar_publicacion' ? 'warning' : 
                                    ($actividad->accion === 'eliminar_comentario' ? 'orange' : 'info'))) 
                                }}">
                                    <i class="ti ti-{{ 
                                        $actividad->accion === 'bloquear_usuario' ? 'ban' : 
                                        ($actividad->accion === 'desbloquear_usuario' ? 'lock-open' : 
                                        ($actividad->accion === 'eliminar_publicacion' ? 'trash' : 
                                        ($actividad->accion === 'eliminar_comentario' ? 'message-x' : 
                                        ($actividad->accion === 'advertencia' ? 'alert-triangle' : 'eye')))) 
                                    }}"></i>
                                </div>
                                <div class="card timeline-event-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h4 class="mb-0">{{ ucfirst(str_replace('_', ' ', $actividad->accion)) }}</h4>
                                                    <span class="badge bg-{{ 
                                                        $actividad->accion === 'bloquear_usuario' ? 'danger' : 
                                                        ($actividad->accion === 'desbloquear_usuario' ? 'success' : 
                                                        ($actividad->accion === 'eliminar_publicacion' ? 'warning' : 
                                                        ($actividad->accion === 'eliminar_comentario' ? 'orange' : 'info'))) 
                                                    }}">
                                                        {{ ucfirst(str_replace('_', ' ', $actividad->accion)) }}
                                                    </span>
                                                </div>
                                                
                                                <div class="text-muted mb-2">
                                                    <i class="ti ti-user me-1"></i>
                                                    <strong>Moderador:</strong> {{ $actividad->moderador->name ?? 'Desconocido' }}
                                                    
                                                    @if($actividad->alumnoAfectado)
                                                        <br>
                                                        <i class="ti ti-user-check me-1"></i>
                                                        <strong>Estudiante:</strong> {{ $actividad->alumnoAfectado->nombres }} {{ $actividad->alumnoAfectado->apellidos }}
                                                    @endif
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <strong>Motivo:</strong> {{ $actividad->motivo }}
                                                </div>
                                                
                                                @if($actividad->detalles)
                                                    <div class="mb-2">
                                                        <strong>Detalles:</strong> {{ $actividad->detalles }}
                                                    </div>
                                                @endif
                                                
                                                @if($actividad->contenido_afectado_type)
                                                    <div class="mb-2">
                                                        <strong>Contenido Afectado:</strong> {{ $actividad->tipo_contenido }}
                                                    </div>
                                                @endif
                                                
                                                @if($actividad->ip_moderador)
                                                    <div class="text-muted small">
                                                        <i class="ti ti-world me-1"></i>
                                                        IP: {{ $actividad->ip_moderador }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="col-md-4 text-end">
                                                <div class="text-muted">
                                                    <i class="ti ti-calendar me-1"></i>
                                                    {{ $actividad->created_at->format('d/m/Y H:i:s') }}
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $actividad->created_at->diffForHumans() }}
                                                </div>
                                                
                                                @if($actividad->datos_adicionales)
                                                    <button type="button" class="btn btn-sm btn-outline-info mt-2" data-bs-toggle="modal" data-bs-target="#datosModal{{ $actividad->id }}">
                                                        <i class="ti ti-info-circle me-1"></i>
                                                        Ver Datos
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-history-off fs-1 text-muted mb-3"></i>
                        <h3 class="text-muted">No hay actividad registrada</h3>
                        <p class="text-muted">No se encontraron acciones de moderación que coincidan con los filtros.</p>
                    </div>
                @endif
            </div>
            
            @if($historial->hasPages())
                <div class="card-footer">
                    {{ $historial->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modales para mostrar datos adicionales -->
@foreach($historial as $actividad)
    @if($actividad->datos_adicionales)
        <div class="modal fade" id="datosModal{{ $actividad->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Datos Adicionales - {{ ucfirst(str_replace('_', ' ', $actividad->accion)) }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Acción:</div>
                            <div class="col-sm-9">{{ ucfirst(str_replace('_', ' ', $actividad->accion)) }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Fecha:</div>
                            <div class="col-sm-9">{{ $actividad->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Moderador:</div>
                            <div class="col-sm-9">{{ $actividad->moderador->name ?? 'Desconocido' }}</div>
                        </div>
                        
                        @if($actividad->alumnoAfectado)
                            <div class="row mb-3">
                                <div class="col-sm-3 text-muted">Estudiante:</div>
                                <div class="col-sm-9">{{ $actividad->alumnoAfectado->nombres }} {{ $actividad->alumnoAfectado->apellidos }}</div>
                            </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Datos Respaldados:</div>
                            <div class="col-sm-9">
                                <pre class="bg-light p-3 rounded"><code>{{ json_encode($actividad->datos_adicionales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline:before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--tblr-border-color);
}

.timeline-event {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-event-icon {
    position: absolute;
    left: -2.5rem;
    top: 1rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    z-index: 1;
    border: 3px solid white;
    box-shadow: 0 0 0 3px var(--tblr-border-color);
}

.timeline-event-card {
    margin-left: 1rem;
}

.timeline-vertical .timeline-event:last-child .timeline-event-card {
    margin-bottom: 0;
}

pre code {
    font-size: 0.75rem;
    color: var(--tblr-muted);
}
</style>
@endpush
@endsection