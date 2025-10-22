@extends('tablar::page')

@section('title', 'Reportes de Calificaciones')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Análisis Académico
                </div>
                <h2 class="page-title text-primary">
                    <i class="ti ti-chart-pie me-2"></i>
                    Reportes de Calificaciones
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('estudiantes.calificaciones') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Calificaciones
                    </a>
                    <a href="{{ route('estudiantes.calificaciones.boleta') }}" class="btn btn-primary">
                        <i class="ti ti-file-text me-1"></i>
                        Ver Boleta
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Estadísticas generales -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Resumen General</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-6">
                                <div class="display-4 fw-bold text-primary">
                                    {{ number_format($promedioGeneral, 1) }}
                                </div>
                                <div class="text-muted">Promedio General</div>
                            </div>
                            <div class="col-6">
                                <div class="display-4 fw-bold text-info">
                                    {{ $totalCalificaciones }}
                                </div>
                                <div class="text-muted">Total Evaluaciones</div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar bg-{{ $promedioGeneral >= 70 ? 'success' : 'danger' }}" 
                                 style="width: {{ min($promedioGeneral, 100) }}%">
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-{{ $promedioGeneral >= 90 ? 'success' : ($promedioGeneral >= 80 ? 'warning' : ($promedioGeneral >= 70 ? 'info' : 'danger')) }} fs-6">
                                {{ $promedioGeneral >= 90 ? 'Excelente' : ($promedioGeneral >= 80 ? 'Muy Bueno' : ($promedioGeneral >= 70 ? 'Bueno' : 'Necesita Mejorar')) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rendimiento Académico</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h3 text-success">{{ $calificacionesPorMateria->sum(function($item) { return $item['aprobadas']; }) }}</div>
                                <div class="text-muted small">Evaluaciones Aprobadas</div>
                            </div>
                            <div class="col-6">
                                <div class="h3 text-danger">{{ $calificacionesPorMateria->sum(function($item) { return $item['reprobadas']; }) }}</div>
                                <div class="text-muted small">Evaluaciones Reprobadas</div>
                            </div>
                        </div>
                        @php
                            $totalEvaluaciones = $calificacionesPorMateria->sum(function($item) { return $item['total']; });
                            $totalAprobadas = $calificacionesPorMateria->sum(function($item) { return $item['aprobadas']; });
                            $porcentajeAprobacion = $totalEvaluaciones > 0 ? ($totalAprobadas / $totalEvaluaciones) * 100 : 0;
                        @endphp
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $porcentajeAprobacion }}%"></div>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted">{{ number_format($porcentajeAprobacion, 1) }}% de Aprobación</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calificaciones por materia -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-book me-2"></i>
                    Rendimiento por Materia
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th class="text-center">Promedio</th>
                            <th class="text-center d-none d-md-table-cell">Total</th>
                            <th class="text-center d-none d-sm-table-cell">Aprobadas</th>
                            <th class="text-center d-none d-sm-table-cell">Reprobadas</th>
                            <th class="text-center">Estado</th>
                            <th class="w-25">Progreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calificacionesPorMateria as $materia => $datos)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $materia }}</div>
                                    <div class="text-muted small d-md-none">
                                        {{ $datos['total'] }} evaluaciones
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="fw-bold fs-5 {{ $datos['promedio'] >= 70 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($datos['promedio'], 1) }}
                                    </div>
                                </td>
                                <td class="text-center d-none d-md-table-cell">
                                    <span class="badge bg-secondary">{{ $datos['total'] }}</span>
                                </td>
                                <td class="text-center d-none d-sm-table-cell">
                                    <span class="badge bg-success">{{ $datos['aprobadas'] }}</span>
                                </td>
                                <td class="text-center d-none d-sm-table-cell">
                                    <span class="badge bg-danger">{{ $datos['reprobadas'] }}</span>
                                </td>
                                <td class="text-center">
                                    @if($datos['promedio'] >= 90)
                                        <span class="badge bg-success">Excelente</span>
                                    @elseif($datos['promedio'] >= 80)
                                        <span class="badge bg-warning">Muy Bueno</span>
                                    @elseif($datos['promedio'] >= 70)
                                        <span class="badge bg-info">Bueno</span>
                                    @else
                                        <span class="badge bg-danger">Reprobado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $datos['promedio'] >= 70 ? 'success' : 'danger' }}" 
                                             style="width: {{ min($datos['promedio'], 100) }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ number_format($datos['promedio'], 1) }}%</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">No hay datos de calificaciones por materia disponibles.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Calificaciones por período -->
        @if($calificacionesPorPeriodo->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-calendar me-2"></i>
                        Rendimiento por Período Escolar
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($calificacionesPorPeriodo as $periodo => $datos)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">{{ $periodo }}</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="h3 {{ $datos['promedio'] >= 70 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($datos['promedio'], 1) }}
                                        </div>
                                        <div class="text-muted small mb-2">Promedio del Período</div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar bg-{{ $datos['promedio'] >= 70 ? 'success' : 'danger' }}" 
                                                 style="width: {{ min($datos['promedio'], 100) }}%">
                                            </div>
                                        </div>
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="fw-bold text-info">{{ $datos['total'] }}</div>
                                                <div class="small text-muted">Total</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="fw-bold text-success">{{ $datos['aprobadas'] }}</div>
                                                <div class="small text-muted">Aprob.</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="fw-bold text-danger">{{ $datos['reprobadas'] }}</div>
                                                <div class="small text-muted">Reprob.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Calificaciones por tipo de evaluación -->
        @if($calificacionesPorTipo->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-clipboard-list me-2"></i>
                        Rendimiento por Tipo de Evaluación
                    </h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Tipo de Evaluación</th>
                                <th class="text-center">Promedio</th>
                                <th class="text-center d-none d-md-table-cell">Total</th>
                                <th class="text-center d-none d-sm-table-cell">Aprobadas</th>
                                <th class="text-center d-none d-sm-table-cell">Reprobadas</th>
                                <th class="w-25">Rendimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calificacionesPorTipo as $tipo => $datos)
                                <tr>
                                    <td>
                                        <div class="fw-bold">
                                            @switch($tipo)
                                                @case('tarea')
                                                    <i class="ti ti-file-text me-2 text-primary"></i>Tareas
                                                    @break
                                                @case('examen_parcial')
                                                    <i class="ti ti-file-check me-2 text-warning"></i>Exámenes Parciales
                                                    @break
                                                @case('examen_final')
                                                    <i class="ti ti-file-certificate me-2 text-danger"></i>Exámenes Finales
                                                    @break
                                                @case('proyecto')
                                                    <i class="ti ti-bulb me-2 text-info"></i>Proyectos
                                                    @break
                                                @case('participacion')
                                                    <i class="ti ti-users me-2 text-success"></i>Participación
                                                    @break
                                                @case('practica')
                                                    <i class="ti ti-tool me-2 text-secondary"></i>Prácticas
                                                    @break
                                                @default
                                                    {{ ucfirst($tipo) }}
                                            @endswitch
                                        </div>
                                        <div class="text-muted small d-md-none">
                                            {{ $datos['total'] }} evaluaciones
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-bold fs-5 {{ $datos['promedio'] >= 70 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($datos['promedio'], 1) }}
                                        </div>
                                    </td>
                                    <td class="text-center d-none d-md-table-cell">
                                        <span class="badge bg-secondary">{{ $datos['total'] }}</span>
                                    </td>
                                    <td class="text-center d-none d-sm-table-cell">
                                        <span class="badge bg-success">{{ $datos['aprobadas'] }}</span>
                                    </td>
                                    <td class="text-center d-none d-sm-table-cell">
                                        <span class="badge bg-danger">{{ $datos['reprobadas'] }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-{{ $datos['promedio'] >= 70 ? 'success' : 'danger' }}" 
                                                 style="width: {{ min($datos['promedio'], 100) }}%">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ $datos['total'] > 0 ? number_format(($datos['aprobadas'] / $datos['total']) * 100, 1) : 0 }}% aprobación
                                        </small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

@section('css')
<style>
    .progress {
        background-color: #e9ecef;
    }
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .fs-5 {
        font-size: 1.125rem !important;
    }
    .fs-6 {
        font-size: 1rem !important;
    }
</style>
@endsection
@endsection
