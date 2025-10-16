@extends('tablar::page')

@section('title', 'Mi Perfil')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-user me-2"></i>
                    Mi Perfil
                </h2>
                <div class="text-muted mt-1">Gestiona tu información personal y configuración</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('perfil.edit') }}" class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i>
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <!-- Información del perfil -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar avatar-xl mb-3" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);">
                            <i class="ti ti-{{ $perfil->icono_personalizado }} text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="card-title">{{ $perfil->alumno->Nombre }}</h3>
                        <p class="text-muted">{{ $perfil->alumno->especialidad }} - {{ $perfil->alumno->semestre }}° Semestre</p>
                        <p class="text-muted mb-3">Grupo: {{ $perfil->alumno->Grupo }}</p>
                        
                        @if($perfil->estado)
                            <div class="badge bg-success-lt mb-3">{{ $perfil->estado }}</div>
                        @endif
                        
                        @if($perfil->biografia)
                            <blockquote class="blockquote">
                                <p>{{ $perfil->biografia }}</p>
                            </blockquote>
                        @endif
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Estadísticas</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h3 text-primary">{{ $estadisticas['total_publicaciones'] }}</div>
                                    <div class="text-muted">Publicaciones</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h3 text-success">{{ $estadisticas['total_reacciones_recibidas'] }}</div>
                                    <div class="text-muted">Reacciones</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h3 text-info">{{ $estadisticas['total_comentarios_recibidos'] }}</div>
                                    <div class="text-muted">Comentarios</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h3 text-warning">{{ $estadisticas['badges_obtenidos'] }}</div>
                                    <div class="text-muted">Logros</div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="text-center">
                            <div class="h4 text-purple">{{ $estadisticas['puntos_actividad'] }}</div>
                            <div class="text-muted">Puntos de Actividad</div>
                        </div>
                    </div>
                </div>

                <!-- Redes Sociales -->
                @if($perfil->redesSociales->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Redes Sociales</h3>
                    </div>
                    <div class="card-body">
                        @foreach($perfil->redesSociales->where('visible', true) as $red)
                            <a href="{{ $red->url_completa }}" target="_blank" class="btn btn-outline-secondary btn-sm me-2 mb-2">
                                <i class="{{ $red->icono }} me-1" style="color: {{ $red->color }};"></i>
                                {{ $red->info_plataforma['nombre'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Badges/Logros -->
                @if($perfil->badges->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Logros Obtenidos</h3>
                    </div>
                    <div class="card-body">
                        @foreach($perfil->badges as $badge)
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2" style="font-size: 1.2rem;">{{ $badge->icono }}</span>
                                <div>
                                    <strong>{{ $badge->nombre }}</strong>
                                    <div class="text-muted small">{{ $badge->descripcion }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Contenido principal -->
            <div class="col-lg-8">
                <!-- Navegación de pestañas -->
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                            <li class="nav-item">
                                <a href="#tab-feed" class="nav-link active" data-bs-toggle="tab">
                                    <i class="ti ti-news me-1"></i>
                                    Feed
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-mis-publicaciones" class="nav-link" data-bs-toggle="tab">
                                    <i class="ti ti-file-text me-1"></i>
                                    Mis Publicaciones
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-galeria" class="nav-link" data-bs-toggle="tab">
                                    <i class="ti ti-photo me-1"></i>
                                    Galería
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Feed Tab -->
                            <div class="tab-pane active" id="tab-feed">
                                <!-- Formulario para nueva publicación -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <form action="{{ route('perfil.publicaciones.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <textarea name="contenido" class="form-control" rows="3" placeholder="¿Qué estás pensando?" required></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tipo de publicación</label>
                                                        <select name="tipo" class="form-select">
                                                            <option value="texto">Solo texto</option>
                                                            <option value="imagen">Con imagen</option>
                                                            <option value="archivo">Con archivo</option>
                                                            <option value="galeria">Galería</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Archivos (opcional)</label>
                                                        <input type="file" name="archivos[]" class="form-control" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" name="etiquetas[]" class="form-control" placeholder="Etiquetas (separadas por comas)">
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ti ti-send me-1"></i>
                                                Publicar
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Aquí irían las publicaciones del feed -->
                                <div class="text-center text-muted">
                                    <i class="ti ti-news fs-1 opacity-50"></i>
                                    <p>Visita el <a href="{{ route('perfil.feed') }}">Feed completo</a> para ver más publicaciones</p>
                                </div>
                            </div>

                            <!-- Mis Publicaciones Tab -->
                            <div class="tab-pane" id="tab-mis-publicaciones">
                                @if($perfil->publicaciones->count() > 0)
                                    @foreach($perfil->publicaciones->take(5) as $publicacion)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">{{ $publicacion->created_at->diffForHumans() }}</small>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm" data-bs-toggle="dropdown">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('perfil.publicaciones.edit', $publicacion->id) }}" class="dropdown-item">
                                                                <i class="ti ti-edit me-1"></i>
                                                                Editar
                                                            </a>
                                                            <form action="{{ route('perfil.publicaciones.destroy', $publicacion->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Estás seguro?')">
                                                                    <i class="ti ti-trash me-1"></i>
                                                                    Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="mt-2">{{ $publicacion->contenido }}</p>
                                                <div class="d-flex align-items-center text-muted small">
                                                    <span class="me-3">
                                                        <i class="ti ti-heart me-1"></i>
                                                        {{ $publicacion->total_reacciones }}
                                                    </span>
                                                    <span>
                                                        <i class="ti ti-message me-1"></i>
                                                        {{ $publicacion->total_comentarios }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted">
                                        <i class="ti ti-file-text fs-1 opacity-50"></i>
                                        <p>Aún no tienes publicaciones</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Galería Tab -->
                            <div class="tab-pane" id="tab-galeria">
                                <div class="row">
                                    @forelse($perfil->publicaciones->whereNotNull('archivos')->take(12) as $publicacion)
                                        @foreach($publicacion->archivos as $archivo)
                                            @if(str_starts_with($archivo['tipo'], 'image/'))
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="card">
                                                        <img src="{{ Storage::url($archivo['path']) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                        <div class="card-body p-2">
                                                            <small class="text-muted">{{ $archivo['nombre'] }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @empty
                                        <div class="col-12 text-center text-muted">
                                            <i class="ti ti-photo fs-1 opacity-50"></i>
                                            <p>No tienes imágenes en tu galería</p>
                                        </div>
                                    @endforelse
                                </div>
                                @if($perfil->publicaciones->whereNotNull('archivos')->count() > 12)
                                    <div class="text-center">
                                        <a href="{{ route('perfil.galeria') }}" class="btn btn-outline-primary">
                                            Ver galería completa
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    // Aquí se puede agregar JavaScript para funcionalidades interactivas
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tabs
        var triggerTabList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tab"]'));
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
        });
    });
</script>
@endpush
@endsection