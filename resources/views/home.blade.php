@extends('tablar::page')

@section('title', 'Inicio - SAAFCECEYT')

@section('content')
    <!-- Estilos responsivos optimizados -->
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
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
        
        /* Responsive carousel heights optimized for admin dashboard */
        @media (max-width: 576px) {
            .carousel-inner {
                height: 300px !important;
            }
            .hero-section {
                padding: 2rem 0;
            }
            .hero-section h1 {
                font-size: 1.75rem;
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
        
        .quick-stats {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.2s;
        }
        
        .quick-stats:hover {
            transform: translateY(-2px);
        }
        
        .welcome-card {
            background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
        }
    </style>
    <!-- Sección Hero responsiva -->
    <div class="hero-section">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        Bienvenido a SAAFCECEYT
                    </h1>
                    <p class="lead mb-4">
                        Sistema de Administración Financiera 
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                        @can('view', App\Models\Alumno::class)
                        <a href="{{ route('alumnos.index') }}" class="btn btn-light btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/>
                            </svg>
                            Ver Alumnos
                        </a>
                        @endcan
                        @can('view', App\Models\Materia::class)
                        <a href="{{ route('materias.index') }}" class="btn btn-outline-light btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <rect x="4" y="4" width="16" height="12" rx="1"/>
                                <path d="m16 8l-8 5l8 5v-10z"/>
                            </svg>
                            Ver Materias
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección del carrusel responsivo -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card" style="min-height: 700px;">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="4" y="4" width="16" height="12" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21,15 16,10 5,21"/>
                                </svg>
                                Novedades y Anuncios
                            </h3>
                            @can('create', App\Models\Carrusel::class)
                            <div class="card-actions d-none d-sm-flex">
                                <a href="{{ route('carrusels.index') }}" class="btn btn-primary btn-sm">
                                    Gestionar
                                </a>
                            </div>
                            @endcan
                        </div>
                        
                        @if($carrusels->count() > 0)
                        <div class="card-body p-0">
                            <!-- Carrusel completamente responsivo -->
                            <div id="carousel-sample" class="carousel slide carousel-container" data-bs-ride="carousel" data-bs-interval="5000">
                                <!-- Indicadores responsivos -->
                                <div class="carousel-indicators d-none d-sm-flex">
                                    @foreach($carrusels as $index => $carrusel)
                                        <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="{{ $index }}" 
                                                class="{{ $index == 0 ? 'active' : '' }}" 
                                                aria-current="{{ $index == 0 ? 'true' : 'false' }}" 
                                                aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                                
                                <!-- Contenido del carrusel -->
                                <div class="carousel-inner">
                                    @foreach($carrusels as $index => $carrusel)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <div class="d-flex align-items-center justify-content-center bg-light">
                                                <img class="d-block w-100" 
                                                     alt="{{ $carrusel->Description }}" 
                                                     src="{{ route('carrusel.image', ['id' => $carrusel->id]) }}" 
                                                     style="object-fit: cover; width: 100%; height: 100%;" />
                                            </div>
                                            
                                            <!-- Caption responsivo -->
                                            @if($carrusel->Description)
                                            <div class="carousel-caption d-none d-md-block">
                                                <div class="bg-dark bg-opacity-75 rounded px-3 py-2">
                                                    <p class="mb-0">{{ $carrusel->Description }}</p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Controles del carrusel -->
                                @if($carrusels->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-sample" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-sample" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                                @endif
                            </div>
                            
                            <!-- Información de la imagen actual - Solo móviles -->
                            <div class="d-md-none p-3 bg-light">
                                @foreach($carrusels as $index => $carrusel)
                                    @if($carrusel->Description)
                                    <div class="carousel-description {{ $index == 0 ? '' : 'd-none' }}" data-slide="{{ $index }}">
                                        <small class="text-muted">{{ $carrusel->Description }}</small>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @else
                        <!-- Estado cuando no hay imágenes -->
                        <div class="card-body text-center py-5">
                            <div class="empty">
                                <div class="empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="4" width="16" height="12" rx="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21,15 16,10 5,21"/>
                                    </svg>
                                </div>
                                <p class="empty-title h3">No hay novedades</p>
                                <p class="empty-subtitle text-muted">
                                    Aún no se han agregado Novedades o anuncios
                                </p>
                                @can('create', App\Models\Carrusel::class)
                                <div class="empty-action">
                                    <a href="{{ route('carrusels.create') }}" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                        </svg>
                                        Agregar primera imagen
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para funcionalidad adicional del carrusel -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sincronizar descripciones en móviles con el carrusel
        const carousel = document.getElementById('carousel-sample');
        if (carousel) {
            carousel.addEventListener('slid.bs.carousel', function (event) {
                const slideIndex = event.to;
                const descriptions = document.querySelectorAll('.carousel-description');
                
                descriptions.forEach((desc, index) => {
                    if (index === slideIndex) {
                        desc.classList.remove('d-none');
                    } else {
                        desc.classList.add('d-none');
                    }
                });
            });
        }
        
        // Pausa del carrusel en hover (solo desktop)
        if (window.innerWidth > 768) {
            const carouselElement = document.querySelector('.carousel');
            if (carouselElement) {
                carouselElement.addEventListener('mouseenter', function() {
                    bootstrap.Carousel.getInstance(this).pause();
                });
                
                carouselElement.addEventListener('mouseleave', function() {
                    bootstrap.Carousel.getInstance(this).cycle();
                });
            }
        }
    });
    </script>
@endsection
