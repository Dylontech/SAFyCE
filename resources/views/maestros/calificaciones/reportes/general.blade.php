@extends('tablar::page')

@section('title', 'Reportes de Calificaciones')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-chart-bar me-2"></i>
                    Reportes de Calificaciones
                </h2>
                <div class="text-muted mt-1">Visualiza estadísticas y reportes de las calificaciones</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('maestros.calificaciones.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver a Calificaciones
                    </a>
                    <button onclick="window.print()" class="btn btn-info">
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
        <!-- Filtros de reporte -->
        <div class="card mb-4 bg-secondary">
            <div class="card-body">
                <form method="GET" action="{{ route('maestros.calificaciones.reportes') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-light">Materia</label>
                        <select name="materia_id" class="form-select">
                            <option value="">Todas las materias</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ request('materia_id') == $materia->id ? 'selected' : '' }}>
                                    {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label class="form-label text-light">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search me-1"></i>
                                Generar Reporte
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Estadísticas generales -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0">{{ number_format(85.5, 1) }}</div>
                                <div class="text-white-50">Promedio General</div>
                            </div>
                            <i class="ti ti-chart-line fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0">{{ $materias->count() }}</div>
                                <div class="text-white-50">Materias Activas</div>
                            </div>
                            <i class="ti ti-book fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0">12</div>
                                <div class="text-white-50">Estudiantes Activos</div>
                            </div>
                            <i class="ti ti-users fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0">48</div>
                                <div class="text-white-50">Total Evaluaciones</div>
                            </div>
                            <i class="ti ti-star fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Distribución de calificaciones -->
            <div class="col-lg-8">
                <div class="card bg-dark text-light">
                    <div class="card-header bg-gradient-primary">
                        <h3 class="card-title text-white">
                            <i class="ti ti-chart-pie me-2"></i>
                            Distribución de Calificaciones por Materia
                        </h3>
                    </div>
                    <div class="card-body">
                        @foreach($materias as $materia)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-info">{{ $materia->nombre }}</h6>
                                    <span class="text-muted">Promedio: 87.5</span>
                                </div>
                                
                                <!-- Barra de progreso simulada -->
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: 60%" title="Excelente (80-100)"></div>
                                    <div class="progress-bar bg-warning" style="width: 25%" title="Regular (60-79)"></div>
                                    <div class="progress-bar bg-danger" style="width: 15%" title="Reprobado (0-59)"></div>
                                </div>
                                
                                <div class="row text-center">
                                    <div class="col">
                                        <small class="text-success">
                                            <strong>15</strong> Excelente
                                        </small>
                                    </div>
                                    <div class="col">
                                        <small class="text-warning">
                                            <strong>6</strong> Regular
                                        </small>
                                    </div>
                                    <div class="col">
                                        <small class="text-danger">
                                            <strong>4</strong> Reprobado
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)<hr class="border-secondary">@endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Estadísticas detalladas -->
            <div class="col-lg-4">
                <!-- Top estudiantes -->
                <div class="card mb-4 bg-dark text-light">
                    <div class="card-header bg-gradient-success">
                        <h3 class="card-title text-white">
                            <i class="ti ti-trophy me-2"></i>
                            Mejores Estudiantes
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="list-group-item bg-transparent border-secondary text-light">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="badge text-white
                                                @if($i == 1) bg-warning
                                                @elseif($i == 2) bg-secondary
                                                @elseif($i == 3) bg-orange
                                                @else bg-primary
                                                @endif">
                                                {{ $i }}°
                                            </span>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">Estudiante {{ $i }}</div>
                                            <div class="text-muted small">Matrícula: 202{{ 4-$i }}001{{ $i }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-success font-weight-medium">{{ 95 - ($i-1)*2 }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Evaluaciones por tipo -->
                <div class="card bg-dark text-light">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title text-white">
                            <i class="ti ti-chart-donut me-2"></i>
                            Evaluaciones por Tipo
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            @php
                                $tipos = [
                                    ['nombre' => 'Tareas', 'cantidad' => 18, 'color' => 'primary'],
                                    ['nombre' => 'Exámenes Parciales', 'cantidad' => 12, 'color' => 'danger'],
                                    ['nombre' => 'Proyectos', 'cantidad' => 8, 'color' => 'warning'],
                                    ['nombre' => 'Prácticas', 'cantidad' => 6, 'color' => 'success'],
                                    ['nombre' => 'Participación', 'cantidad' => 4, 'color' => 'purple']
                                ];
                            @endphp
                            
                            @foreach($tipos as $tipo)
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-{{ $tipo['color'] }} text-white me-2">{{ $tipo['cantidad'] }}</span>
                                        <span>{{ $tipo['nombre'] }}</span>
                                    </div>
                                    <div class="progress" style="width: 60px; height: 8px;">
                                        <div class="progress-bar bg-{{ $tipo['color'] }}" 
                                             style="width: {{ ($tipo['cantidad'] / 20) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de rendimiento por estudiante -->
        <div class="card mt-4 bg-dark text-light">
            <div class="card-header bg-gradient-warning">
                <h3 class="card-title text-white">
                    <i class="ti ti-table me-2"></i>
                    Rendimiento Detallado por Estudiante
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead class="bg-secondary">
                            <tr>
                                <th>Estudiante</th>
                                <th>Matrícula</th>
                                <th>Tareas</th>
                                <th>Exámenes</th>
                                <th>Proyectos</th>
                                <th>Participación</th>
                                <th>Promedio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 10; $i++)
                                @php
                                    $promedio = rand(60, 100);
                                @endphp
                                <tr>
                                    <td>
                                        <strong>Estudiante {{ $i }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted">202{{ 4-($i%3) }}00{{ $i }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary text-white">{{ rand(75, 95) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger text-white">{{ rand(65, 90) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-white">{{ rand(70, 95) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success text-white">{{ rand(80, 100) }}</span>
                                    </td>
                                    <td>
                                        <strong class="
                                            @if($promedio >= 80) text-success
                                            @elseif($promedio >= 60) text-warning
                                            @else text-danger
                                            @endif">
                                            {{ $promedio }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge text-white
                                            @if($promedio >= 80) bg-success
                                            @elseif($promedio >= 60) bg-warning
                                            @else bg-danger
                                            @endif">
                                            @if($promedio >= 80) Excelente
                                            @elseif($promedio >= 60) Regular
                                            @else Reprobado
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
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

@media print {
    .page-header, .btn-list, .card-header {
        background: none !important;
        color: black !important;
    }
    .bg-dark, .bg-secondary {
        background: white !important;
        color: black !important;
    }
    .text-light {
        color: black !important;
    }
}
</style>
@endsection
