@extends('tablar::page')

@section('title', 'Configurar Página de Inicio')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Configurar Página de Inicio</h3>
        </div>
        <form action="{{ route('admin.pagina-inicio.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Sección Principal -->
            <div class="card-body">
                <h4>Sección Principal</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Título Principal *</label>
                            <input type="text" name="titulo_principal" class="form-control" 
                                   value="{{ old('titulo_principal', $configuracion->titulo_principal) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Imagen Principal</label>
                            <input type="file" name="imagen_principal" class="form-control crop-trigger" 
                                   accept="image/*" data-target="#cropModal" data-type="principal">
                            
                            <!-- Campo oculto para recorte de imagen principal -->
                            <input type="hidden" name="imagen_principal_crop_data" 
                                   value="{{ $configuracion->imagen_principal_crop_data ?? '' }}" 
                                   class="crop-data-input-principal">
                            
                            @if($configuracion->imagen_principal)
                                @php
                                    $imagePath = 'storage/' . $configuracion->imagen_principal;
                                    $fullImageUrl = asset($imagePath);
                                @endphp
                                
                                <div class="mt-2">
                                    <img src="{{ $fullImageUrl }}" 
                                         alt="Imagen actual" 
                                         class="img-thumbnail preview-image" 
                                         width="100" 
                                         height="100" 
                                         style="object-fit: cover; cursor: pointer;"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal"
                                         data-image="{{ $fullImageUrl }}"
                                         data-title="Imagen Principal">
                                    <div class="mt-1">
                                        <small class="text-muted">Haz clic para expandir</small>
                                        <br>
                                        <button type="button" class="btn btn-warning btn-sm mt-1 recortar-btn" 
                                                data-foto="{{ $configuracion->imagen_principal }}"
                                                data-crop-data="{{ $configuracion->imagen_principal_crop_data ?? '' }}"
                                                data-target="principal">
                                            <i class="fas fa-crop-alt"></i> Re-centrar
                                        </button>
                                    </div>
                                    <input type="hidden" name="imagen_principal_actual" value="{{ $configuracion->imagen_principal }}">
                                </div>
                            @else
                            <div class="mt-2">
                                <div class="text-muted small">No hay imagen actual</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Descripción *</label>
                    <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion', $configuracion->descripcion) }}</textarea>
                </div>

                <!-- Sección About editable -->
                <hr>
                <h4>Sección Acerca de</h4>
                @php
                    $about = $configuracion->about;
                    if (is_string($about)) {
                        $about = json_decode($about, true);
                    }
                    $mision = old('about_mision', $about['mision'] ?? '');
                    $vision = old('about_vision', $about['vision'] ?? '');
                    $valores = old('about_valores', $about['valores'] ?? ['']);
                    if (!is_array($valores)) {
                        $valores = [$valores];
                    }
                @endphp
                <div class="form-group mb-3">
                    <label>Quienes Somos</label>
                    <input type="text" name="about_mision" class="form-control" value="{{ $mision }}">
                </div>
                <div class="form-group mb-3">
                    <label>Institución</label>
                    <input type="text" name="about_vision" class="form-control" value="{{ $vision }}">
                </div>
                <div class="form-group mb-3">
                    <label>Valores</label>
                    <div id="valores-container">
                        @foreach($valores as $i => $valor)
                        <div class="input-group mb-2 valor-item">
                            <input type="text" name="about_valores[]" class="form-control" value="{{ $valor }}" placeholder="Ingresa un valor...">
                            <button type="button" class="btn btn-danger remove-valor" onclick="removeValor(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-success mt-2" onclick="addValor()">
                        <i class="fas fa-plus"></i> Agregar Valor
                    </button>
                </div>
            </div>

            <!-- Sección Equipo -->
            <div class="card-body border-top">
                <h4>Equipo de Desarrollo</h4>
                <div class="form-group">
                    <label>Título de la Sección *</label>
                    <input type="text" name="titulo_equipo" class="form-control" 
                           value="{{ old('titulo_equipo', $configuracion->titulo_equipo) }}" required>
                </div>

                <div id="miembros-container">
                    @php
                        $miembros = $configuracion->miembros_equipo;
                        if (is_string($miembros)) {
                            $miembros = json_decode($miembros, true) ?: [['nombre' => '', 'cargo' => '', 'foto' => null]];
                        } elseif (is_array($miembros)) {
                            $miembros = !empty($miembros) ? $miembros : [['nombre' => '', 'cargo' => '', 'foto' => null]];
                        } else {
                            $miembros = [['nombre' => '', 'cargo' => '', 'foto' => null]];
                        }
                    @endphp
                    
                    @foreach($miembros as $index => $miembro)
                    <div class="card mb-3 miembro-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nombre del Miembro</label>
                                        <input type="text" name="miembros_nombre[]" class="form-control" 
                                               value="{{ old('miembros_nombre.' . $index, $miembro['nombre'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cargo</label>
                                        <input type="text" name="miembros_cargo[]" class="form-control" 
                                               value="{{ old('miembros_cargo.' . $index, $miembro['cargo'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Foto</label>
                                        <input type="file" name="miembros_foto[]" class="form-control crop-trigger" 
                                               accept="image/*" data-target="#cropModal" data-type="miembro">
                                        
                                        <!-- Campos ocultos para el centrado -->
                                        <input type="hidden" name="miembros_crop_data[]" 
                                               value="{{ $miembro['crop_data'] ?? '' }}" 
                                               class="crop-data-input">
                                        
                                        @if(!empty($miembro['foto']))
                                            @php
                                                $memberImagePath = 'storage/' . $miembro['foto'];
                                                $memberImageUrl = asset($memberImagePath);
                                            @endphp
                                            
                                            <div class="mt-2">
                                                <img src="{{ $memberImageUrl }}" 
                                                     alt="Foto actual" 
                                                     class="img-thumbnail preview-image" 
                                                     width="80" 
                                                     height="80" 
                                                     style="object-fit: cover; cursor: pointer;"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#imageModal"
                                                     data-image="{{ $memberImageUrl }}"
                                                     data-title="Foto de {{ $miembro['nombre'] ?? 'Miembro' }}">
                                                <div class="mt-1">
                                                    <small class="text-muted">Haz clic para expandir</small>
                                                    <br>
                                                    <button type="button" class="btn btn-warning btn-sm mt-1 recortar-btn" 
                                                            data-foto="{{ $miembro['foto'] }}"
                                                            data-crop-data="{{ $miembro['crop_data'] ?? '' }}"
                                                            data-target="miembro">
                                                        <i class="fas fa-crop-alt"></i> Re-centrar
                                                    </button>
                                                </div>
                                                <input type="hidden" name="miembros_foto_actual[]" value="{{ $miembro['foto'] }}">
                                            </div>
                                        @else
                                        <div class="mt-2">
                                            <div class="text-muted small">No hay foto actual</div>
                                            <input type="hidden" name="miembros_foto_actual[]" value="">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-danger remove-miembro" onclick="removeMiembro(this)">
                                    <i class="fas fa-trash"></i> Eliminar Miembro
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <button type="button" class="btn btn-success" onclick="addMiembro()">
                    <i class="fas fa-plus"></i> Agregar Miembro
                </button>
            </div>

            <!-- Sección Novedades -->
            <div class="card-body border-top">
                <h4>Novedades</h4>
                <div class="form-group">
                    <label>Título de la Sección *</label>
                    <input type="text" name="titulo_novedades" class="form-control" 
                           value="{{ old('titulo_novedades', $configuracion->titulo_novedades) }}" required>
                </div>

                <div id="novedades-container">
                    @php
                        $novedades = $configuracion->novedades;
                        if (is_string($novedades)) {
                            $novedades = json_decode($novedades, true) ?: [['titulo' => '', 'fecha' => date('Y-m-d'), 'descripcion' => '']];
                        } elseif (is_array($novedades)) {
                            $novedades = !empty($novedades) ? $novedades : [['titulo' => '', 'fecha' => date('Y-m-d'), 'descripcion' => '']];
                        } else {
                            $novedades = [['titulo' => '', 'fecha' => date('Y-m-d'), 'descripcion' => '']];
                        }
                    @endphp
                    
                    @foreach($novedades as $index => $novedad)
                    <div class="card mb-3 novedad-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Título</label>
                                        <input type="text" name="novedades_titulo[]" class="form-control" 
                                               value="{{ old('novedades_titulo.' . $index, $novedad['titulo'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date" name="novedades_fecha[]" class="form-control" 
                                               value="{{ old('novedades_fecha.' . $index, $novedad['fecha'] ?? date('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Descripción</label>
                                        <textarea name="novedades_descripcion[]" class="form-control" rows="2">{{ old('novedades_descripcion.' . $index, $novedad['descripcion'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-danger btn-sm remove-novedad" onclick="removeNovedad(this)">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <button type="button" class="btn btn-success btn-sm" onclick="addNovedad()">
                    <i class="fas fa-plus"></i> Agregar Novedad
                </button>
            </div>

            <!-- Sección Contacto -->
            <div class="card-body border-top">
                <h4>Información de Contacto</h4>
                <div class="form-group">
                    <label>Título de la Sección *</label>
                    <input type="text" name="titulo_contacto" class="form-control" 
                           value="{{ old('titulo_contacto', $configuracion->titulo_contacto) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email_contacto" class="form-control" 
                                   value="{{ old('email_contacto', $configuracion->email_contacto) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Teléfono *</label>
                            <input type="text" name="telefono_contacto" class="form-control" 
                                   value="{{ old('telefono_contacto', $configuracion->telefono_contacto) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Dirección *</label>
                    <input type="text" name="direccion_contacto" class="form-control" 
                           value="{{ old('direccion_contacto', $configuracion->direccion_contacto) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Facebook URL</label>
                            <input type="url" name="facebook" class="form-control" 
                                   value="{{ old('facebook', $configuracion->facebook) }}" placeholder="https://facebook.com/...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>WhatsApp URL</label>
                            <input type="url" name="whatsapp" class="form-control" 
                                   value="{{ old('whatsapp', $configuracion->whatsapp) }}" placeholder="https://wa.me/...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Instagram URL</label>
                            <input type="url" name="instagram" class="form-control" 
                                   value="{{ old('instagram', $configuracion->instagram) }}" placeholder="https://instagram.com/...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Guardar Configuración
                </button>
                <a href="{{ route('inicio') }}" class="btn btn-secondary btn-lg" target="_blank">
                    <i class="fas fa-eye"></i> Ver Página
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Modal para expandir imagen -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Imagen expandida" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Modal para recortar imagen - MEJORADO -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Recortar y Centrar Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="container-fluid h-100">
                    <div class="row h-100">
                        <!-- ÁREA PRINCIPAL DE RECORTE - 70% DE LA PANTALLA -->
                        <div class="col-12 col-lg-8 d-flex flex-column">
                            <div class="crop-main-area flex-grow-1 d-flex align-items-center justify-content-center p-3">
                                <div class="crop-container-wrapper">
                                    <div id="cropContainer" class="crop-container-large">
                                        <img id="cropImage" src="" alt="Imagen a recortar" class="crop-image-large">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- INSTRUCCIONES SIMPLES -->
                            <div class="crop-instructions p-3 border-top bg-light">
                                <div class="text-center">
                                    <h6 class="mb-2">INSTRUCCIONES</h6>
                                    <p class="mb-0 text-muted">
                                        <small>
                                            Arrastra el recuadro para moverlo • Usa la rueda del mouse para zoom • 
                                            Ajusta los bordes arrastrando las esquinas
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- PANEL DE CONFIGURACIÓN - 30% DE LA PANTALLA -->
                        <div class="col-12 col-lg-4 border-start">
                            <div class="controls-panel h-100 p-3">
                                <!-- Vista previa -->
                                <div class="preview-section mb-4 text-center">
                                    <h5 class="section-title">VISTA PREVIA</h5>
                                    <div id="preview" class="preview-box-large"></div>
                                    <small class="text-muted d-block mt-2">Así se verá la imagen en el sitio</small>
                                </div>
                                
                                <!-- Configuración de forma -->
                                <div class="config-section mb-4">
                                    <h5 class="section-title">FORMA DEL RECORTE</h5>
                                    <select id="aspectRatio" class="form-select">
                                        <option value="1">Cuadrado (1:1)</option>
                                        <option value="4/3" selected>Rectángulo (4:3)</option>
                                        <option value="16/9">Panorámico (16:9)</option>
                                        <option value="0">Libre</option>
                                    </select>
                                </div>
                                
                                <!-- Tamaño del recorte -->
                                <div class="config-section mb-4">
                                    <h5 class="section-title">TAMAÑO DEL RECORTE</h5>
                                    <input type="range" id="cropSize" class="form-range" min="30" max="150" value="100" 
                                           onchange="changeCropSize(this.value)">
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">Pequeño</small>
                                        <strong id="sizeLabel" class="text-primary">100%</strong>
                                        <small class="text-muted">Grande</small>
                                    </div>
                                </div>
                                
                                <!-- Información -->
                                <div class="info-section mt-4">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-lightbulb me-2"></i>CONSEJOS RÁPIDOS</h6>
                                        <ul class="mb-0 ps-3">
                                            <li>Arrastra para mover el área de recorte</li>
                                            <li>Usa la rueda del mouse para hacer zoom</li>
                                            <li>Ajusta las esquinas para cambiar el tamaño</li>
                                            <li>La vista previa muestra el resultado final</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-lg btn-primary" id="cropButton">
                    <i class="fas fa-crop-alt me-2"></i> Aplicar Recorte
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.css">

<script>
// Variables globales
let cropper;
let currentCropInput;
let currentCropData;
let currentCropType; // 'principal' o 'miembro'
let originalData = null;

// Inicialización cuando se carga el documento
document.addEventListener('DOMContentLoaded', function() {
    // Modal para expandir imagen
    const imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const imageUrl = button.getAttribute('data-image');
        const imageTitle = button.getAttribute('data-title');
        
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModalTitle').textContent = imageTitle;
    });

    // Modal para recortar imagen
    const cropModal = document.getElementById('cropModal');
    
    // Trigger para abrir el modal de recorte
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('crop-trigger') && e.target.files[0]) {
            currentCropInput = e.target;
            currentCropType = e.target.getAttribute('data-type');
            openCropModal(e.target.files[0]);
        }
    });

    // Botones de re-centrar para fotos existentes
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('recortar-btn') || 
            e.target.closest('.recortar-btn')) {
            const button = e.target.classList.contains('recortar-btn') ? 
                          e.target : e.target.closest('.recortar-btn');
            recortarFotoExistente(button);
        }
    });

    // Botón de aplicar recorte
    document.getElementById('cropButton').addEventListener('click', function() {
        aplicarRecorte();
    });

    // Cerrar modal y limpiar
    cropModal.addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        currentCropInput = null;
        currentCropData = null;
        currentCropType = null;
        originalData = null;
    });
});

