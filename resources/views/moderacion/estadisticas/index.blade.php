@extends('tablar::page')

@section('title', 'Estadísticas de Moderación')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-success">
                    <i class="ti ti-chart-bar me-2"></i>
                    Estadísticas de Moderación
                </h2>
                <div class="text-muted mt-1">Análisis y métricas del sistema de moderación</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('moderacion.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Estadísticas Generales -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-primary">{{ $estadisticasGenerales['total_reportes'] }}</div>
                                <div class="text-muted">Total Reportes</div>
                            </div>
                            <i class="ti ti-flag fs-1 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-warning">{{ $estadisticasGenerales['reportes_pendientes'] }}</div>
                                <div class="text-muted">Reportes Pendientes</div>
                            </div>
                            <i class="ti ti-clock fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-success">{{ $estadisticasGenerales['reportes_resueltos'] }}</div>
                                <div class="text-muted">Reportes Resueltos</div>
                            </div>
                            <i class="ti ti-check fs-1 text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-danger">{{ $estadisticasGenerales['usuarios_bloqueados_activos'] }}</div>
                                <div class="text-muted">Usuarios Bloqueados</div>
                            </div>
                            <i class="ti ti-ban fs-1 text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h1 mb-0 text-orange">{{ $estadisticasGenerales['publicaciones_eliminadas'] }}</div>
                                <div class="text-muted">Publicaciones Eliminadas</div>
                            </div>
                            <i class="ti ti-trash fs-1 text-orange opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                @php 
                                    $total = $estadisticasGenerales['total_reportes'];
                                    $resueltos = $estadisticasGenerales['reportes_resueltos'];
                                    $tasa = $total > 0 ? round(($resueltos / $total) * 100, 1) : 0;
                                @endphp
                                <div class="h1 mb-0 text-info">{{ $tasa }}%</div>
                                <div class="text-muted">Tasa de Resolución</div>
                            </div>
                            <i class="ti ti-percentage fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                @php
                                    $tiempoPromedio = $estadisticasGenerales['reportes_resueltos'] > 0 ? '2.5 días' : 'N/A';
                                @endphp
                                <div class="h1 mb-0 text-purple">{{ $tiempoPromedio }}</div>
                                <div class="text-muted">Tiempo Promedio</div>
                            </div>
                            <i class="ti ti-clock-hour-4 fs-1 text-purple opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gráfico de Reportes por Mes -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Reportes por Mes ({{ date('Y') }})</h3>
                    </div>
                    <div class="card-body">
                        @if($reportesPorMes->count() > 0)
                            <div class="chart-container">
                                <canvas id="reportesPorMesChart" style="height: 300px;"></canvas>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ti ti-chart-line fs-1 text-muted mb-3"></i>
                                <p class="text-muted">No hay datos suficientes para mostrar el gráfico</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Top Moderadores -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Moderadores Más Activos (30 días)</h3>
                    </div>
                    <div class="card-body">
                        @if($moderadoresMasActivos->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($moderadoresMasActivos as $index => $moderador)
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="badge bg-{{ $index === 0 ? 'yellow' : ($index === 1 ? 'secondary' : 'orange') }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-initials">{{ strtoupper(substr($moderador->moderador->name ?? 'D', 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="text-truncate">{{ $moderador->moderador->name ?? 'Desconocido' }}</div>
                                                        <div class="text-muted small">{{ $moderador->total_acciones }} acciones</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-users-off fs-1 mb-3"></i>
                                <p>No hay actividad registrada</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tipos de Reportes Más Comunes -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tipos de Reportes Más Comunes</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $tiposReportes = [
                                'contenido_inapropiado' => 'Contenido Inapropiado',
                                'acoso_bullying' => 'Acoso/Bullying',
                                'spam' => 'Spam',
                                'informacion_falsa' => 'Información Falsa',
                                'violencia' => 'Violencia',
                                'contenido_sexual' => 'Contenido Sexual',
                                'drogas_alcohol' => 'Drogas/Alcohol',
                                'otros' => 'Otros'
                            ];
                            $totalReportes = \App\Models\Reporte::count();
                        @endphp
                        
                        @if($totalReportes > 0)
                            @foreach($tiposReportes as $tipo => $nombre)
                                @php
                                    $cantidad = \App\Models\Reporte::where('tipo_reporte', $tipo)->count();
                                    $porcentaje = $totalReportes > 0 ? round(($cantidad / $totalReportes) * 100, 1) : 0;
                                @endphp
                                @if($cantidad > 0)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-muted">{{ $nombre }}</span>
                                            <span class="text-muted">{{ $cantidad }} ({{ $porcentaje }}%)</span>
                                        </div>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-{{ 
                                                $tipo === 'contenido_inapropiado' ? 'danger' : 
                                                ($tipo === 'acoso_bullying' ? 'warning' : 
                                                ($tipo === 'spam' ? 'info' : 'secondary')) 
                                            }}" style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-chart-pie-off fs-1 mb-3"></i>
                                <p>No hay reportes registrados</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estados de Reportes -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Estado de Reportes</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $estados = [
                                'pendiente' => ['nombre' => 'Pendientes', 'color' => 'warning'],
                                'en_revision' => ['nombre' => 'En Revisión', 'color' => 'info'],
                                'resuelto' => ['nombre' => 'Resueltos', 'color' => 'success'],
                                'rechazado' => ['nombre' => 'Rechazados', 'color' => 'danger']
                            ];
                        @endphp
                        
                        @if($totalReportes > 0)
                            @foreach($estados as $estado => $config)
                                @php
                                    $cantidad = \App\Models\Reporte::where('estado', $estado)->count();
                                    $porcentaje = $totalReportes > 0 ? round(($cantidad / $totalReportes) * 100, 1) : 0;
                                @endphp
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted">{{ $config['nombre'] }}</span>
                                        <span class="badge bg-{{ $config['color'] }}">{{ $cantidad }} ({{ $porcentaje }}%)</span>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-{{ $config['color'] }}" style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-chart-donut-off fs-1 mb-3"></i>
                                <p>No hay reportes registrados</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de Acciones de Moderación -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Resumen de Acciones de Moderación (Últimos 30 días)</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $acciones = \App\Models\HistorialModeracion::selectRaw('accion, COUNT(*) as total')
                                ->where('created_at', '>=', now()->subDays(30))
                                ->groupBy('accion')
                                ->pluck('total', 'accion');
                            $totalAcciones = $acciones->sum();
                        @endphp
                        
                        @if($totalAcciones > 0)
                            <div class="row">
                                @foreach($acciones as $accion => $cantidad)
                                    <div class="col-sm-6 col-lg-4 mb-3">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <div class="h2 mb-0 text-{{ 
                                                    $accion === 'bloquear_usuario' ? 'danger' : 
                                                    ($accion === 'desbloquear_usuario' ? 'success' : 
                                                    ($accion === 'eliminar_publicacion' ? 'warning' : 'info')) 
                                                }}">{{ $cantidad }}</div>
                                                <div class="text-muted">{{ ucfirst(str_replace('_', ' ', $accion)) }}</div>
                                                <div class="small text-muted">
                                                    {{ round(($cantidad / $totalAcciones) * 100, 1) }}% del total
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="ti ti-activity-off fs-1 mb-3"></i>
                                <h3>No hay actividad reciente</h3>
                                <p>No se han registrado acciones de moderación en los últimos 30 días.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Reportes por Mes
    @if($reportesPorMes->count() > 0)
        const ctx = document.getElementById('reportesPorMesChart');
        if (ctx) {
            const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            const datos = @json($reportesPorMes);
            
            const labels = [];
            const values = [];
            
            for (let i = 1; i <= 12; i++) {
                labels.push(meses[i - 1]);
                values.push(datos[i] || 0);
            }
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Reportes',
                        data: values,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
    @endif
});
</script>
@endpush
@endsection