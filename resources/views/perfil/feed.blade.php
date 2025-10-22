@extends('tablar::page')

@section('title', 'Feed Social')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-news me-2"></i>
                    Feed Social
                </h2>
                <div class="text-muted mt-1">Comparte y descubre publicaciones de tus compañeros</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('perfil.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-user me-1"></i>
                        Mi Perfil
                    </a>
                    <a href="{{ route('perfil.galeria') }}" class="btn btn-outline-primary">
                        <i class="ti ti-photo me-1"></i>
                        Galería
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Mi información -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg mb-3" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);">
                            <i class="ti ti-{{ $perfil->icono_personalizado }} text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <h5 class="card-title">{{ $perfil->alumno->Nombre }}</h5>
                        <p class="text-muted small">{{ $perfil->alumno->especialidad }} - {{ $perfil->alumno->semestre }}°</p>
                        @if($perfil->estado)
                            <div class="badge bg-success-lt">{{ $perfil->estado }}</div>
                        @endif
                    </div>
                </div>

                <!-- Navegación rápida -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Navegación</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('perfil.feed') }}" class="list-group-item list-group-item-action active">
                            <i class="ti ti-news me-2"></i>
                            Feed Principal
                        </a>
                        <a href="{{ route('perfil.index') }}" class="list-group-item list-group-item-action">
                            <i class="ti ti-user me-2"></i>
                            Mi Perfil
                        </a>
                        <a href="{{ route('perfil.galeria') }}" class="list-group-item list-group-item-action">
                            <i class="ti ti-photo me-2"></i>
                            Galería
                        </a>
                        <a href="{{ route('perfil.buscar') }}" class="list-group-item list-group-item-action">
                            <i class="ti ti-search me-2"></i>
                            Buscar Compañeros
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="col-lg-6">
                <!-- Formulario para nueva publicación -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-pencil me-1"></i>
                            Crear Publicación
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('perfil.publicaciones.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <textarea name="contenido" class="form-control" rows="3" placeholder="¿Qué estás pensando?" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <select name="tipo" class="form-select" id="tipo-publicacion">
                                            <option value="texto">Solo texto</option>
                                            <option value="imagen">Con imagen</option>
                                            <option value="archivo">Con archivo</option>
                                            <option value="galeria">Galería</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="file" name="archivos[]" class="form-control" multiple id="archivos-input" style="display: none;">
                                        <button type="button" class="btn btn-outline-secondary w-100" id="btn-archivos" style="display: none;">
                                            <i class="ti ti-paperclip me-1"></i>
                                            Seleccionar archivos
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="etiquetas" class="form-control" placeholder="Etiquetas (separadas por comas)">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Comparte con tus compañeros de grupo y especialidad
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-send me-1"></i>
                                    Publicar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lista de publicaciones -->
                @forelse($publicaciones as $publicacion)
                    <div class="card mb-4 publicacion-card" data-id="{{ $publicacion->id }}">
                        <div class="card-body">
                            <!-- Cabecera de la publicación -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-sm me-3" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);">
                                    <i class="ti ti-{{ $publicacion->perfil->icono_personalizado }} text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-0">
                                        <a href="{{ route('perfil.show', $publicacion->perfil->id) }}" class="text-decoration-none">
                                            {{ $publicacion->perfil->alumno->Nombre }}
                                        </a>
                                    </h6>
                                    <div class="text-muted small">
                                        {{ $publicacion->created_at->diffForHumans() }}
                                        @if($publicacion->tipo !== 'texto')
                                            <span class="badge bg-{{ $publicacion->tipo === 'imagen' ? 'success' : ($publicacion->tipo === 'archivo' ? 'info' : 'warning') }}-lt ms-1">
                                                {{ ucfirst($publicacion->tipo) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($publicacion->perfil->id === $perfil->id)
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
                                @endif
                            </div>

                            <!-- Contenido de la publicación -->
                            <div class="mb-3">
                                <p class="card-text">{{ $publicacion->contenido }}</p>
                                
                                <!-- Etiquetas -->
                                @if($publicacion->etiquetas && count($publicacion->etiquetas) > 0)
                                    <div class="mb-2">
                                        @foreach($publicacion->etiquetas as $etiqueta)
                                            <a href="{{ route('perfil.publicaciones.etiqueta', $etiqueta) }}" class="badge bg-primary-lt text-decoration-none me-1">
                                                #{{ $etiqueta }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Archivos adjuntos -->
                                @if($publicacion->archivos && count($publicacion->archivos) > 0)
                                    <div class="row">
                                        @foreach($publicacion->archivos as $index => $archivo)
                                            <div class="col-md-6 mb-2">
                                                @if(str_starts_with($archivo['tipo'], 'image/'))
                                                    <img src="{{ Storage::url($archivo['path']) }}" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                                                @else
                                                    <div class="card">
                                                        <div class="card-body text-center">
                                                            <i class="ti ti-file-text fs-1 text-muted"></i>
                                                            <p class="small">{{ $archivo['nombre'] }}</p>
                                                            <a href="{{ route('perfil.publicaciones.descargar-archivo', [$publicacion->id, $index]) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="ti ti-download me-1"></i>
                                                                Descargar
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Estadísticas y acciones -->
                            <div class="d-flex justify-content-between align-items-center text-muted small mb-2">
                                <span class="reacciones-count">{{ $publicacion->total_reacciones }} reacciones</span>
                                <span class="comentarios-count">{{ $publicacion->total_comentarios }} comentarios</span>
                            </div>

                            <hr>

                            <!-- Botones de acción -->
                            <div class="d-flex justify-content-around">
                                <button class="btn btn-outline-primary btn-sm flex-fill me-2 btn-reaccionar" data-publicacion="{{ $publicacion->id }}">
                                    <i class="ti ti-heart me-1"></i>
                                    Me gusta
                                </button>
                                <button class="btn btn-outline-secondary btn-sm flex-fill btn-comentar" data-publicacion="{{ $publicacion->id }}">
                                    <i class="ti ti-message me-1"></i>
                                    Comentar
                                </button>
                            </div>

                            <!-- Sección de comentarios (inicialmente oculta) -->
                            <div class="comentarios-section mt-3" id="comentarios-{{ $publicacion->id }}" style="display: none;">
                                <hr>
                                <div class="comentarios-lista mb-3">
                                    @foreach($publicacion->comentarios->take(3) as $comentario)
                                        <div class="d-flex mb-2">
                                            <div class="avatar avatar-xs me-2" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);">
                                                <i class="ti ti-{{ $comentario->perfil->icono_personalizado }} text-white" style="font-size: 0.7rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="bg-light rounded p-2">
                                                    <strong class="small">{{ $comentario->perfil->alumno->Nombre }}</strong>
                                                    <p class="mb-0 small">{{ $comentario->contenido }}</p>
                                                </div>
                                                <div class="text-muted small">{{ $comentario->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Formulario para nuevo comentario -->
                                <form class="form-comentario" data-publicacion="{{ $publicacion->id }}">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="contenido" class="form-control form-control-sm" placeholder="Escribe un comentario...">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="ti ti-send"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="ti ti-news fs-1 text-muted opacity-50"></i>
                            <h5 class="mt-3">No hay publicaciones aún</h5>
                            <p class="text-muted">¡Sé el primero en compartir algo con tus compañeros!</p>
                        </div>
                    </div>
                @endforelse

                <!-- Paginación -->
                @if($publicaciones->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $publicaciones->links() }}
                    </div>
                @endif
            </div>

            <!-- Sidebar derecho -->
            <div class="col-lg-3">
                <!-- Compañeros sugeridos -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Compañeros de Grupo</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <a href="{{ route('perfil.buscar') }}" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-search me-1"></i>
                                Buscar compañeros
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar tipo de publicación
    const tipoSelect = document.getElementById('tipo-publicacion');
    const archivosInput = document.getElementById('archivos-input');
    const btnArchivos = document.getElementById('btn-archivos');

    tipoSelect.addEventListener('change', function() {
        if (this.value === 'texto') {
            archivosInput.style.display = 'none';
            btnArchivos.style.display = 'none';
        } else {
            archivosInput.style.display = 'none';
            btnArchivos.style.display = 'block';
        }
    });

    btnArchivos.addEventListener('click', function() {
        archivosInput.click();
    });

    archivosInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            btnArchivos.innerHTML = `<i class="ti ti-paperclip me-1"></i>${this.files.length} archivo(s) seleccionado(s)`;
            btnArchivos.classList.add('btn-success');
            btnArchivos.classList.remove('btn-outline-secondary');
        }
    });

    // Manejar comentarios
    document.querySelectorAll('.btn-comentar').forEach(btn => {
        btn.addEventListener('click', function() {
            const publicacionId = this.dataset.publicacion;
            const comentariosSection = document.getElementById('comentarios-' + publicacionId);
            
            if (comentariosSection.style.display === 'none') {
                comentariosSection.style.display = 'block';
            } else {
                comentariosSection.style.display = 'none';
            }
        });
    });

    // Manejar envío de comentarios (esto se implementaría con AJAX)
    document.querySelectorAll('.form-comentario').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Aquí se implementaría el envío por AJAX
            alert('Funcionalidad de comentarios en desarrollo');
        });
    });

    // Manejar reacciones (esto se implementaría con AJAX)
    document.querySelectorAll('.btn-reaccionar').forEach(btn => {
        btn.addEventListener('click', function() {
            // Aquí se implementaría el sistema de reacciones
            alert('Funcionalidad de reacciones en desarrollo');
        });
    });
});
</script>
@endpush
@endsection