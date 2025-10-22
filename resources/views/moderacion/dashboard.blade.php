@extends('tablar::page')

@section('title', 'Dashboard de Moderación')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-danger">
                    <i class="ti ti-shield-check me-2"></i>
                    Dashboard de Moderación
                </h2>
                <div class="text-muted mt-1">Panel de control para la moderación de contenido estudiantil</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('moderacion.reportes.index') }}" class="btn btn-warning">
                        <i class="ti ti-flag me-1"></i>
                        Ver Reportes
                    </a>
                    <a href="{{ route('moderacion.bloqueados.index') }}" class="btn btn-secondary">
                        <i class="ti ti-ban me-1"></i>
                        Usuarios Bloqueados
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Estadísticas principales -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-warning">{{ $estadisticas['reportes_pendientes'] }}</div>
                                <div class="text-muted">Reportes Pendientes</div>
                            </div>
                            <i class="ti ti-flag fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('moderacion.reportes.index') }}?estado=pendiente" class="text-decoration-none">
                            <small class="text-muted">Ver reportes pendientes <i class="ti ti-arrow-right"></i></small>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-info">{{ $estadisticas['reportes_en_revision'] }}</div>
                                <div class="text-muted">En Revisión</div>
                            </div>
                            <i class="ti ti-eye fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('moderacion.reportes.index') }}?estado=en_revision" class="text-decoration-none">
                            <small class="text-muted">Ver en revisión <i class="ti ti-arrow-right"></i></small>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-danger">{{ $estadisticas['usuarios_bloqueados'] }}</div>
                                <div class="text-muted">Usuarios Bloqueados</div>
                            </div>
                            <i class="ti ti-ban fs-1 text-danger opacity-50"></i>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('moderacion.bloqueados.index') }}" class="text-decoration-none">
                            <small class="text-muted">Gestionar bloqueos <i class="ti ti-arrow-right"></i></small>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-success">{{ $estadisticas['acciones_hoy'] }}</div>
                                <div class="text-muted">Acciones Hoy</div>
                            </div>
                            <i class="ti ti-activity fs-1 text-success opacity-50"></i>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('moderacion.historial.index') }}?fecha_desde={{ today()->format('Y-m-d') }}" class="text-decoration-none">
                            <small class="text-muted">Ver actividad <i class="ti ti-arrow-right"></i></small>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-deck row-cards">
            <!-- Reportes Recientes -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-flag me-2 text-warning"></i>
                            Reportes Recientes
                        </h3>
                        <div class="card-actions">
                            <a href="{{ route('moderacion.reportes.index') }}" class="btn btn-sm btn-outline-primary">
                                Ver todos
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($reportesRecientes->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($reportesRecientes as $reporte)
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="badge text-white
                                                    @if($reporte->estado === 'pendiente') bg-warning
                                                    @elseif($reporte->estado === 'en_revision') bg-info
                                                    @elseif($reporte->estado === 'resuelto') bg-success
                                                    @else bg-danger
                                                    @endif">
                                                    {{ ucfirst($reporte->estado) }}
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $reporte->tipo_reporte)) }}</strong>
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $reporte->descripcion ? Str::limit($reporte->descripcion, 60) : 'Sin descripción' }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="text-muted small">{{ $reporte->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="col-auto">
                                                <a href="{{ route('moderacion.reportes.show', $reporte) }}" class="btn btn-sm btn-outline-primary">
                                                    Ver
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-flag-off fs-1 mb-3"></i>
                                <p>No hay reportes recientes</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel de Control -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-settings me-2 text-primary"></i>
                            Panel de Control
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('moderacion.reportes.index') }}" class="btn btn-warning">
                                <i class="ti ti-flag me-2"></i>
                                Gestionar Reportes
                            </a>
                            <a href="{{ route('moderacion.bloqueados.index') }}" class="btn btn-danger">
                                <i class="ti ti-ban me-2"></i>
                                Usuarios Bloqueados
                            </a>
                            <a href="{{ route('moderacion.historial.index') }}" class="btn btn-info">
                                <i class="ti ti-history me-2"></i>
                                Historial de Acciones
                            </a>
                            <a href="{{ route('moderacion.estadisticas.index') }}" class="btn btn-success">
                                <i class="ti ti-chart-bar me-2"></i>
                                Estadísticas
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Reportes por Tipo -->
                @if($reportesPorTipo->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Reportes por Tipo (30 días)</h3>
                    </div>
                    <div class="card-body">
                        @foreach($reportesPorTipo as $tipo => $cantidad)
                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">{{ ucfirst(str_replace('_', ' ', $tipo)) }}</span>
                                    <span class="badge bg-secondary">{{ $cantidad }}</span>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar" style="width: {{ ($cantidad / $reportesPorTipo->max()) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actividad Reciente -->
        @if($actividadReciente->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-activity me-2 text-success"></i>
                            Actividad Reciente de Moderación
                        </h3>
                        <div class="card-actions">
                            <a href="{{ route('moderacion.historial.index') }}" class="btn btn-sm btn-outline-primary">
                                Ver historial completo
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach($actividadReciente as $actividad)
                                <div class="timeline-event">
                                    <div class="timeline-event-icon bg-{{ $actividad->accion === 'bloquear_usuario' ? 'danger' : ($actividad->accion === 'eliminar_publicacion' ? 'warning' : 'info') }}">
                                        <i class="ti ti-{{ $actividad->accion === 'bloquear_usuario' ? 'ban' : ($actividad->accion === 'eliminar_publicacion' ? 'trash' : 'eye') }}"></i>
                                    </div>
                                    <div class="card timeline-event-card">
                                        <div class="card-body">
                                            <div class="text-muted float-end">{{ $actividad->created_at->diffForHumans() }}</div>
                                            <h4>{{ $actividad->moderador->name ?? 'Moderador' }}</h4>
                                            <p class="text-muted">
                                                <strong>{{ ucfirst(str_replace('_', ' ', $actividad->accion)) }}</strong>
                                                @if($actividad->alumnoAfectado)
                                                    - Estudiante: {{ $actividad->alumnoAfectado->nombres }} {{ $actividad->alumnoAfectado->apellidos }}
                                                @endif
                                            </p>
                                            @if($actividad->motivo)
                                                <div class="text-secondary">
                                                    <i class="ti ti-quote"></i>
                                                    {{ Str::limit($actividad->motivo, 100) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline:before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--tblr-border-color);
}

.timeline-event {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-event-icon {
    position: absolute;
    left: -2.25rem;
    top: 0.5rem;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.75rem;
    z-index: 1;
}

.timeline-event-card {
    margin-left: 1rem;
}

.card-link:hover {
    transform: translateY(-2px);
    transition: transform 0.2s;
}
</style>
@endpush
@endsection