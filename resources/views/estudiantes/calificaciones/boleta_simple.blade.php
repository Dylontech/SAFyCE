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
</style>
@endsection
@endsection
