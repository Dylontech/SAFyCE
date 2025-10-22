@extends('tablar::page')

@section('title', 'Mis Calificaciones')

@section('css')
    <style>
        .calificaciones-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .calificaciones-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1.5" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.2"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 35px rgba(0,0,0,0.15);
        }
        
        .materia-card {
            border: none;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .materia-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
        }
        
        .materia-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 1.5rem;
            position: relative;
        }
        
        .materia-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" opacity="0.1"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>');
            transform: translate(20px, -20px);
        }
        
        .parcial-section {
            margin-bottom: 2rem;
            background: rgba(248, 249, 250, 0.7);
            border-radius: 12px;
            padding: 1.5rem;
            border-left: 4px solid #4facfe;
            backdrop-filter: blur(5px);
        }
        
        .parcial-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .parcial-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .calificacion-card {
            background: rgba(255, 255, 255, 0.85);
            border: none;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 15px rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
        }
        
        .calificacion-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #4facfe, #00f2fe);
        }
        
        .calificacion-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.12);
        }
        
        .calificacion-badge {
            font-size: 1.2rem;
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-weight: 700;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            min-width: 60px;
            text-align: center;
        }
        
        .badge-excellent {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: rgba(255, 255, 255, 0.95);
        }
        
        .badge-good {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: rgba(255, 255, 255, 0.95);
        }
        
        .badge-regular {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: rgba(33, 37, 41, 0.9);
        }
        
        .badge-bad {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
            color: rgba(255, 255, 255, 0.95);
        }
        
        .tipo-evaluacion {
            background: linear-gradient(135deg, #e9ecef, #f8f9fa);
            border-radius: 20px;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            color: #495057;
            font-weight: 500;
            border: 1px solid #dee2e6;
        }
        
        .evaluacion-info {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.75rem;
            border-left: 3px solid #4facfe;
            backdrop-filter: blur(3px);
        }
        
        .evaluacion-comentario {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            border-radius: 10px;
            padding: 0.75rem;
            margin-top: 0.75rem;
            border: 1px solid #e1bee7;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem;
            color: #6c757d;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
        }
        
        .empty-state i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.3;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-custom {
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: rgba(255, 255, 255, 0.95);
        }
        
        .btn-secondary-custom {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: rgba(255, 255, 255, 0.95);
        }
        
        .btn-outline-custom {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
        }
        
        .btn-outline-custom:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: rgba(255, 255, 255, 0.95);
        }
        
        .info-counter {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .materia-card:nth-child(odd) .materia-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        
        .materia-card:nth-child(even) .materia-header {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
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
            <!-- Lista de calificaciones organizadas por materia y parcial -->
            @php
                // Agrupar calificaciones por materia y luego por parcial
                $calificacionesAgrupadas = $calificaciones->groupBy(function($calificacion) {
                    return $calificacion->materia->materia ?? 'Sin materia';
                })->map(function($materiaGroup) {
                    return $materiaGroup->groupBy('parcial');
                });
            @endphp

            @foreach($calificacionesAgrupadas as $nombreMateria => $parcialesPorMateria)
                <div class="materia-card animate-fade-in">
                    <div class="materia-header">
                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="ti ti-book-2 me-2"></i>{{ $nombreMateria }}
                            </span>
                            <span class="info-counter">
                                {{ $parcialesPorMateria->flatten()->count() }} evaluaciones
                            </span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach($parcialesPorMateria->sortKeys() as $parcial => $calificacionesParcial)
                            <div class="parcial-section">
                                <div class="parcial-title">
                                    <i class="ti ti-clipboard-list text-primary"></i>
                                    <span class="text-gradient">
                                        @if($parcial)
                                            Parcial {{ $parcial }}
                                        @else
                                            Evaluaciones Generales
                                        @endif
                                    </span>
                                    <span class="parcial-badge">{{ $calificacionesParcial->count() }} evaluaciones</span>
                                </div>
                                
                                <div class="row">
                                    @foreach($calificacionesParcial as $calificacion)
                                        <div class="col-12 col-md-6 col-xl-4 mb-3">
                                            <div class="calificacion-card p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold text-primary mb-1">
                                                            <i class="ti ti-user me-1"></i>{{ $calificacion->maestro->name ?? 'Sin maestro' }}
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
                                                
                                                <div class="mb-3">
                                                    <span class="tipo-evaluacion">
                                                        <i class="ti ti-file-description me-1"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $calificacion->tipo_evaluacion)) }}
                                                    </span>
                                                </div>
                                                
                                                @if($calificacion->tarea || $calificacion->fecha_evaluacion)
                                                    <div class="evaluacion-info">
                                                        @if($calificacion->tarea)
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="ti ti-book text-primary me-2"></i>
                                                                <span class="small fw-bold">{{ Str::limit($calificacion->tarea->titulo, 25) }}</span>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($calificacion->fecha_evaluacion)
                                                            <div class="d-flex align-items-center text-muted small">
                                                                <i class="ti ti-calendar-event me-2"></i>
                                                                <span>{{ $calificacion->fecha_evaluacion->format('d/m/Y') }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                
                                                @if($calificacion->comentarios)
                                                    <div class="evaluacion-comentario">
                                                        <div class="d-flex align-items-start">
                                                            <i class="ti ti-message-circle text-purple me-2 mt-1"></i>
                                                            <small class="text-muted">
                                                                {{ $calificacion->comentarios }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Información de resultados -->
            <div class="d-flex justify-content-center mt-4">
                <div class="text-muted">
                    Mostrando {{ $calificaciones->count() }} de {{ $calificaciones->total() }} evaluaciones
                </div>
            </div>
            
            <!-- Paginación -->
            @if($calificaciones->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $calificaciones->links() }}
                </div>
            @endif
        @endif

        <!-- Botones de acción -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                @if(Auth::guard('alumno')->check())
                    <a href="{{ route('alumnos_user.index') }}" class="btn btn-secondary-custom btn-custom me-3">
                        <i class="ti ti-arrow-left me-2"></i>Regresar al Dashboard
                    </a>
                @else
                    <a href="{{ url()->previous() }}" class="btn btn-secondary-custom btn-custom me-3">
                        <i class="ti ti-arrow-left me-2"></i>Regresar
                    </a>
                @endif
                <a href="{{ route('calificaciones.kardex') }}" class="btn btn-primary-custom btn-custom me-3">
                    <i class="ti ti-school me-2"></i>Ver Kardex Completo
                </a>
                <button onclick="window.print()" class="btn btn-outline-custom btn-custom">
                    <i class="ti ti-printer me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animaciones escalonadas para las tarjetas de materias
            const materiaCards = document.querySelectorAll('.materia-card');
            materiaCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Animaciones para las tarjetas de calificaciones
            const calificacionCards = document.querySelectorAll('.calificacion-card');
            calificacionCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.4s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateX(0)';
                }, 500 + (index * 50));
            });

            // Animaciones para las stats cards
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, index * 100);
            });

            // Efecto hover mejorado para badges de calificación
            const badges = document.querySelectorAll('.calificacion-badge');
            badges.forEach(badge => {
                badge.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1) rotate(5deg)';
                });
                badge.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1) rotate(0deg)';
                });
            });

            // Animación de conteo para números
            const numerosStats = document.querySelectorAll('.stats-card .h2');
            numerosStats.forEach(numero => {
                const valorFinal = parseInt(numero.textContent);
                let valorActual = 0;
                const increment = valorFinal / 30;
                
                const timer = setInterval(() => {
                    valorActual += increment;
                    if (valorActual >= valorFinal) {
                        numero.textContent = valorFinal;
                        clearInterval(timer);
                    } else {
                        numero.textContent = Math.floor(valorActual);
                    }
                }, 50);
            });

            // Efectos de paralaje suave en el header
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const header = document.querySelector('.calificaciones-header');
                if (header) {
                    header.style.transform = `translateY(${scrolled * 0.5}px)`;
                    header.style.opacity = 1 - (scrolled / 400);
                }
            });

            // Agregar efectos de ripple a los botones
            const buttons = document.querySelectorAll('.btn-custom');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255,255,255,0.6);
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Agregar estilos CSS para la animación ripple
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
@endsection
