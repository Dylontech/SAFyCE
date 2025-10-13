<div class="row">
    <div class="col-12">
        <div class="form-group mb-4">
            <label class="form-label">{{ Form::label('Description', 'Descripción de la imagen') }}</label>
            <div>
                {{ Form::text('Description', $carrusel->Description, ['class' => 'form-control' . ($errors->has('Description') ? ' is-invalid' : ''), 'placeholder' => 'Ingrese una descripción para la imagen']) }}
                {!! $errors->first('Description', '<div class="invalid-feedback">:message</div>') !!}
                <small class="form-hint">Describe brevemente el contenido de la imagen.</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group mb-4">
            <label class="form-label">{{ Form::label('image', 'Imagen del carrusel') }}</label>
            <div>
                {{ Form::file('image', ['class' => 'form-control' . ($errors->has('image') ? ' is-invalid' : ''), 'accept' => 'image/*', 'id' => 'imageInput']) }}
                {!! $errors->first('image', '<div class="invalid-feedback">:message</div>') !!}
                <small class="form-hint">
                    Formatos aceptados: JPG, PNG, GIF. Tamaño recomendado: 1024×1024px.
                </small>
            </div>
            
            <!-- Preview de la imagen -->
            <div id="imagePreview" class="mt-3 d-none">
                <div class="card">
                    <div class="card-body text-center">
                        <img id="previewImg" src="" alt="Vista previa" class="img-fluid rounded" style="max-height: 300px;">
                        <div class="mt-2">
                            <small class="text-muted">Vista previa de la imagen</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <div class="d-flex flex-column flex-sm-row gap-2">
        <a href="{{ route('carrusels.index') }}" class="btn btn-danger order-sm-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
            Cancelar
        </a>
        <button type="submit" class="btn btn-primary flex-fill order-sm-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <path d="M10 21v-6a1 1 0 0 1 1 -1h6"/>
                <path d="M12 11v-8a1 1 0 0 0 -1 -1h-6a1 1 0 0 0 -1 1v8a1 1 0 0 0 1 1h6"/>
                <path d="m3 15l4 -4l4 4"/>
            </svg>
            Subir imagen
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                }
                
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('d-none');
            }
        });
    }
});
</script>