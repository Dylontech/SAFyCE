@extends('tablar::page')

@section('title', 'Portal Estudiantil - CECEyT')

@section('content')
    <!-- Estilos responsivos optimizados para alumnos -->
    <style>
        .student-hero {
            background: linear-gradient(135deg, #2196F3 0%, #21CBF3 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
        }
        
        .student-card {
            background: linear-gradient(45deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }
        
        .academic-info {
            background: #f8f9ff;
            border-left: 4px solid #2196F3;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0 0.5rem 0.5rem 0;
        }
        
        .carousel-container {
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        .carousel-inner {
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        .carousel-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        
        /* Responsive carousel heights optimized for students */
        @media (max-width: 576px) {
            .carousel-inner {
                height: 300px !important;
            }
            .student-hero {
                padding: 1.5rem 0;
            }
            .student-hero h1 {
                font-size: 1.5rem;
            }
        }
        
        @media (min-width: 577px) and (max-width: 768px) {
            .carousel-inner {
                height: 400px !important;
            }
        }
        
        @media (min-width: 769px) and (max-width: 992px) {
            .carousel-inner {
                height: 480px !important;
            }
        }
        
        @media (min-width: 993px) {
            .carousel-inner {
                height: 580px !important;
            }
        }
        
        .quick-access {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .quick-access:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .student-badge {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
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
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
    <!-- Sección Hero para estudiantes -->
    <div class="student-hero fade-in-up">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    @auth('alumno')
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/>
                            </svg>
                        </div>
                        <h1 class="display-5 fw-bold mb-3">
                            ¡Bienvenido, {{ Auth::guard('alumno')->user()->Nombre ?? 'Estudiante' }}!
                        </h1>
                        <p class="lead mb-4">
                            Portal Estudiantil del CECEyT - Tu espacio académico digital
                        </p>
                        
                        <!-- Información académica del estudiante -->
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-8 col-lg-6">
                                <div class="student-badge rounded-pill px-4 py-2 d-inline-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2v-6"/>
                                        <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4"/>
                                    </svg>
                                    <span>
                                        {{ Auth::guard('alumno')->user()->especialidad ?? 'Especialidad' }} - 
                                        Semestre {{ Auth::guard('alumno')->user()->semestre ?? 'N/A' }} - 
                                        Grupo {{ Auth::guard('alumno')->user()->Grupo ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-warning" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                                <polyline points="11,12 12,12 12,16 13,16"/>
                            </svg>
                        </div>
                        <h1 class="display-5 fw-bold mb-3">Acceso Restringido</h1>
                        <p class="lead mb-4">
                            Necesitas iniciar sesión como alumno para acceder a este portal
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/>
                                <path d="M20 12h-13l3 -3m0 6l-3 -3"/>
                            </svg>
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos para estudiantes -->
    @auth('alumno')
    <div class="container-xl mb-4">
        <div class="row g-3 fade-in-up" style="animation-delay: 0.2s;">
            <div class="col-6 col-md-2">
                <a href="{{ route('estudiantes.horarios') }}" class="quick-access text-decoration-none">
                    <div class="h2 mb-2 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                            <line x1="16" y1="3" x2="16" y2="7"/>
                            <line x1="8" y1="3" x2="8" y2="7"/>
                            <line x1="4" y1="11" x2="20" y2="11"/>
                        </svg>
                    </div>
                    <div class="small fw-medium text-dark">Mis Horarios</div>
                    <div class="small text-muted">Consultar</div>
                </a>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('estudiantes.salas') }}" class="quick-access text-decoration-none">
                    <div class="h2 mb-2 text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 21h18"/>
                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                            <path d="M9 9h6"/>
                            <path d="M9 12h6"/>
                            <path d="M9 15h6"/>
                        </svg>
                    </div>
                    <div class="small fw-medium text-dark">Aulas</div>
                    <div class="small text-muted">Explorar</div>
                </a>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('estudiantes.horarios.semanal') }}" class="quick-access text-decoration-none">
                    <div class="h2 mb-2 text-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                            <path d="M8 14h.01"/>
                            <path d="M12 14h.01"/>
                            <path d="M16 14h.01"/>
                            <path d="M8 18h.01"/>
                            <path d="M12 18h.01"/>
                            <path d="M16 18h.01"/>
                        </svg>
                    </div>
                    <div class="small fw-medium text-dark">Horario Semanal</div>
                    <div class="small text-muted">Ver</div>
                </a>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('tareas.mis-tareas') }}" class="quick-access text-decoration-none">
                    <div class="h2 mb-2 text-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                            <line x1="9" y1="9" x2="10" y2="9"/>
                            <line x1="9" y1="13" x2="15" y2="13"/>
                            <line x1="9" y1="17" x2="15" y2="17"/>
                        </svg>
                    </div>
                    <div class="small fw-medium text-dark">Mis Tareas</div>
                    <div class="small text-muted">Pendientes</div>
                </a>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('calificaciones.mis-calificaciones') }}" class="quick-access text-decoration-none">
                    <div class="h2 mb-2 text-orange">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 11l3 3l8 -8"/>
                            <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                        </svg>
                    </div>
                    <div class="small fw-medium text-dark">Calificaciones</div>
                    <div class="small text-muted">Revisar</div>
                </a>
            </div>
            <div class="col-6 col-md-2">
                <div class="quick-access">
                    <div class="h2 mb-2 text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                            <line x1="9" y1="13" x2="15" y2="13"/>
                        </svg>
                    </div>
                    <div class="small fw-medium text-dark">Solicitudes</div>
                    <div class="small text-muted">Gestionar</div>
                </div>
            </div>
        </div>
    </div>
    @endauth
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Novedades</h3>
                        </div>                   
                        <div id="carousel-sample" class="carousel slide" data-bs-ride="carousel">
                          <div class="carousel-indicators">
                              @foreach($carrusels as $index => $carrusel)
                                  <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
                              @endforeach
                          </div>
                          <div class="carousel-inner">
                              @foreach($carrusels as $index => $carrusel)
                                  <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                      <img class="d-block w-100" alt="{{ $carrusel->Description }}" src="{{ route('carrusel.image', ['id' => $carrusel->id]) }}" />
                                  </div>
                              @endforeach
                          </div>
                          <a class="carousel-control-prev" data-bs-target="#carousel-sample" role="button" data-bs-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Previous</span>
                          </a>
                          <a class="carousel-control-next" data-bs-target="#carousel-sample" role="button" data-bs-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Next</span>
                          </a>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