// Función para abrir modal de recorte
function openCropModal(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const image = document.getElementById('cropImage');
        image.src = e.target.result;
        
        initializeCropper();
        new bootstrap.Modal(document.getElementById('cropModal')).show();
    };
    reader.readAsDataURL(file);
}

// Función para re-centrar foto existente
function recortarFotoExistente(button) {
    const fotoUrl = "{{ asset('storage/') }}/" + button.getAttribute('data-foto');
    const cropData = button.getAttribute('data-crop-data');
    const targetType = button.getAttribute('data-target');
    
    currentCropType = targetType;
    
    if (targetType === 'principal') {
        currentCropInput = document.querySelector('input[name="imagen_principal"]');
        currentCropData = document.querySelector('.crop-data-input-principal');
    } else {
        currentCropInput = button.closest('.form-group').querySelector('input[type="file"]');
        currentCropData = button.closest('.form-group').querySelector('.crop-data-input');
    }
    
    const image = document.getElementById('cropImage');
    image.src = fotoUrl;
    
    initializeCropper(cropData);
    new bootstrap.Modal(document.getElementById('cropModal')).show();
}

// Inicializar Cropper
function initializeCropper(cropData = null) {
    if (cropper) {
        cropper.destroy();
    }
    
    // Configurar relación de aspecto según el tipo
    let aspectRatio = 4/3; // Rectángulo por defecto
    if (currentCropType === 'miembro') {
        aspectRatio = 1; // Cuadrado para miembros
    }
    
    cropper = new Cropper(document.getElementById('cropImage'), {
        aspectRatio: aspectRatio,
        viewMode: 1,
        guides: true,
        background: false,
        autoCropArea: 0.8,
        responsive: true,
        preview: '#preview',
        movable: true,
        zoomable: true,
        rotatable: false,
        scalable: false,
        zoomOnWheel: true,
        minContainerWidth: 800,
        minContainerHeight: 600
    });

    // Guardar datos originales
    originalData = cropper.getData();

    // Aplicar datos de recorte previos si existen
    if (cropData && cropData !== '') {
        try {
            const data = JSON.parse(cropData);
            cropper.setData(data);
        } catch (e) {
            console.log('No se pudieron cargar los datos de recorte previos');
        }
    }

    // Configurar eventos
    document.getElementById('aspectRatio').addEventListener('change', function() {
        const ratio = this.value;
        if (ratio === '0') {
            cropper.setAspectRatio(NaN);
        } else {
            cropper.setAspectRatio(eval(ratio));
        }
    });
}

