@extends('tablar::page')

@section('title', 'Detalles de la Tarea')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-eye me-2"></i>
                    Detalles de la Tarea
                </h2>
                <div class="text-muted mt-1">{{ $tarea->titulo }}</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('maestros.tareas.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver a Tareas
                    </a>
                    <a href="{{ route('maestros.tareas.edit', $tarea) }}" class="btn btn-warning">
                        <i class="ti ti-edit me-1"></i>
                        Editar
                    </a>
                    <form action="{{ route('maestros.tareas.toggle-estado', $tarea) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $tarea->estado === 'activa' ? 'danger' : 'success' }}">
                            <i class="ti ti-{{ $tarea->estado === 'activa' ? 'x' : 'check' }} me-1"></i>
                            {{ $tarea->estado === 'activa' ? 'Cancelar' : 'Activar' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Información principal -->
            <div class="col-lg-8">
                <!-- Detalles de la tarea -->
                <div class="card mb-4 bg-dark text-light">
                    <div class="card-header bg-gradient-primary">
                        <h3 class="card-title text-white">
                            <i class="ti ti-info-circle me-2"></i>
                            Información de la Tarea
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-light">Título</label>
                                    <div class="h5 text-primary">{{ $tarea->titulo }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-light">Estado</label>
                                    <div>
                                        <span class="badge badge-lg
                                            @if($tarea->estado === 'activa') bg-success
                                            @elseif($tarea->estado === 'vencida') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($tarea->estado) }}
                                        </span>
                                        @if($tarea->estaVencida())
                                            <span class="badge bg-warning ms-2">Vencida</span>
                                        @elseif($tarea->diasRestantes() <= 3 && $tarea->estado === 'activa')
                                            <span class="badge bg-warning ms-2">{{ $tarea->diasRestantes() }}d restantes</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-light">Descripción</label>
                            <div class="bg-secondary p-3 rounded">
                                {{ $tarea->descripcion }}
                            </div>
                        </div>
                        
                        @if($tarea->instrucciones)
                            <div class="mb-3">
                                <label class="form-label text-light">Instrucciones Adicionales</label>
                                <div class="bg-secondary p-3 rounded">
                                    {{ $tarea->instrucciones }}
                                </div>
                            </div>
                        @endif
                        
                        @if($tarea->archivo_adjunto)
                            <div class="mb-3">
                                <label class="form-label text-light">Archivo Adjunto</label>
                                <div class="card bg-secondary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="ti ti-file-text me-2"></i>
                                                <span>{{ basename($tarea->archivo_adjunto) }}</span>
                                            </div>
                                            <a href="{{ Storage::url($tarea->archivo_adjunto) }}" 
                                               class="btn btn-sm btn-outline-light" target="_blank">
                                                <i class="ti ti-download me-1"></i>
                                                Descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Calificaciones -->
                <div class="card bg-dark text-light">
                    <div class="card-header bg-gradient-success d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-white mb-0">
                            <i class="ti ti-star me-2"></i>
                            Calificaciones ({{ $calificaciones->total() }})
                        </h3>
                        <a href="{{ route('maestros.calificaciones.create') }}?tarea_id={{ $tarea->id }}" 
                           class="btn btn-sm btn-outline-light">
                            <i class="ti ti-plus me-1"></i>
                            Nueva Calificación
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if($calificaciones->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0">
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Calificación</th>
                                            <th>Puntos</th>
                                            <th>Fecha</th>
                                            <th>Comentarios</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($calificaciones as $calificacion)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong>{{ $calificacion->alumno->nombres }} {{ $calificacion->alumno->apellidos }}</strong>
                                                        <div class="text-muted small">{{ $calificacion->alumno->matricula }}</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-lg
                                                        @if($calificacion->calificacion >= 80) bg-success
                                                        @elseif($calificacion->calificacion >= 60) bg-warning
                                                        @else bg-danger
                                                        @endif">
                                                        {{ $calificacion->calificacion }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $calificacion->puntos_obtenidos ?? '-' }}/{{ $calificacion->puntos_totales ?? $tarea->puntos_totales }}
                                                </td>
                                                <td>
                                                    {{ $calificacion->fecha_evaluacion->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ $calificacion->comentarios ? Str::limit($calificacion->comentarios, 30) : '-' }}
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('maestros.calificaciones.edit', $calificacion) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Editar">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($calificaciones->hasPages())
                                <div class="card-footer bg-transparent">
                                    {{ $calificaciones->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="ti ti-star-off fs-1 text-muted mb-3"></i>
                                <h4 class="text-muted">No hay calificaciones registradas</h4>
                                <p class="text-muted">Comienza agregando las primeras calificaciones</p>
                                <a href="{{ route('maestros.calificaciones.create') }}?tarea_id={{ $tarea->id }}" 
                                   class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i>
                                    Agregar Calificación
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel lateral -->
            <div class="col-lg-4">
                <!-- Información académica -->
                <div class="card mb-4 bg-dark text-light">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title text-white">
                            <i class="ti ti-school me-2"></i>
                            Información Académica
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-light">Materia</label>
                            <div class="h6 text-info">{{ $tarea->materia->nombre }}</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-light">Grupo</label>
                                    <div class="h6">{{ $tarea->grupo }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-light">Semestre</label>
                                    <div class="h6">{{ $tarea->semestre }}°</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-light">Tipo</label>
                            <div>
                                <span class="badge 
                                    @if($tarea->tipo === 'examen') bg-danger
                                    @elseif($tarea->tipo === 'proyecto') bg-warning
                                    @elseif($tarea->tipo === 'practica') bg-success
                                    @else bg-primary
                                    @endif">
                                    {{ ucfirst($tarea->tipo) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-light">Puntos Totales</label>
                            <div class="h5 text-warning">{{ $tarea->puntos_totales }} pts</div>
                        </div>
                    </div>
                </div>

                <!-- Fechas importantes -->
                <div class="card mb-4 bg-dark text-light">
                    <div class="card-header bg-gradient-warning">
                        <h3 class="card-title text-white">
                            <i class="ti ti-calendar me-2"></i>
                            Fechas Importantes
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-light">Fecha de Asignación</label>
                            <div class="h6">{{ $tarea->fecha_asignacion->format('d/m/Y H:i') }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-light">Fecha de Entrega</label>
                            <div class="h6 {{ $tarea->estaVencida() ? 'text-danger' : 'text-success' }}">
                                {{ $tarea->fecha_entrega->format('d/m/Y H:i') }}
                            </div>
                            @if($tarea->estado === 'activa')
                                <div class="text-muted small mt-1">
                                    @if($tarea->estaVencida())
                                        Vencida hace {{ $tarea->fecha_entrega->diffForHumans() }}
                                    @else
                                        {{ $tarea->fecha_entrega->diffForHumans() }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="card bg-dark text-light">
                    <div class="card-header bg-gradient-success">
                        <h3 class="card-title text-white">
                            <i class="ti ti-chart-bar me-2"></i>
                            Estadísticas
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h4 text-primary">{{ $calificaciones->total() }}</div>
                                <div class="text-muted small">Calificaciones</div>
                            </div>
                            <div class="col-6">
                                <div class="h4 text-success">
                                    {{ $calificaciones->count() > 0 ? round($calificaciones->avg('calificacion'), 1) : 0 }}
                                </div>
                                <div class="text-muted small">Promedio</div>
                            </div>
                        </div>
                        
                        @if($calificaciones->count() > 0)
                            <hr class="border-secondary">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-success">
                                        {{ $calificaciones->where('calificacion', '>=', 80)->count() }}
                                    </div>
                                    <div class="text-muted small">Excelente</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-warning">
                                        {{ $calificaciones->whereBetween('calificacion', [60, 79])->count() }}
                                    </div>
                                    <div class="text-muted small">Regular</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-danger">
                                        {{ $calificaciones->where('calificacion', '<', 60)->count() }}
                                    </div>
                                    <div class="text-muted small">Reprobado</div>
                                </div>
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
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
}
</style>
@endsection
