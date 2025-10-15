@extends('tablar::page')

@section('title', 'Mis Calificaciones')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-report-analytics me-2"></i>
                    Mis Calificaciones
                </h2>
                <div class="text-muted mt-1">
                    Registro académico de {{ $alumno->Nombre }} - {{ $alumno->Grupo }}
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('alumnos_user.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Portal Estudiantil
                    </a>
                    <a href="{{ route('estudiantes.calificaciones.reportes') }}" class="btn btn-info">
                        <i class="ti ti-chart-pie me-1"></i>
                        Reportes
                    </a>
                    <a href="{{ route('estudiantes.calificaciones.boleta') }}" class="btn btn-primary">
                        <i class="ti ti-file-text me-1"></i>
                        Boleta
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Resumen de calificaciones -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ number_format($promedioGeneral, 1) }}</h3>
                        <p class="card-text">Promedio General</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $totalCalificaciones }}</h3>
                        <p class="card-text">Total Evaluaciones</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $aprobadas }}</h3>
                        <p class="card-text">Evaluaciones Aprobadas</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-danger text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $reprobadas }}</h3>
                        <p class="card-text">Evaluaciones Reprobadas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4 bg-secondary">
            <div class="card-body">
                <form method="GET" action="{{ route('estudiantes.calificaciones') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-light">Período Escolar</label>
                        <select name="periodo_escolar" class="form-select">
                            <option value="">Todos los períodos</option>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo }}" {{ request('periodo_escolar') === $periodo ? 'selected' : '' }}>
                                    {{ $periodo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-light">Parcial</label>
                        <select name="parcial" class="form-select">
                            <option value="">Todos los parciales</option>
                            <option value="1" {{ request('parcial') === '1' ? 'selected' : '' }}>Primer Parcial</option>
                            <option value="2" {{ request('parcial') === '2' ? 'selected' : '' }}>Segundo Parcial</option>
                            <option value="3" {{ request('parcial') === '3' ? 'selected' : '' }}>Tercer Parcial</option>
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
                    <div class="col-md-3">
                        <label class="form-label text-light">Tipo de Evaluación</label>
                        <select name="tipo_evaluacion" class="form-select">
                            <option value="">Todos los tipos</option>
                            @foreach($tipos as $valor => $etiqueta)
                                <option value="{{ $valor }}" {{ request('tipo_evaluacion') === $valor ? 'selected' : '' }}>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i>
                        </button>
                        <a href="{{ route('estudiantes.calificaciones') }}" class="btn btn-outline-light ms-2">
                            <i class="ti ti-x"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de calificaciones -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Historial de Calificaciones</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Materia</th>
                            <th>Maestro</th>
                            <th class="d-none d-md-table-cell">Tipo</th>
                            <th class="d-none d-sm-table-cell">Parcial</th>
                            <th>Calificación</th>
                            <th class="d-none d-lg-table-cell">Puntos</th>
                            <th>Estado</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calificaciones as $calificacion)
                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $calificacion->fecha_evaluacion ? $calificacion->fecha_evaluacion->format('d/m/Y') : $calificacion->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-muted small d-md-none">
                                        {{ $tipos[$calificacion->tipo_evaluacion] ?? $calificacion->tipo_evaluacion }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $calificacion->materia->materia }}</div>
                                    <div class="text-muted small d-sm-none">
                                        @if($calificacion->parcial)
                                            Parcial {{ $calificacion->parcial }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">{{ $calificacion->maestro->name }}</div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge bg-{{ $calificacion->tipo_evaluacion === 'examen_final' ? 'danger' : ($calificacion->tipo_evaluacion === 'proyecto' ? 'info' : 'primary') }}">
                                        {{ $tipos[$calificacion->tipo_evaluacion] ?? $calificacion->tipo_evaluacion }}
                                    </span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    @if($calificacion->parcial)
                                        <span class="badge bg-secondary">{{ $calificacion->parcial }}° Parcial</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold fs-4 {{ $calificacion->calificacion >= 70 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($calificacion->calificacion, 1) }}
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    @if($calificacion->puntos_obtenidos && $calificacion->puntos_totales)
                                        <div class="text-muted">
                                            {{ $calificacion->puntos_obtenidos }} / {{ $calificacion->puntos_totales }}
                                        </div>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-{{ $calificacion->calificacion >= 70 ? 'success' : 'danger' }}" 
                                                 style="width: {{ ($calificacion->puntos_obtenidos / $calificacion->puntos_totales) * 100 }}%">
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($calificacion->calificacion >= 70)
                                        <span class="badge bg-success">Aprobado</span>
                                    @else
                                        <span class="badge bg-danger">Reprobado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($calificacion->comentarios)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#comentarios-{{ $calificacion->id }}">
                                            <i class="ti ti-message"></i>
                                        </button>
                                    @endif
                                    @if($calificacion->tarea)
                                        <a href="{{ route('estudiantes.tareas.show', $calificacion->tarea) }}" 
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Ver tarea">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="empty">
                                        <div class="empty-icon">
                                            <i class="ti ti-report-analytics" style="font-size: 4rem; color: #ccc;"></i>
                                        </div>
                                        <p class="empty-title">No hay calificaciones disponibles</p>
                                        <p class="empty-subtitle text-muted">
                                            No se encontraron calificaciones para los filtros seleccionados.
                                        </p>
                                        @if(request()->hasAny(['periodo_escolar', 'parcial', 'materia_id', 'tipo_evaluacion']))
                                            <div class="empty-action">
                                                <a href="{{ route('estudiantes.calificaciones') }}" class="btn btn-primary">
                                                    <i class="ti ti-refresh"></i>
                                                    Limpiar filtros
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        @if($calificaciones->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $calificaciones->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modales para comentarios -->
@foreach($calificaciones as $calificacion)
    @if($calificacion->comentarios)
        <div class="modal fade" id="comentarios-{{ $calificacion->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ti ti-message me-2"></i>
                            Comentarios del Maestro
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Materia:</strong> {{ $calificacion->materia->materia }}<br>
                            <strong>Maestro:</strong> {{ $calificacion->maestro->name }}<br>
                            <strong>Calificación:</strong> 
                            <span class="{{ $calificacion->calificacion >= 70 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($calificacion->calificacion, 1) }}
                            </span>
                        </div>
                        <div class="bg-light p-3 rounded">
                            {{ $calificacion->comentarios }}
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

@section('css')
<style>
    .progress-sm {
        height: 4px;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .fs-4 {
        font-size: 1.25rem !important;
    }
</style>
@endsection
@endsection
