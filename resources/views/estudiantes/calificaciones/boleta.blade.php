@extends('tablar::page')

@section('title', 'Boleta de Calificaciones')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Documento Oficial
                </div>
                <h2 class="page-title text-primary">
                    <i class="ti ti-file-text me-2"></i>
                    Boleta de Calificaciones
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('estudiantes.calificaciones') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Calificaciones
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="ti ti-printer me-1"></i>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Selector de período -->
        <div class="card mb-4 d-print-none">
            <div class="card-body">
                <form method="GET" action="{{ route('estudiantes.calificaciones.boleta') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Período Escolar</label>
                        <select name="periodo" class="form-select" onchange="this.form.submit()">
                            @foreach($periodos as $periodoOption)
                                <option value="{{ $periodoOption }}" {{ $periodo === $periodoOption ? 'selected' : '' }}>
                                    {{ $periodoOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Boleta oficial -->
        <div class="card">
            <div class="card-body p-5">
                <!-- Encabezado institucional -->
                <div class="text-center mb-5">
                    <h1 class="h2 fw-bold text-primary">Centro de Estudios Científicos y Tecnológicos</h1>
                    <h2 class="h3 text-muted">CECEyT</h2>
                    <h3 class="h4 mt-3">BOLETA DE CALIFICACIONES</h3>
                    <div class="text-muted">Período Escolar: <strong>{{ $periodo }}</strong></div>
                </div>

                <!-- Información del estudiante -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Nombre del Estudiante:</td>
                                <td>{{ $alumno->Nombre }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Número de Control:</td>
                                <td>{{ $alumno->numero_control }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">CURP:</td>
                                <td>{{ $alumno->CURP }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Especialidad:</td>
                                <td>{{ $alumno->especialidad }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Grupo:</td>
                                <td>{{ $alumno->Grupo }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Semestre:</td>
                                <td>{{ $alumno->semestre }}°</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Tabla de calificaciones -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">MATERIA</th>
                                <th class="text-center">MAESTRO</th>
                                <th class="text-center">EVALUACIONES</th>
                                <th class="text-center">PROMEDIO</th>
                                <th class="text-center">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($calificaciones as $materia => $calificacionesMateria)
                                <tr>
                                    <td class="fw-bold">{{ $materia }}</td>
                                    <td>{{ $calificacionesMateria->first()->maestro->name }}</td>
                                    <td class="text-center">
                                        <div class="d-flex flex-wrap justify-content-center gap-1">
                                            @foreach($calificacionesMateria as $cal)
                                                <span class="badge bg-{{ $cal->calificacion >= 70 ? 'success' : 'danger' }} small">
                                                    {{ number_format($cal->calificacion, 1) }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <small class="text-muted">({{ $calificacionesMateria->count() }} evaluaciones)</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-bold fs-5 {{ $promediosPorMateria[$materia] >= 70 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($promediosPorMateria[$materia], 1) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($promediosPorMateria[$materia] >= 70)
                                            <span class="badge bg-success">APROBADO</span>
                                        @else
                                            <span class="badge bg-danger">REPROBADO</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">No hay calificaciones disponibles para el período seleccionado.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($calificaciones->count() > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">PROMEDIO GENERAL:</td>
                                    <td class="text-center">
                                        <div class="fw-bold fs-4 {{ $promedioGeneral >= 70 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($promedioGeneral, 1) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($promedioGeneral >= 70)
                                            <span class="badge bg-success fs-6">APROBADO</span>
                                        @else
                                            <span class="badge bg-danger fs-6">REPROBADO</span>
                                        @endif
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <!-- Estadísticas del período -->
                @if($calificaciones->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">Estadísticas del Período</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td>Total de Materias:</td>
                                            <td class="fw-bold">{{ $calificaciones->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>Materias Aprobadas:</td>
                                            <td class="fw-bold text-success">{{ $promediosPorMateria->filter(function($promedio) { return $promedio >= 70; })->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>Materias Reprobadas:</td>
                                            <td class="fw-bold text-danger">{{ $promediosPorMateria->filter(function($promedio) { return $promedio < 70; })->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total de Evaluaciones:</td>
                                            <td class="fw-bold">{{ $calificaciones->flatten()->count() }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">Desempeño Académico</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="display-6 fw-bold {{ $promedioGeneral >= 70 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($promedioGeneral, 1) }}
                                    </div>
                                    <div class="text-muted mb-3">Promedio General</div>
                                    <div class="progress mb-3" style="height: 12px;">
                                        <div class="progress-bar bg-{{ $promedioGeneral >= 70 ? 'success' : 'danger' }}" 
                                             style="width: {{ min($promedioGeneral, 100) }}%">
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $promedioGeneral >= 90 ? 'success' : ($promedioGeneral >= 80 ? 'warning' : ($promedioGeneral >= 70 ? 'info' : 'danger')) }} fs-6">
                                        @if($promedioGeneral >= 90)
                                            EXCELENTE
                                        @elseif($promedioGeneral >= 80)
                                            MUY BUENO
                                        @elseif($promedioGeneral >= 70)
                                            BUENO
                                        @else
                                            NECESITA MEJORAR
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Escala de calificaciones -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Escala de Calificaciones</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 col-md-3">
                                        <div class="badge bg-success mb-1">90 - 100</div>
                                        <div class="small">Excelente</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="badge bg-warning mb-1">80 - 89</div>
                                        <div class="small">Muy Bueno</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="badge bg-info mb-1">70 - 79</div>
                                        <div class="small">Bueno</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="badge bg-danger mb-1">0 - 69</div>
                                        <div class="small">Reprobado</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Firmas y fecha -->
                <div class="row mt-5">
                    <div class="col-md-6 text-center">
                        <div class="border-top pt-3" style="margin-top: 80px;">
                            <strong>Coordinador Académico</strong>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="border-top pt-3" style="margin-top: 80px;">
                            <strong>Director</strong>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        Documento generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@section('css')
<style>
    @media print {
        .page-header,
        .d-print-none {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        body {
            font-size: 12px;
        }
        .table {
            font-size: 11px;
        }
        .badge {
            font-size: 10px !important;
        }
    }
    
    .fs-4 {
        font-size: 1.25rem !important;
    }
    .fs-5 {
        font-size: 1.125rem !important;
    }
    .fs-6 {
        font-size: 1rem !important;
    }
    .display-6 {
        font-size: 2rem !important;
    }
</style>
@endsection
@endsection
