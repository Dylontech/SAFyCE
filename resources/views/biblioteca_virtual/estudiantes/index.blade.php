{{--
/*====================================================================================
|                             BIBLIOTECA VIRTUAL - SAFyCE                            |
|==================================================================================== 
| Vista: resources/views/biblioteca_virtual/estudiantes/index.blade.php
| Propósito: Portal de acceso a recursos digitales para estudiantes
| Funcionalidades:
|   - Acceso directo a bases de datos académicas
|   - Organización por categorías de recursos
|   - Enlaces rápidos a plataformas como eLibro, EBSCOhost, DynaMed, SciELO
|   - Interfaz intuitiva y responsive
|====================================================================================*/
--}}

@extends('tablar::page')

@section('title', 'Biblioteca Virtual - Portal Estudiantil')

@section('content')
    {{-- ============================================================================
         ESTILOS CSS PERSONALIZADOS PARA BIBLIOTECA VIRTUAL
         ============================================================================ --}}
    <style>
        .biblioteca-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
        }

        .recurso-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: 100%;
        }

        .recurso-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .recurso-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #4299e1;
        }

        .categoria-section {
            margin-bottom: 3rem;
        }

        .categoria-title {
            color: #2d3748;
            border-bottom: 3px solid #4299e1;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .recurso-link {
            text-decoration: none;
            color: inherit;
        }

        .recurso-link:hover {
            color: inherit;
            text-decoration: none;
        }

        .stats-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #718096;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .biblioteca-hero {
                padding: 2rem 0;
            }
            
            .recurso-icon {
                font-size: 2rem;
            }
        }
    </style>

    {{-- ============================================================================
         SECCIÓN HERO - BIENVENIDA Y ESTADÍSTICAS
         ============================================================================ --}}
    <div class="biblioteca-hero">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="fas fa-book-open me-3"></i>
                        Biblioteca Virtual CECEyT
                    </h1>
                    <p class="lead mb-0">
                        Accede a recursos digitales de alta calidad para potenciar tu aprendizaje. 
                        Bases de datos, revistas científicas, libros digitales y más.
                    </p>
                </div>
                <div class="col-lg-4">
                    <div class="stats-card text-center">
                        <div class="h1 mb-1">{{ $recursos->flatten()->count() }}</div>
                        <div class="text-white-50">Recursos Disponibles</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================================
         CONTENIDO PRINCIPAL - RECURSOS POR CATEGORÍAS
         ============================================================================ --}}
    <div class="page-body">
        <div class="container-xl">
            @if($recursos->count() > 0)
                @foreach($recursos as $categoria => $recursosCategoria)
                    <div class="categoria-section">
                        <h2 class="categoria-title">
                            <i class="fas fa-folder-open me-2"></i>
                            {{ $categorias[$categoria] ?? ucfirst($categoria) }}
                        </h2>
                        
                        <div class="row g-4">
                            @foreach($recursosCategoria as $recurso)
                                <div class="col-lg-4 col-md-6">
                                    <a href="{{ $recurso->url }}" target="_blank" class="recurso-link">
                                        <div class="card recurso-card">
                                            <div class="card-body text-center">
                                                <div class="recurso-icon">
                                                    <i class="{{ $recurso->icono_display }}"></i>
                                                </div>
                                                <h5 class="card-title mb-2">{{ $recurso->nombre }}</h5>
                                                @if($recurso->descripcion)
                                                    <p class="card-text text-muted small">{{ $recurso->descripcion }}</p>
                                                @endif
                                                <div class="mt-3">
                                                    <span class="btn btn-primary btn-sm">
                                                        <i class="fas fa-external-link-alt me-1"></i>
                                                        Acceder
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- ============================================================================
                     SECCIÓN DE AYUDA Y CONSEJOS
                     ============================================================================ --}}
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    Consejos para el Uso de la Biblioteca Virtual
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Utiliza palabras clave específicas en tus búsquedas
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Guarda los artículos relevantes para futuras consultas
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Verifica la fecha de publicación de los recursos
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Utiliza filtros por tipo de documento y año
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Cita correctamente las fuentes consultadas
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Consulta con tus profesores sobre recursos específicos
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- ============================================================================
                     ESTADO VACÍO - NO HAY RECURSOS
                     ============================================================================ --}}
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3>Biblioteca Virtual en Construcción</h3>
                    <p>Los recursos digitales estarán disponibles próximamente.</p>
                    <p class="text-muted">
                        Mientras tanto, puedes consultar con tus profesores sobre 
                        recursos específicos para tus materias.
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================================
         SECCIÓN FOOTER CON INFORMACIÓN ADICIONAL
         ============================================================================ --}}
    <div class="container-xl mt-5">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-light border">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-2x text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">¿Necesitas ayuda?</h6>
                            <p class="mb-0 text-muted">
                                Si tienes problemas para acceder a algún recurso o necesitas orientación 
                                sobre cómo utilizar estas herramientas, contacta con el personal de biblioteca 
                                o tus profesores.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    // Animación al hacer scroll para las tarjetas
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observar todas las tarjetas de recursos
        document.querySelectorAll('.recurso-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });

    // Seguimiento de clics en recursos (opcional para analytics)
    document.querySelectorAll('.recurso-link').forEach(link => {
        link.addEventListener('click', function() {
            const recursoNombre = this.querySelector('.card-title').textContent;
            console.log('Acceso a recurso:', recursoNombre);
            // Aquí se podría enviar información de analytics si se requiere
        });
    });
</script>
@endsection
