@extends('tablar::page')

@section('title', 'Kardex Académico')

@section('css')
    <style>
        .kardex-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .periodo-section {
            margin-bottom: 2rem;
            border: 1px solid #3b97f2ff;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .periodo-header {
            background: #54a9feff;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #55aaffff;
            font-weight: 600;
            color: #495057;
        }
        
        .materia-row {
            transition: background-color 0.3s ease;
        }
        
        .materia-row:hover {
            background-color: #7dbeffff;
        }
        
        .badge-aprobado {
            background-color: #28a745;
            color: white;
        }
        
        .badge-reprobado {
            background-color: #dc3545;
            color: white;
        }
        
        .promedio-excellent {
            color: #42fe6eff;
            font-weight: bold;
        }
        
        .promedio-good {
            color: #007bff;
            font-weight: bold;
        }
        
        .promedio-regular {
            color: #ffc107;
            font-weight: bold;
        }
        
        .promedio-bad {
            color: #dc3545;
            font-weight: bold;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            color: #6c757d;
            font-weight: 500;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #495057;
            background-color: #5daeffff;
        }
        
        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #6cfffaff;
            border-color: #dee2e6 #dee2e6 #ffababff;
            font-weight: 600;
        }
        
        .tab-content {
            padding-top: 1rem;
        }
        
        .materia-resumen {
            background: #7dbeffff;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .materia-resumen:hover {
            background: #6db6ffff;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
    <!-- Kardex Header -->
    <div class="kardex-header">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="ti ti-school me-2"></i>Kardex Académico
                    </h1>
                    <p class="mb-0 opacity-75">Historial completo de calificaciones</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="text-white">
                        <div class="fw-bold">{{ $alumno->Nombre }}</div>
                        <div class="opacity-75">{{ $alumno->numero_control }}</div>
                        <div class="opacity-75">{{ $alumno->especialidad }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xl">
        <!-- Estadísticas generales -->
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card p-3 text-center">
                    <div class="h2 mb-1 text-primary">{{ $estadisticas['materias_cursadas'] }}</div>
                    <div class="text-muted small">Materias Cursadas</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card p-3 text-center">
                    <div class="h2 mb-1 text-success">{{ $estadisticas['materias_aprobadas'] }}</div>
                    <div class="text-muted small">Materias Aprobadas</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card p-3 text-center">
                    <div class="h2 mb-1 text-danger">{{ $estadisticas['materias_reprobadas'] }}</div>
                    <div class="text-muted small">Materias Reprobadas</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card p-3 text-center">
                    <div class="h2 mb-1 
                        @if($estadisticas['promedio_general'] >= 90) text-success
                        @elseif($estadisticas['promedio_general'] >= 80) text-info
                        @elseif($estadisticas['promedio_general'] >= 70) text-warning
                        @else text-danger
                        @endif">
                        {{ number_format($estadisticas['promedio_general'], 1) }}
                    </div>
                    <div class="text-muted small">Promedio General</div>
                </div>
            </div>
        </div>

        @if($kardexData->isEmpty())
            <!-- Estado vacío -->
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="ti ti-school-off"></i>
                        <h3>No hay calificaciones registradas</h3>
                        <p class="text-muted">
                            Aún no tienes calificaciones en el sistema.<br>
                            Las calificaciones aparecerán aquí cuando tus maestros las registren.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Navegación por pestañas -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="kardexTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="resumen-tab" data-bs-toggle="tab" data-bs-target="#resumen" type="button" role="tab">
                                <i class="ti ti-chart-bar me-2"></i>Resumen por Materias
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="detalle-tab" data-bs-toggle="tab" data-bs-target="#detalle" type="button" role="tab">
                                <i class="ti ti-list-details me-2"></i>Evaluaciones Detalladas
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="kardexTabsContent">
                        <!-- PESTAÑA 1: RESUMEN POR MATERIAS -->
                        <div class="tab-pane fade show active" id="resumen" role="tabpanel">
                            @foreach($kardexData as $periodo => $materiasPorPeriodo)
                                <div class="periodo-section mb-4">
                                    <div class="periodo-header">
                                        <i class="ti ti-calendar me-2"></i>
                                        Período Escolar: {{ $periodo }}
                                        <span class="float-end text-muted">
                                            {{ count($materiasPorPeriodo) }} {{ Str::plural('materia', count($materiasPorPeriodo)) }}
                                        </span>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Materia</th>
                                                    <th class="text-center">Maestro</th>
                                                    <th class="text-center">Calificación Final</th>
                                                    <th class="text-center">Estatus</th>
                                                    <th class="text-center">Evaluaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($materiasPorPeriodo as $materiaName => $calificaciones)
                                                    @php
                                                        $promedio = $calificaciones->avg('calificacion');
                                                        $estatus = $promedio >= 70 ? 'Aprobado' : 'Reprobado';
                                                        $maestro = $calificaciones->first()->maestro;
                                                        $totalEvaluaciones = $calificaciones->count();
                                                    @endphp
                                                    <tr class="materia-row">
                                                        <td>
                                                            <div class="fw-bold">{{ $materiaName }}</div>
                                                            <div class="text-muted small">
                                                                {{ $calificaciones->first()->materia->semestre ?? 'N/A' }}° Semestre
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="fw-bold">{{ $maestro->name ?? 'Sin asignar' }}</div>
                                                            <div class="text-muted small">Docente</div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="fw-bold h4
                                                                @if($promedio >= 90) text-success
                                                                @elseif($promedio >= 80) text-info
                                                                @elseif($promedio >= 70) text-warning
                                                                @else text-danger
                                                                @endif">
                                                                {{ number_format($promedio, 1) }}
                                                            </span>
                                                            <div class="text-muted small">Promedio</div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge {{ $estatus == 'Aprobado' ? 'badge-aprobado' : 'badge-reprobado' }}">
                                                                {{ $estatus }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-warning text-white">
                                                                {{ $totalEvaluaciones }} evaluaciones
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- PESTAÑA 2: EVALUACIONES DETALLADAS -->
                        <div class="tab-pane fade" id="detalle" role="tabpanel">
                            @foreach($kardexData as $periodo => $materiasPorPeriodo)
                                <div class="periodo-section mb-4">
                                    <div class="periodo-header">
                                        <i class="ti ti-calendar me-2"></i>
                                        Período Escolar: {{ $periodo }}
                                        <span class="float-end text-muted">
                                            {{ count($materiasPorPeriodo) }} {{ Str::plural('materia', count($materiasPorPeriodo)) }}
                                        </span>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Materia</th>
                                                    <th class="text-center">Evaluaciones</th>
                                                    <th class="text-center">Promedio</th>
                                                    <th class="text-center">Estatus</th>
                                                    <th class="text-center">Maestro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($materiasPorPeriodo as $materiaName => $calificaciones)
                                                    @php
                                                        $promedio = $calificaciones->avg('calificacion');
                                                        $estatus = $promedio >= 70 ? 'Aprobado' : 'Reprobado';
                                                        $maestro = $calificaciones->first()->maestro;
                                                    @endphp
                                                    <tr class="materia-row">
                                                        <td>
                                                            <div class="fw-bold">{{ $materiaName }}</div>
                                                            <div class="text-muted small">
                                                                {{ $calificaciones->first()->materia->semestre ?? 'N/A' }}° Semestre
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                                                @foreach($calificaciones as $cal)
                                                                    <span class="badge bg-warning text-white" 
                                                                          title="{{ $cal->tipo_evaluacion }} - {{ $cal->fecha_evaluacion ? $cal->fecha_evaluacion->format('d/m/Y') : 'Sin fecha' }}">
                                                                        {{ number_format($cal->calificacion, 0) }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                            <div class="text-muted small mt-1">
                                                                {{ count($calificaciones) }} evaluaciones
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="fw-bold
                                                                @if($promedio >= 90) promedio-excellent
                                                                @elseif($promedio >= 80) promedio-good
                                                                @elseif($promedio >= 70) promedio-regular
                                                                @else promedio-bad
                                                                @endif">
                                                                {{ number_format($promedio, 1) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge {{ $estatus == 'Aprobado' ? 'badge-aprobado' : 'badge-reprobado' }}">
                                                                {{ $estatus }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="text-muted small">
                                                                {{ $maestro->name ?? 'Sin asignar' }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Botones de acción -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                @if(Auth::guard('alumno')->check())
                    <a href="{{ route('alumnos_user.index') }}" class="btn btn-secondary me-2">
                        <i class="ti ti-arrow-left me-1"></i>Regresar al Dashboard
                    </a>
                @else
                    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">
                        <i class="ti ti-arrow-left me-1"></i>Regresar
                    </a>
                @endif
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="ti ti-printer me-1"></i>Imprimir Kardex
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar pestañas de Bootstrap
            var triggerTabList = [].slice.call(document.querySelectorAll('#kardexTabs button'))
            triggerTabList.forEach(function (triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)
                
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            });
            
            // Agregar tooltips a las calificaciones individuales
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Animación suave al cambiar de pestaña
            document.querySelectorAll('#kardexTabs button').forEach(button => {
                button.addEventListener('shown.bs.tab', function (e) {
                    const target = e.target.getAttribute('data-bs-target');
                    const tabPane = document.querySelector(target);
                    tabPane.style.opacity = '0';
                    tabPane.style.transform = 'translateY(10px)';
                    
                    setTimeout(() => {
                        tabPane.style.transition = 'all 0.3s ease';
                        tabPane.style.opacity = '1';
                        tabPane.style.transform = 'translateY(0)';
                    }, 50);
                });
            });
        });
    </script>
@endsection
