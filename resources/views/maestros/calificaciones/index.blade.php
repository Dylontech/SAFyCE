@extends('tablar::page')

@section('title', 'Gestión de Calificaciones')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-star me-2"></i>
                    Gestión de Calificaciones
                </h2>
                <div class="text-muted mt-1">Administra las calificaciones de tus estudiantes</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('maestros.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver al Dashboard
                    </a>
                    <a href="{{ route('maestros.calificaciones.reportes') }}" class="btn btn-info">
                        <i class="ti ti-chart-bar me-1"></i>
                        Reportes
                    </a>
                    <a href="{{ route('maestros.calificaciones.create') }}" class="btn btn-primary">
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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filtros -->
        <div class="card mb-4 bg-secondary">
            <div class="card-body">
                <form method="GET" action="{{ route('maestros.calificaciones.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-light">Tipo de Evaluación</label>
                        <select name="tipo_evaluacion" class="form-select">
                            <option value="">Todos los tipos</option>
                            <option value="tarea" {{ request('tipo_evaluacion') === 'tarea' ? 'selected' : '' }}>Tarea</option>
                            <option value="examen_parcial" {{ request('tipo_evaluacion') === 'examen_parcial' ? 'selected' : '' }}>Examen Parcial</option>
                            <option value="examen_final" {{ request('tipo_evaluacion') === 'examen_final' ? 'selected' : '' }}>Examen Final</option>
                            <option value="proyecto" {{ request('tipo_evaluacion') === 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                            <option value="participacion" {{ request('tipo_evaluacion') === 'participacion' ? 'selected' : '' }}>Participación</option>
                            <option value="practica" {{ request('tipo_evaluacion') === 'practica' ? 'selected' : '' }}>Práctica</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-light">Parcial</label>
                        <select name="parcial" class="form-select">
                            <option value="">Todos los parciales</option>
                            <option value="1" {{ request('parcial') === '1' ? 'selected' : '' }}>Primer Parcial</option>
                            <option value="2" {{ request('parcial') === '2' ? 'selected' : '' }}>Segundo Parcial</option>
                            <option value="3" {{ request('parcial') === '3' ? 'selected' : '' }}>Tercer Parcial</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-light">Búsqueda de Estudiante</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Buscar por nombre o matrícula..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-light">&nbsp;</label>
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search me-1"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('maestros.calificaciones.index') }}" class="btn btn-outline-light">
                                <i class="ti ti-refresh me-1"></i>
                                Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de calificaciones -->
        <div class="card bg-dark text-light">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title text-white">
                    <i class="ti ti-list me-2"></i>
                    Lista de Calificaciones ({{ $calificaciones->total() }})
                </h3>
            </div>
            <div class="card-body p-0">
                @if($calificaciones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead class="bg-secondary">
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Materia</th>
                                    <th>Tarea/Evaluación</th>
                                    <th>Tipo</th>
                                    <th>Calificación</th>
                                    <th>Puntos</th>
                                    <th>Parcial</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calificaciones as $calificacion)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>
                                                    @if(isset($calificacion->alumno))
                                                        {{ $calificacion->alumno->Nombre ?? $calificacion->alumno->nombres ?? 'Sin nombre' }}
                                                    @else
                                                        Sin estudiante
                                                    @endif
                                                </strong>
                                                <div class="text-muted small">
                                                    @if(isset($calificacion->alumno))
                                                        {{ $calificacion->alumno->numero_control ?? $calificacion->alumno->matricula ?? 'Sin matrícula' }}
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if(isset($calificacion->materia))
                                                <span class="badge bg-info text-white">{{ $calificacion->materia->materia ?? $calificacion->materia->nombre ?? 'Sin materia' }}</span>
                                            @else
                                                <span class="text-muted">Sin materia</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($calificacion->tarea)
                                                <div>
                                                    <strong>{{ $calificacion->tarea->titulo }}</strong>
                                                    <div class="text-muted small">{{ Str::limit($calificacion->tarea->descripcion, 30) }}</div>
                                                </div>
                                            @else
                                                <span class="text-muted">Evaluación directa</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($calificacion->tipo_evaluacion)
                                                <span class="badge text-white
                                                    @if($calificacion->tipo_evaluacion === 'examen_parcial' || $calificacion->tipo_evaluacion === 'examen_final') bg-danger
                                                    @elseif($calificacion->tipo_evaluacion === 'proyecto') bg-warning
                                                    @elseif($calificacion->tipo_evaluacion === 'practica') bg-success
                                                    @elseif($calificacion->tipo_evaluacion === 'participacion') bg-purple
                                                    @else bg-primary
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $calificacion->tipo_evaluacion)) }}
                                                </span>
                                            @else
                                                <span class="text-muted">Sin tipo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($calificacion->calificacion !== null)
                                                <span class="badge badge-lg text-white
                                                    @if($calificacion->calificacion >= 80) bg-success
                                                    @elseif($calificacion->calificacion >= 60) bg-warning
                                                    @else bg-danger
                                                    @endif">
                                                    {{ $calificacion->calificacion }}
                                                </span>
                                            @else
                                                <span class="text-muted">Sin calificación</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $calificacion->puntos_obtenidos ?? '-' }}/{{ $calificacion->puntos_totales ?? '-' }}
                                        </td>
                                        <td>
                                            @if($calificacion->parcial)
                                                <span class="badge bg-secondary text-white">{{ $calificacion->parcial }}°</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                {{ $calificacion->fecha_evaluacion ? $calificacion->fecha_evaluacion->format('d/m/Y') : 'Sin fecha' }}
                                                <div class="text-muted small">
                                                    {{ $calificacion->fecha_evaluacion ? $calificacion->fecha_evaluacion->format('H:i') : '' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($calificacion->tarea)
                                                    <a href="{{ route('maestros.tareas.show', $calificacion->tarea) }}" 
                                                       class="btn btn-sm btn-outline-info" title="Ver Tarea">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('maestros.calificaciones.edit', $calificacion) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('maestros.calificaciones.destroy', $calificacion) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('¿Estás seguro de eliminar esta calificación?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-star-off fs-1 text-muted mb-3"></i>
                        <h3 class="text-muted">No hay calificaciones registradas</h3>
                        <p class="text-muted">Comienza registrando las primeras calificaciones</p>
                        <a href="{{ route('maestros.calificaciones.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>
                            Registrar Primera Calificación
                        </a>
                    </div>
                @endif
            </div>
            
            @if($calificaciones->hasPages())
                <div class="card-footer bg-transparent">
                    {{ $calificaciones->links() }}
                </div>
            @endif
        </div>

        <!-- Estadísticas rápidas -->
        @if($calificaciones->count() > 0)
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-trophy fs-1 me-3"></i>
                                <div>
                                    <div class="h2 mb-0">{{ $calificaciones->where('calificacion', '>=', 80)->count() }}</div>
                                    <div class="text-white-50">Excelentes (80+)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-medal fs-1 me-3"></i>
                                <div>
                                    <div class="h2 mb-0">{{ $calificaciones->whereBetween('calificacion', [60, 79])->count() }}</div>
                                    <div class="text-white-50">Regulares (60-79)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-alert-triangle fs-1 me-3"></i>
                                <div>
                                    <div class="h2 mb-0">{{ $calificaciones->where('calificacion', '<', 60)->count() }}</div>
                                    <div class="text-white-50">Reprobados (<60)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-chart-line fs-1 me-3"></i>
                                <div>
                                    <div class="h2 mb-0">{{ round($calificaciones->avg('calificacion'), 1) }}</div>
                                    <div class="text-white-50">Promedio General</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
