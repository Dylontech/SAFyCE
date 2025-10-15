@extends('tablar::page')

@section('title', 'Mis Tareas')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-checklist me-2"></i>
                    Mis Tareas
                </h2>
                <div class="text-muted mt-1">
                    Tareas asignadas para {{ $alumno->Grupo }} - {{ $alumno->semestre }}° semestre
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('alumnos_user.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Portal Estudiantil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Resumen de tareas -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $tareas->where('estado', 'activa')->where('fecha_entrega', '>=', now())->count() }}</h3>
                        <p class="card-text">Tareas Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-warning text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $tareas->where('fecha_entrega', '<', now())->where('estado', 'activa')->count() }}</h3>
                        <p class="card-text">Tareas Vencidas</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $tareas->where('fecha_entrega', '>=', now())->where('fecha_entrega', '<=', now()->addDays(3))->count() }}</h3>
                        <p class="card-text">Por Vencer (3 días)</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $tareas->total() }}</h3>
                        <p class="card-text">Total de Tareas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4 bg-secondary">
            <div class="card-body">
                <form method="GET" action="{{ route('estudiantes.tareas') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-light">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="pendientes" {{ request('estado') === 'pendientes' ? 'selected' : '' }}>Pendientes</option>
                            <option value="vencidas" {{ request('estado') === 'vencidas' ? 'selected' : '' }}>Vencidas</option>
                            <option value="activa" {{ request('estado') === 'activa' ? 'selected' : '' }}>Activa</option>
                            <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-light">Materia</label>
                        <select name="materia_id" class="form-select">
                            <option value="">Todas las materias</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ request('materia_id') == $materia->id ? 'selected' : '' }}>
                                    {{ $materia->materia }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
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
                        <label class="form-label text-light">Buscar</label>
                        <input type="text" name="buscar" class="form-control" 
                               placeholder="Título o descripción..." 
                               value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i>
                        </button>
                        <a href="{{ route('estudiantes.tareas') }}" class="btn btn-outline-light ms-2">
                            <i class="ti ti-x"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de tareas -->
        <div class="row">
            @forelse($tareas as $tarea)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 {{ $tarea->estaVencida() ? 'border-danger' : ($tarea->diasRestantes() <= 3 ? 'border-warning' : 'border-success') }}">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge bg-{{ $tarea->tipo === 'examen' ? 'danger' : ($tarea->tipo === 'proyecto' ? 'info' : 'primary') }}">
                                    {{ $tipos[$tarea->tipo] ?? $tarea->tipo }}
                                </span>
                                @if($tarea->estaVencida())
                                    <span class="badge bg-danger">Vencida</span>
                                @elseif($tarea->diasRestantes() <= 3)
                                    <span class="badge bg-warning">Por vencer</span>
                                @else
                                    <span class="badge bg-success">{{ $tarea->diasRestantes() }} días</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $tarea->titulo }}</h5>
                            <p class="card-text text-muted small">
                                <i class="ti ti-book me-1"></i>{{ $tarea->materia->materia }}
                            </p>
                            <p class="card-text text-muted small">
                                <i class="ti ti-user me-1"></i>{{ $tarea->maestro->name }}
                            </p>
                            <p class="card-text">
                                {{ Str::limit($tarea->descripcion, 100) }}
                            </p>
                            <div class="row text-muted small">
                                <div class="col-6">
                                    <i class="ti ti-calendar me-1"></i>
                                    Entrega: {{ $tarea->fecha_entrega->format('d/m/Y H:i') }}
                                </div>
                                <div class="col-6 text-end">
                                    <i class="ti ti-star me-1"></i>
                                    {{ $tarea->puntos_totales }} pts
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('estudiantes.tareas.show', $tarea) }}" class="btn btn-sm btn-primary me-2">
                                        <i class="ti ti-eye me-1"></i>
                                        Ver Detalles
                                    </a>
                                    @if($tarea->archivo_adjunto)
                                        <a href="{{ route('estudiantes.tareas.descargar', $tarea) }}" 
                                           class="btn btn-sm btn-outline-secondary me-2" 
                                           title="Descargar archivo adjunto">
                                            <i class="ti ti-paperclip"></i>
                                        </a>
                                    @endif
                                </div>
                                <div>
                                    @php
                                        $estadoEntrega = $entregas[$tarea->id] ?? 'pendiente';
                                    @endphp
                                    @if($estadoEntrega === 'pendiente')
                                        @if($tarea->estaVencida())
                                            <span class="badge bg-danger">
                                                <i class="ti ti-alert-triangle me-1"></i>
                                                Vencida
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="ti ti-clock me-1"></i>
                                                Pendiente
                                            </span>
                                        @endif
                                    @elseif($estadoEntrega === 'entregada')
                                        <span class="badge bg-success">
                                            <i class="ti ti-check me-1"></i>
                                            Entregada
                                        </span>
                                    @elseif($estadoEntrega === 'tarde')
                                        <span class="badge bg-orange">
                                            <i class="ti ti-clock-x me-1"></i>
                                            Entrega tardía
                                        </span>
                                    @elseif($estadoEntrega === 'calificada')
                                        <span class="badge bg-info">
                                            <i class="ti ti-star me-1"></i>
                                            Calificada
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-checklist" style="font-size: 4rem; color: #ccc;"></i>
                        </div>
                        <p class="empty-title">No hay tareas disponibles</p>
                        <p class="empty-subtitle text-muted">
                            No se encontraron tareas para los filtros seleccionados.
                        </p>
                        @if(request()->hasAny(['estado', 'materia_id', 'tipo', 'buscar']))
                            <div class="empty-action">
                                <a href="{{ route('estudiantes.tareas') }}" class="btn btn-primary">
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
        @if($tareas->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $tareas->appends(request()->query())->links() }}
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
    .border-danger {
        border-left: 4px solid #dc3545 !important;
    }
    .border-warning {
        border-left: 4px solid #ffc107 !important;
    }
    .border-success {
        border-left: 4px solid #28a745 !important;
    }
</style>
@endsection
@endsection
