@extends('tablar::page')

@section('title', 'Reuniones Virtuales')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-video me-2"></i>
                    Reuniones Virtuales
                </h2>
                <div class="text-muted mt-1">
                    Todas las reuniones programadas
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('alumnos_user.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Portal Estudiantil
                    </a>
                    <a href="{{ route('estudiantes.reuniones.activas') }}" class="btn btn-danger">
                        <i class="ti ti-live-photo me-1"></i>
                        Ver Activas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Acceso rápido a reuniones activas -->
        @php
            $reunionesActuales = App\Models\Reunion::with(['creador', 'sala'])
                                                  ->activas()
                                                  ->get()
                                                  ->filter(function ($reunion) {
                                                      return $reunion->puedeUnirse();
                                                  });
        @endphp
        
        @if($reunionesActuales->count() > 0)
            <div class="alert alert-success d-flex align-items-center mb-4">
                <div class="flex-grow-1">
                    <h4 class="alert-heading">
                        <i class="ti ti-broadcast me-2"></i>
                        ¡Hay {{ $reunionesActuales->count() }} reunión(es) disponible(s) ahora!
                    </h4>
                    <p class="mb-0">Puedes unirte a las reuniones que están en curso o empezando pronto.</p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('estudiantes.reuniones.activas') }}" class="btn btn-success">
                        <i class="ti ti-external-link me-1"></i>
                        Ver Reuniones Activas
                    </a>
                </div>
            </div>
        @endif

        <!-- Filtros -->
        <div class="card mb-4 bg-secondary">
            <div class="card-body">
                <form method="GET" action="{{ route('estudiantes.reuniones') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-light">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="">Todos los tipos</option>
                            @foreach($tipos as $valor => $etiqueta)
                                <option value="{{ $valor }}" {{ request('tipo') === $valor ? 'selected' : '' }}>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-light">Plataforma</label>
                        <select name="plataforma" class="form-select">
                            <option value="">Todas las plataformas</option>
                            @foreach($plataformas as $valor => $etiqueta)
                                <option value="{{ $valor }}" {{ request('plataforma') === $valor ? 'selected' : '' }}>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-light">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ti ti-search"></i>
                        </button>
                        <a href="{{ route('estudiantes.reuniones') }}" class="btn btn-outline-light">
                            <i class="ti ti-x"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de reuniones -->
        <div class="row">
            @forelse($reuniones as $reunion)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 {{ $reunion->puedeUnirse() ? 'border-success' : ($reunion->haTerminado() ? 'border-secondary' : 'border-info') }}">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge bg-{{ $reunion->tipo === 'clase' ? 'primary' : ($reunion->tipo === 'examen' ? 'danger' : 'info') }}">
                                    {{ $tipos[$reunion->tipo] ?? $reunion->tipo }}
                                </span>
                                @if($reunion->puedeUnirse())
                                    <span class="badge bg-success">Disponible</span>
                                @elseif($reunion->haTerminado())
                                    <span class="badge bg-secondary">Finalizada</span>
                                @else
                                    <span class="badge bg-info">Programada</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $reunion->titulo }}</h5>
                            <p class="card-text text-muted small">
                                <i class="ti ti-user me-1"></i>{{ $reunion->creador->name }}
                            </p>
                            @if($reunion->sala)
                                <p class="card-text text-muted small">
                                    <i class="ti ti-door me-1"></i>{{ $reunion->sala->nombre }}
                                </p>
                            @endif
                            <p class="card-text">
                                {{ Str::limit($reunion->descripcion, 100) }}
                            </p>
                            <div class="row text-muted small">
                                <div class="col-6">
                                    <i class="ti ti-calendar me-1"></i>
                                    {{ $reunion->fecha_formateada }}
                                </div>
                                <div class="col-6">
                                    <i class="ti ti-clock me-1"></i>
                                    {{ $reunion->hora_formateada }}
                                </div>
                            </div>
                            <div class="row text-muted small mt-1">
                                <div class="col-6">
                                    <i class="ti ti-hourglass me-1"></i>
                                    {{ $reunion->duracion_formateada }}
                                </div>
                                <div class="col-6">
                                    <i class="{{ $reunion->plataforma_icono }} me-1"></i>
                                    {{ $reunion->plataforma_nombre }}
                                </div>
                            </div>
                            @if($reunion->participantes_actuales)
                                <div class="text-muted small mt-1">
                                    <i class="ti ti-users me-1"></i>
                                    {{ $reunion->participantes_actuales }} participante(s)
                                    @if($reunion->max_participantes)
                                        / {{ $reunion->max_participantes }} máx.
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light">
                            @if($reunion->puedeUnirse())
                                <form action="{{ route('estudiantes.reuniones.unirse', $reunion) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="ti ti-external-link me-1"></i>
                                        Unirse Ahora
                                    </button>
                                </form>
                            @elseif($reunion->haTerminado())
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="ti ti-check me-1"></i>
                                    Reunión Finalizada
                                </button>
                            @else
                                <button class="btn btn-outline-info w-100" disabled>
                                    <i class="ti ti-clock me-1"></i>
                                    Programada para {{ $reunion->fecha_hora_formatted }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-video-off" style="font-size: 4rem; color: #ccc;"></i>
                        </div>
                        <p class="empty-title">No hay reuniones disponibles</p>
                        <p class="empty-subtitle text-muted">
                            No se encontraron reuniones para los filtros seleccionados.
                        </p>
                        @if(request()->hasAny(['tipo', 'plataforma', 'fecha']))
                            <div class="empty-action">
                                <a href="{{ route('estudiantes.reuniones') }}" class="btn btn-primary">
                                    <i class="ti ti-refresh"></i>
                                    Limpiar filtros
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($reuniones->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $reuniones->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@section('css')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .border-success {
        border-left: 4px solid #28a745 !important;
    }
    .border-secondary {
        border-left: 4px solid #6c757d !important;
    }
    .border-info {
        border-left: 4px solid #17a2b8 !important;
    }
</style>
@endsection
@endsection
