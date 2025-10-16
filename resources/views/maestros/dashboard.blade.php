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
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0">{{ $totalTareas }}</div>
                                <div class="text-white-50">Total Tareas</div>
                            </div>
                            <i class="ti ti-clipboard fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0">{{ $tareasActivas }}</div>
                                <div class="text-white-50">Tareas Activas</div>
                            </div>
                            <i class="ti ti-clock fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0">{{ $tareasVencidas }}</div>
                                <div class="text-white-50">Tareas Vencidas</div>
                            </div>
                            <i class="ti ti-alert-triangle fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0">{{ $totalCalificaciones }}</div>
                                <div class="text-white-50">Calificaciones</div>
                            </div>
                            <i class="ti ti-star fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-deck row-cards">
            <!-- Tareas próximas a vencer -->
            <div class="col-md-6">
                <div class="card bg-dark text-light">
                    <div class="card-header bg-gradient-primary">
                        <h3 class="card-title text-white">
                            <i class="ti ti-clock-hour-4 me-2"></i>
                            Tareas Próximas a Vencer
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($tareasProximasVencer->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($tareasProximasVencer as $tarea)
                                    <div class="list-group-item bg-transparent border-secondary text-light">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="badge text-white
                                                    @if($tarea->diasRestantes() <= 1) bg-danger
                                                    @elseif($tarea->diasRestantes() <= 3) bg-warning
                                                    @else bg-info
                                                    @endif">
                                                    {{ $tarea->diasRestantes() }}d
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
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('maestros.tareas.index') }}" class="btn btn-outline-light btn-sm">
                            Ver todas las tareas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tareas recientes -->
            <div class="col-md-6">
                <div class="card bg-dark text-light">
                    <div class="card-header bg-gradient-success">
                        <h3 class="card-title text-white">
                            <i class="ti ti-clipboard-list me-2"></i>
                            Tareas Recientes
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($tareasRecientes->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($tareasRecientes as $tarea)
                                    <div class="list-group-item bg-transparent border-secondary text-light">
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
                <div class="card bg-secondary text-light">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title text-white">
                            <i class="ti ti-book me-2"></i>
                            Mis Materias
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($materias->count() > 0)
                            <div class="row">
                                @foreach($materias as $materia)
                                    <div class="col-md-6 mb-3">
                                        <div class="card bg-primary">
                                            <div class="card-body">
                                                <h5 class="card-title text-white">{{ $materia->nombre }}</h5>
                                                <p class="card-text text-white-50">{{ $materia->descripcion }}</p>
                                                <div class="btn-group w-100">
                                                    <a href="{{ route('maestros.tareas.index') }}?materia={{ $materia->id }}" 
                                                       class="btn btn-outline-light btn-sm">Tareas</a>
                                                    <a href="{{ route('maestros.calificaciones.index') }}?materia={{ $materia->id }}" 
                                                       class="btn btn-outline-light btn-sm">Calificaciones</a>
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
                <div class="card bg-secondary text-light">
                    <div class="card-header bg-gradient-warning">
                        <h3 class="card-title text-white">
                            <i class="ti ti-activity me-2"></i>
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
                                <i class="ti ti-star me-2"></i>
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

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
