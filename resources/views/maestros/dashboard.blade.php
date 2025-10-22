@extends('tablar::page')

@section('title', 'Dashboard - Maestros')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-dashboard me-2"></i>
                    Dashboard - Maestros
                </h2>
                <div class="text-muted mt-1">Bienvenido {{ Auth::user()->name }}</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('maestros.tareas.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        Nueva Tarea
                    </a>
                    <a href="{{ route('maestros.calificaciones.create') }}" class="btn btn-success">
                        <i class="ti ti-plus me-1"></i>
                        Nueva Calificación
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
                                <div class="h1 mb-0 text-primary">{{ $totalTareas }}</div>
                                <div class="text-muted">Total Tareas</div>
                            </div>
                            <i class="ti ti-clipboard fs-1 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-success">{{ $tareasActivas }}</div>
                                <div class="text-muted">Tareas Activas</div>
                            </div>
                            <i class="ti ti-clock fs-1 text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-warning">{{ $tareasVencidas }}</div>
                                <div class="text-muted">Tareas Vencidas</div>
                            </div>
                            <i class="ti ti-alert-triangle fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-info">{{ $totalCalificaciones }}</div>
                                <div class="text-muted">Calificaciones</div>
                            </div>
                            <i class="ti ti-star fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-deck row-cards">
            <!-- Tareas próximas a vencer -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-clock-hour-4 me-2 text-primary"></i>
                            Tareas Próximas a Vencer
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($tareasProximasVencer->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($tareasProximasVencer as $tarea)
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="badge text-white
                                                    @if($tarea->diasRestantes() <= 1) bg-danger
                                                    @elseif($tarea->diasRestantes() <= 3) bg-warning
                                                    @else bg-info
                                                    @endif">
                                                    {{ number_format($tarea->diasRestantes(), 1) }}d
                                                </span>
                                            </div>
                                            <div class="col text-truncate">
                                                <strong>{{ $tarea->titulo }}</strong>
                                                <div class="text-muted small">{{ $tarea->materia->nombre }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="text-muted">{{ $tarea->fecha_entrega->format('d/m') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-check-circle fs-1 mb-3"></i>
                                <p>No hay tareas próximas a vencer</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('maestros.tareas.index') }}" class="btn btn-outline-primary btn-sm">
                            Ver todas las tareas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tareas recientes -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-clipboard-list me-2 text-success"></i>
                            Tareas Recientes
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($tareasRecientes->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($tareasRecientes as $tarea)
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="badge text-white
                                                    @if($tarea->estado === 'activa') bg-success
                                                    @elseif($tarea->estado === 'vencida') bg-danger
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst($tarea->estado) }}
                                                </span>
                                            </div>
                                            <div class="col text-truncate">
                                                <strong>{{ $tarea->titulo }}</strong>
                                                <div class="text-muted small">{{ $tarea->materia->nombre }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="text-muted">{{ $tarea->created_at->format('d/m') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-plus-circle fs-1 mb-3"></i>
                                <p>No hay tareas creadas aún</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Materias y accesos rápidos -->
        <div class="row row-deck row-cards mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-book me-2 text-info"></i>
                            Mis Materias
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($materias->count() > 0)
                            <div class="row">
                                @foreach($materias as $materia)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">{{ $materia->nombre }}</h5>
                                                <p class="card-text text-muted">{{ $materia->descripcion }}</p>
                                                <div class="btn-group w-100">
                                                    <a href="{{ route('maestros.tareas.index') }}?materia={{ $materia->id }}" 
                                                       class="btn btn-outline-primary btn-sm">Tareas</a>
                                                    <a href="{{ route('maestros.calificaciones.index') }}?materia={{ $materia->id }}" 
                                                       class="btn btn-outline-success btn-sm">Calificaciones</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-book-off fs-1 mb-3"></i>
                                <p>No tienes materias asignadas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-activity me-2 text-warning"></i>
                            Acciones Rápidas
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('maestros.tareas.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>
                                Crear Nueva Tarea
                            </a>
                            <a href="{{ route('maestros.calificaciones.create') }}" class="btn btn-success">
                                <i class="ti ti-star me-2 text-success"></i>
                                Registrar Calificación
                            </a>
                            <a href="{{ route('maestros.calificaciones.reportes') }}" class="btn btn-info">
                                <i class="ti ti-chart-bar me-2"></i>
                                Ver Reportes
                            </a>
                            <a href="{{ route('maestros.tareas.index') }}" class="btn btn-warning">
                                <i class="ti ti-list me-2"></i>
                                Gestionar Tareas
                            </a>
                            <a href="{{ route('moderacion.dashboard') }}" class="btn btn-danger">
                                <i class="ti ti-shield-check me-2"></i>
                                Moderación Social
                            </a>
                        </div>
                        
                        @if($calificacionesPendientes > 0)
                            <div class="alert alert-warning mt-3" role="alert">
                                <i class="ti ti-alert-triangle me-2"></i>
                                <strong>{{ $calificacionesPendientes }}</strong> tareas pendientes de calificar
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