function changeCropSize(value) {
    if (!cropper) return;
    
    const data = cropper.getData();
    const scale = value / 100;
    const newWidth = data.width * scale;
    const newHeight = data.height * scale;
    
    cropper.setData({
        width: newWidth,
        height: newHeight
    });
    
    document.getElementById('sizeLabel').textContent = `${value}%`;
}

// CORRECCIÓN CRÍTICA: Función para aplicar el recorte
function aplicarRecorte() {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas();
    const cropData = cropper.getData();
    
    // CORRECCIÓN: Guardar datos de recorte en el campo oculto correspondiente
    if (currentCropData) {
        currentCropData.value = JSON.stringify(cropData);
    }
    
    canvas.toBlob(function(blob) {
        const fileName = currentCropType === 'principal' ? 'imagen_principal_recortada.jpg' : 'imagen_recortada.jpg';
        const file = new File([blob], fileName, {
            type: 'image/jpeg',
            lastModified: new Date().getTime()
        });
        
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        
        if (currentCropInput) {
            currentCropInput.files = dataTransfer.files;
            
            // Mostrar preview
            const previewUrl = canvas.toDataURL();
            let previewContainer;
            
            if (currentCropType === 'principal') {
                previewContainer = currentCropInput.closest('.form-group').querySelector('.mt-2');
            } else {
                previewContainer = currentCropInput.closest('.form-group').querySelector('.mt-2');
            }
            
            if (!previewContainer.querySelector('.preview-image')) {
                crearPreview(previewContainer, previewUrl, 'Imagen recortada');
            } else {
                actualizarPreview(previewContainer, previewUrl);
            }
        }
        
        bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
    }, 'image/jpeg', 0.9);
}

