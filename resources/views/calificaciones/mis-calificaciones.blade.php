@extends('tablar::page')

@section('title', 'Mis Calificaciones')

@section('css')
    <style>
        .calificaciones-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
        
        .calificacion-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 1rem;
            transition: box-shadow 0.3s ease;
        }
        
        .calificacion-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .calificacion-badge {
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .badge-excellent {
            background-color: #28a745;
            color: white;
        }
        
        .badge-good {
            background-color: #007bff;
            color: white;
        }
        
        .badge-regular {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-bad {
            background-color: #dc3545;
            color: white;
        }
        
        .tipo-evaluacion {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            color: #6c757d;
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
    </style>
@endsection

@section('content')
    <!-- Header de Calificaciones -->
    <div class="calificaciones-header">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="ti ti-clipboard-list me-2"></i>Mis Calificaciones
                    </h1>
                    <p class="mb-0 opacity-75">Período Escolar 2024-2025-1</p>
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
        <!-- Estadística del promedio -->
        <div class="row mb-4">
            <div class="col-md-4 col-6 mb-3">
                <div class="stats-card p-3 text-center">
                    <div class="h2 mb-1 
                        @if($promedio >= 90) text-success
                        @elseif($promedio >= 80) text-info
                        @elseif($promedio >= 70) text-warning
                        @else text-danger
                        @endif">
                        {{ number_format($promedio, 1) }}
                    </div>
                    <div class="text-muted small">Promedio del Período</div>
                </div>
            </div>
            <div class="col-md-4 col-6 mb-3">
                <div class="stats-card p-3 text-center">
                    <div class="h2 mb-1 text-primary">{{ $calificaciones->total() }}</div>
                    <div class="text-muted small">Total de Evaluaciones</div>
                </div>
            </div>
            <div class="col-md-4 col-12 mb-3">
                <div class="stats-card p-3 text-center">
                    <div class="h2 mb-1 text-success">
                        {{ $calificaciones->where('calificacion', '>=', 70)->count() }}
                    </div>
                    <div class="text-muted small">Evaluaciones Aprobadas</div>
                </div>
            </div>
        </div>

        @if($calificaciones->isEmpty())
            <!-- Estado vacío -->
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="ti ti-clipboard-off"></i>
                        <h3>No hay calificaciones registradas</h3>
                        <p class="text-muted">
                            Aún no tienes calificaciones en este período escolar.<br>
                            Las calificaciones aparecerán aquí cuando tus maestros las registren.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Lista de calificaciones -->
            <div class="row">
                @foreach($calificaciones as $calificacion)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="calificacion-card p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $calificacion->materia->materia ?? 'Sin materia' }}</h6>
                                    <div class="small text-muted">
                                        {{ $calificacion->maestro->name ?? 'Sin maestro' }}
                                    </div>
                                </div>
                                <span class="calificacion-badge 
                                    @if($calificacion->calificacion >= 90) badge-excellent
                                    @elseif($calificacion->calificacion >= 80) badge-good
                                    @elseif($calificacion->calificacion >= 70) badge-regular
                                    @else badge-bad
                                    @endif">
                                    {{ number_format($calificacion->calificacion, 0) }}
                                </span>
                            </div>
                            
                            <div class="mb-2">
                                <span class="tipo-evaluacion me-2">
                                    {{ ucfirst(str_replace('_', ' ', $calificacion->tipo_evaluacion)) }}
                                </span>
                                @if($calificacion->parcial)
                                    <span class="tipo-evaluacion">
                                        Parcial {{ $calificacion->parcial }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center text-muted small">
                                <div>
                                    @if($calificacion->tarea)
                                        <i class="ti ti-book me-1"></i>{{ $calificacion->tarea->titulo }}
                                    @else
                                        <i class="ti ti-clipboard me-1"></i>Evaluación general
                                    @endif
                                </div>
                                <div>
                                    <i class="ti ti-calendar me-1"></i>
                                    {{ $calificacion->fecha_evaluacion ? $calificacion->fecha_evaluacion->format('d/m/Y') : 'Sin fecha' }}
                                </div>
                            </div>
                            
                            @if($calificacion->comentarios)
                                <div class="mt-2 p-2 bg-light rounded">
                                    <small class="text-muted">
                                        <i class="ti ti-message me-1"></i>
                                        {{ $calificacion->comentarios }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $calificaciones->links() }}
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
                <a href="{{ route('calificaciones.kardex') }}" class="btn btn-primary me-2">
                    <i class="ti ti-school me-1"></i>Ver Kardex Completo
                </a>
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="ti ti-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar animaciones a las tarjetas
            const cards = document.querySelectorAll('.calificacion-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
@endsection
