<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $configuracion->titulo_principal ?? 'Sistema de Gestión Escolar' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .login-navbar {
            background: rgba(0, 0, 0, 0.9);
            padding: 0.8rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .login-navbar .navbar-brand {
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .login-navbar .login-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.6rem 1.8rem;
            border-radius: 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .login-navbar .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 120px 0 100px 0;
            margin-top: 76px;
        }
        .team-card, .news-card {
            transition: transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .team-card:hover {
            transform: translateY(-5px);
        }
        .news-card {
            border-left: 4px solid #667eea;
        }
        .social-icon {
            font-size: 1.5rem;
            margin-right: 15px;
            color: #667eea;
            transition: color 0.3s ease;
        }
        .social-icon:hover {
            color: #764ba2;
            transform: scale(1.1);
        }
        
        /* PLACEHOLDER CORREGIDO - Perfectamente centrado */
        .member-placeholder {
            width: 100%;
            height: 100%;
            background-color: #6c757d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        
        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .hero-img {
            max-height: 400px;
            width: auto;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        .img-error {
            display: none;
        }

        .team-section {
            padding: 80px 0;
        }
        .team-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin: 0 auto;
            max-width: 1200px;
        }
        .team-member {
            flex: 0 0 calc(33.333% - 30px);
            max-width: calc(33.333% - 30px);
            margin-bottom: 30px;
        }
        .team-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: none;
            border-radius: 15px;
            padding: 20px;
            background: white;
        }

        /* CONTENEDOR DE IMAGEN CORREGIDO */
        .team-image-container {
            margin-bottom: 20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid #667eea;
            overflow: hidden;
            position: relative;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* IMAGEN PERFECTAMENTE CENTRADA */
        .team-member-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
            display: block;
            border-radius: 0;
        }

        /* PLACEHOLDER DE ERROR - Posicionado absolutamente */
        .member-placeholder-error {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #6c757d;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .team-member {
                flex: 0 0 calc(50% - 30px);
                max-width: calc(50% - 30px);
            }
            .team-image-container {
                width: 100px;
                height: 100px;
            }
        }

        @media (max-width: 768px) {
            .team-member {
                flex: 0 0 calc(100% - 30px);
                max-width: calc(100% - 30px);
            }
            .team-container {
                gap: 20px;
            }
            .team-image-container {
                width: 90px;
                height: 90px;
            }
            .team-card {
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            .team-member {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 20px;
            }
            .team-image-container {
                width: 80px;
                height: 80px;
            }
            .team-card h5 {
                font-size: 1.1rem;
            }
            .team-card p {
                font-size: 0.9rem;
            }
        }

        .team-container[data-count="1"] .team-member {
            flex: 0 0 auto;
            max-width: 400px;
        }
        .team-container[data-count="2"] .team-member {
            flex: 0 0 calc(50% - 30px);
            max-width: calc(50% - 30px);
        }
        .team-container[data-count="4"] .team-member {
            flex: 0 0 calc(25% - 30px);
            max-width: calc(25% - 30px);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            color: #2c3e50;
            position: relative;
        }
        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        .hero-image-container {
            position: relative;
            display: inline-block;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .hero-img-cropped {
            max-height: 400px;
            width: auto;
            object-fit: cover;
            display: block;
        }
    </style>
</head>
<body>
    <!-- Navbar de Login -->
    <nav class="login-navbar navbar navbar-expand-lg">
        <div class="navbar-container container-fluid">
            <a class="navbar-brand" href="{{ route('inicio') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                {{ $configuracion->titulo_principal ?? 'Sistema Escolar' }}
            </a>
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1h-10a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1h-10a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1h-10a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto align-items-lg-center gap-lg-4">
                    <a href="#inicio" class="nav-link text-white">Inicio</a>
                    <a href="#equipo" class="nav-link text-white">Equipo</a>
                    <a href="#novedades" class="nav-link text-white">Novedades</a>
                    <a href="#about" class="nav-link text-white">Acerca de</a>
                    <a href="#contacto" class="nav-link text-white">Contacto</a>
                    <a href="{{ route('login') }}" class="login-btn ms-lg-3 mt-2 mt-lg-0">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">{{ $configuracion->titulo_principal ?? 'Sistema de Gestión Escolar' }}</h1>
            <p class="lead mb-4">{{ $configuracion->descripcion ?? 'Plataforma integral para la administración de procesos académicos' }}</p>
            @if($configuracion->imagen_principal ?? false)
                @php
                    $heroCropData = null;
                    if (!empty($configuracion->imagen_principal_crop_data)) {
                        if (is_string($configuracion->imagen_principal_crop_data)) {
                            $heroCropData = json_decode($configuracion->imagen_principal_crop_data, true);
                        } elseif (is_array($configuracion->imagen_principal_crop_data)) {
                            $heroCropData = $configuracion->imagen_principal_crop_data;
                        }
                    }
                    
                    $heroImgStyle = '';
                    $heroImgClass = 'hero-img';
                    if ($heroCropData && is_array($heroCropData)) {
                        $heroImgStyle = "object-position: {$heroCropData['x']}px {$heroCropData['y']}px;";
                        $heroImgClass = 'hero-img-cropped';
                    }
                @endphp
                
                <div class="hero-image-container">
                    <img src="{{ Storage::url($configuracion->imagen_principal) }}" 
                         alt="Sistema" 
                         class="{{ $heroImgClass }}"
                         style="{{ $heroImgStyle }}"
                         onerror="this.style.display='none'; document.getElementById('hero-placeholder').style.display='block';">
                    <div id="hero-placeholder" class="img-error">
                        <div class="bg-light rounded p-4">
                            <i class="fas fa-image fa-3x text-muted mb-2"></i>
                            <p class="text-muted">Imagen no disponible</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Team Section - PLACEHOLDER CORREGIDO -->
    <section id="equipo" class="team-section">
        <div class="container">
            <h2 class="section-title text-center">{{ $configuracion->titulo_equipo ?? 'Nuestro Equipo de Desarrollo' }}</h2>
            
            @php
                $miembros = [];
                if (!empty($configuracion->miembros_equipo)) {
                    if (is_array($configuracion->miembros_equipo)) {
                        $miembros = $configuracion->miembros_equipo;
                    } elseif (is_string($configuracion->miembros_equipo)) {
                        $miembros = json_decode($configuracion->miembros_equipo, true) ?: [];
                    }
                }
                $miembrosCount = count($miembros);
            @endphp

            <div class="team-container" data-count="{{ $miembrosCount }}">
                @forelse($miembros as $index => $miembro)
                <div class="team-member">
                    <div class="team-card">
                        <div class="team-image-container">
                            @if(!empty($miembro['foto']))
                                <!-- Imagen con placeholder de error -->
                                <img src="{{ Storage::url($miembro['foto']) }}" 
                                     alt="{{ $miembro['nombre'] ?? 'Miembro del equipo' }}" 
                                     class="team-member-img"
                                     onerror="this.style.display='none'; document.getElementById('member-error-{{ $index }}').style.display='flex';">
                                <div id="member-error-{{ $index }}" class="member-placeholder-error">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            @else
                            <!-- Placeholder cuando no hay imagen - CORREGIDO -->
                            <div class="member-placeholder">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                            @endif
                        </div>
                        <h5 class="card-title fw-bold mb-2">{{ $miembro['nombre'] ?? 'Nombre no disponible' }}</h5>
                        <p class="text-muted mb-0">{{ $miembro['cargo'] ?? 'Cargo no disponible' }}</p>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay información del equipo disponible.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="novedades" class="bg-light py-5">
        <div class="container">
            <h2 class="section-title text-center">{{ $configuracion->titulo_novedades ?? 'Últimas Novedades' }}</h2>
            <div class="row justify-content-center">
                @php
                    $novedades = [];
                    if (!empty($configuracion->novedades)) {
                        if (is_array($configuracion->novedades)) {
                            $novedades = $configuracion->novedades;
                        } elseif (is_string($configuracion->novedades)) {
                            $novedades = json_decode($configuracion->novedades, true) ?: [];
                        }
                    }
                @endphp
                @forelse($novedades as $novedad)
                <div class="col-lg-6 col-md-8 mb-4">
                    <div class="card news-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $novedad['titulo'] ?? 'Título no disponible' }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                {{ !empty($novedad['fecha']) ? date('d/m/Y', strtotime($novedad['fecha'])) : 'Fecha no disponible' }}
                            </h6>
                            <p class="card-text">{{ $novedad['descripcion'] ?? 'Descripción no disponible' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay novedades disponibles.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contacto" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">{{ $configuracion->titulo_contacto ?? 'Contáctanos' }}</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                    <h5>Email</h5>
                                    <p class="text-muted">{{ $configuracion->email_contacto ?? 'contacto@example.com' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                    <h5>Teléfono</h5>
                                    <p class="text-muted">{{ $configuracion->telefono_contacto ?? '+1 234 567 8900' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                                    <h5>Dirección</h5>
                                    <p class="text-muted">{{ $configuracion->direccion_contacto ?? 'Dirección de ejemplo' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="mb-3">Síguenos en redes sociales</h5>
                                    <div class="d-flex justify-content-center">
                                        @if($configuracion->facebook ?? false)
                                        <a href="{{ $configuracion->facebook }}" class="social-icon" target="_blank">
                                            <i class="fab fa-facebook"></i>
                                        </a>
                                        @endif
                                        @if($configuracion->whatsapp ?? false)
                                        <a href="{{ $configuracion->whatsapp }}" class="social-icon" target="_blank">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        @endif
                                        @if($configuracion->instagram ?? false)
                                        <a href="{{ $configuracion->instagram }}" class="social-icon" target="_blank">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-white">
        <div class="container">
            <h2 class="section-title text-center mb-5">Acerca de</h2>
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4" style="background: #f8f9fa;">
                        @php
                            $about = $configuracion->about;
                            if (is_string($about)) {
                                $about = json_decode($about, true);
                            }
                        @endphp
                        @if(is_array($about))
                            <div class="mb-4 text-center">
                                <h4 class="fw-bold">Quienes somos</h4>
                                <p class="mb-0">{{ $about['mision'] ?? '' }}</p>
                            </div>
                            <div class="mb-4 text-center">
                                <h4 class="fw-bold">Institución</h4>
                                <p class="mb-0">{{ $about['vision'] ?? '' }}</p>
                            </div>
                            @if(!empty($about['valores']) && is_array($about['valores']))
                            <div class="mb-4 text-center">
                                <h4 class="fw-bold">Valores</h4>
                                <ul class="list-inline">
                                    @foreach($about['valores'] as $valor)
                                        <li class="list-inline-item badge bg-primary fs-6 m-1">{{ $valor }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        @else
                            <p class="text-center">{{ $configuracion->about ?? '' }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <p class="mb-0">&copy; 2025 {{ $configuracion->titulo_principal ?? 'Sistema de Gestión Escolar' }}. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const teamContainer = document.querySelector('.team-container');
            const teamMembers = document.querySelectorAll('.team-member');
            const count = teamMembers.length;

            if (count === 1) {
                teamContainer.classList.add('justify-content-center');
            }

            // Asegurar que todas las imágenes se carguen correctamente
            const teamImages = document.querySelectorAll('.team-member-img');
            teamImages.forEach(img => {
                img.style.objectFit = 'cover';
                img.style.objectPosition = 'center center';
            });
        });
    </script>
</body>
</html>