// Funciones auxiliares para preview
function crearPreview(container, previewUrl, title) {
    const previewImg = document.createElement('img');
    previewImg.src = previewUrl;
    previewImg.className = 'img-thumbnail preview-image';
    
    if (currentCropType === 'principal') {
        previewImg.style.cssText = 'width: 100px; height: 100px; object-fit: cover; cursor: pointer;';
    } else {
        previewImg.style.cssText = 'width: 80px; height: 80px; object-fit: cover; cursor: pointer;';
    }
    
    previewImg.setAttribute('data-bs-toggle', 'modal');
    previewImg.setAttribute('data-bs-target', '#imageModal');
    previewImg.setAttribute('data-image', previewUrl);
    previewImg.setAttribute('data-title', title);
    
    const previewText = document.createElement('div');
    previewText.className = 'mt-1';
    previewText.innerHTML = '<small class="text-muted">Haz clic para expandir</small>';
    
    container.innerHTML = '';
    container.appendChild(previewImg);
    container.appendChild(previewText);
}

function actualizarPreview(container, previewUrl) {
    const previewImg = container.querySelector('.preview-image');
    if (previewImg) {
        previewImg.src = previewUrl;
        previewImg.setAttribute('data-image', previewUrl);
    }
}

// Funciones para valores
function addValor() {
    const container = document.getElementById('valores-container');
    const nuevoValor = `
        <div class="input-group mb-2 valor-item">
            <input type="text" name="about_valores[]" class="form-control" placeholder="Ingresa un valor...">
            <button type="button" class="btn btn-danger remove-valor" onclick="removeValor(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', nuevoValor);
}

function removeValor(button) {
    if (document.querySelectorAll('.valor-item').length > 1) {
        button.closest('.valor-item').remove();
    } else {
        alert('Debe haber al menos un valor.');
    }
}

// Funciones para miembros
function addMiembro() {
    const container = document.getElementById('miembros-container');
    
    const nuevoMiembro = `
        <div class="card mb-3 miembro-item">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nombre del Miembro</label>
                            <input type="text" name="miembros_nombre[]" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cargo</label>
                            <input type="text" name="miembros_cargo[]" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" name="miembros_foto[]" class="form-control crop-trigger" 
                                   accept="image/*" data-target="#cropModal" data-type="miembro">
                            <input type="hidden" name="miembros_crop_data[]" value="" class="crop-data-input">
                            <div class="mt-2">
                                <div class="text-muted small">No hay foto actual</div>
                                <input type="hidden" name="miembros_foto_actual[]" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-danger remove-miembro" onclick="removeMiembro(this)">
                        <i class="fas fa-trash"></i> Eliminar Miembro
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', nuevoMiembro);
}

function removeMiembro(button) {
    if (document.querySelectorAll('.miembro-item').length > 1) {
        button.closest('.miembro-item').remove();
    } else {
        alert('Debe haber al menos un miembro del equipo.');
    }
}

// Funciones para novedades
function addNovedad() {
    const container = document.getElementById('novedades-container');
    
    const nuevaNovedad = `
        <div class="card mb-3 novedad-item">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="novedades_titulo[]" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha</label>
                            <input type="date" name="novedades_fecha[]" class="form-control" value="${new Date().toISOString().split('T')[0]}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="novedades_descripcion[]" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-danger btn-sm remove-novedad" onclick="removeNovedad(this)">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', nuevaNovedad);
}

function removeNovedad(button) {
    if (document.querySelectorAll('.novedad-item').length > 1) {
        button.closest('.novedad-item').remove();
    } else {
        alert('Debe haber al menos una novedad.');
    }
}
</script>

<style>
/* Estilos optimizados para el recorte */
.crop-trigger {
    cursor: pointer;
}

/* MEJORAS EN BOTONES - TAMAÑOS MEJORADOS */
.recortar-btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.9rem;
}

.btn-success, .btn-danger {
    padding: 0.5rem 1rem;
    font-size: 0.95rem;
}

.remove-valor {
    padding: 0.5rem 0.75rem;
}

.remove-miembro {
    padding: 0.5rem 1rem;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

/* MODAL FULLSCREEN */
.modal-fullscreen .modal-content {
    height: 100vh;
}

/* ÁREA PRINCIPAL DE RECORTE - 70% */
.crop-main-area {
    background: #1a1a1a;
    min-height: 70vh;
}

.crop-container-wrapper {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.crop-container-large {
    max-width: 90%;
    max-height: 90%;
    min-width: 600px;
    min-height: 400px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.5);
}

.crop-image-large {
    max-width: 100%;
    max-height: 100%;
    display: block;
}

/* INSTRUCCIONES */
.crop-instructions {
    background: #f8f9fa !important;
}

/* PANEL DE CONFIGURACIÓN */
.controls-panel {
    background: #f8f9fa;
    overflow-y: auto;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 15px;
    text-align: center;
}

/* VISTA PREVIA GRANDE */
.preview-box-large {
    width: 200px;
    height: 200px;
    border: 3px solid #dee2e6;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* CONFIGURACIÓN */
.config-section {
    background: white;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* BOTONES DEL FOOTER */
.modal-footer .btn {
    min-width: 150px;
    padding: 12px 24px;
    font-size: 1.1rem;
}

/* ESTILOS CROPPER PERSONALIZADOS */
.cropper-view-box {
    border-radius: 50%;
    outline: 4px solid #667eea;
    outline-color: rgba(102, 126, 234, 0.9);
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
}

.cropper-face {
    background-color: transparent;
}

.cropper-line, .cropper-point {
    background-color: #667eea;
}

.cropper-point.point-se {
    background-color: #28a745;
    width: 12px;
    height: 12px;
}

/* RESPONSIVE MEJORADO */
@media (max-width: 1199.98px) {
    .crop-container-large {
        min-width: 500px;
        min-height: 350px;
    }
}

@media (max-width: 991.98px) {
    .crop-container-large {
        min-width: 400px;
        min-height: 300px;
    }
    
    .preview-box-large {
        width: 150px;
        height: 150px;
    }
    
    .modal-fullscreen .modal-dialog {
        margin: 0;
        max-width: 100%;
    }
}

@media (max-width: 767.98px) {
    .crop-container-large {
        min-width: 300px;
        min-height: 250px;
        max-width: 95%;
    }
    
    .preview-box-large {
        width: 120px;
        height: 120px;
    }
    
    .controls-panel {
        padding: 15px;
    }
    
    .config-section {
        padding: 15px;
    }
    
    .modal-footer .btn {
        min-width: 120px;
        padding: 10px 20px;
        font-size: 1rem;
    }
    
    /* MEJORAS EN BOTONES PARA MÓVIL */
    .btn-success, .btn-danger {
        padding: 0.6rem 1rem;
        font-size: 1rem;
    }
    
    .remove-valor {
        padding: 0.6rem 0.8rem;
    }
}

@media (max-width: 575.98px) {
    .crop-container-large {
        min-width: 250px;
        min-height: 200px;
    }
    
    .preview-box-large {
        width: 100px;
        height: 100px;
    }
    
    .controls-panel {
        padding: 10px;
    }
    
    .config-section {
        padding: 12px;
    }
    
    .section-title {
        font-size: 1rem;
    }
    
    .modal-footer .btn {
        min-width: 100px;
        padding: 8px 16px;
        font-size: 0.9rem;
    }
    
    /* LAYOUT VERTICAL EN MÓVIL */
    .modal-fullscreen .row {
        flex-direction: column;
    }
    
    .col-lg-4 {
        border-left: none !important;
        border-top: 1px solid #dee2e6;
    }
}

/* MEJORAS EN INPUT GROUPS */
.input-group {
    flex-wrap: nowrap;
}

.input-group .form-control {
    flex: 1;
}

.input-group .btn {
    white-space: nowrap;
}

/* Estilos para miembros y novedades */
.miembro-item, .novedad-item {
    position: relative;
}
.img-thumbnail {
    object-fit: cover;
}

/* POSICIÓN CORREGIDA PARA BOTONES ELIMINAR */
.text-end {
    text-align: right !important;
}

.mt-3 {
    margin-top: 1rem !important;
}
</style>
@endsection