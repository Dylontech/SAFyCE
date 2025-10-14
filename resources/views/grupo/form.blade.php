<div class="row">
    <div class="col-12">
        <div class="form-group mb-3">
            <label class="form-label">{{ Form::label('semestre', 'Semestre') }}</label>
            <div>
                {{ Form::select('semestre', [
                    '' => 'Seleccione un semestre',
                    '1' => 'Semestre 1',
                    '2' => 'Semestre 2',
                    '3' => 'Semestre 3',
                    '4' => 'Semestre 4',
                    '5' => 'Semestre 5',
                    '6' => 'Semestre 6',
                    '7' => 'Semestre 7',
                    '8' => 'Semestre 8',
                    '9' => 'Semestre 9',
                    '10' => 'Semestre 10',
                    '11' => 'Semestre 11',
                    '12' => 'Semestre 12'
                ], $grupo->semestre, ['class' => 'form-select' . ($errors->has('semestre') ? ' is-invalid' : ''), 'id' => 'semestre-select']) }}
                {!! $errors->first('semestre', '<div class="invalid-feedback">:message</div>') !!}
                <small class="form-hint">Seleccione el semestre correspondiente.</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ Form::label('letra', 'Letra del Grupo') }}</label>
            <div>
                {{ Form::text('letra', $grupo->letra, ['class' => 'form-control' .
                ($errors->has('letra') ? ' is-invalid' : ''), 'placeholder' => 'Ej: a, b, c, etc.', 'id' => 'letra-input']) }}
                {!! $errors->first('letra', '<div class="invalid-feedback">:message</div>') !!}
                <small class="form-hint">Escriba la letra que identifica al grupo (ejemplo: a, b, c).</small>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Nombre Completo (Vista Previa)</label>
            <div>
                <input type="text" class="form-control" id="nombre-preview" readonly 
                       placeholder="Se generará automáticamente...">
                <small class="form-hint">Este campo se genera automáticamente: semestre + letra.</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group mb-3">
            <div class="form-check">
                {{ Form::checkbox('activo', 1, $grupo->activo ?? true, ['class' => 'form-check-input' . ($errors->has('activo') ? ' is-invalid' : ''), 'id' => 'activo']) }}
                <label class="form-check-label" for="activo">
                    Grupo activo
                </label>
                {!! $errors->first('activo', '<div class="invalid-feedback">:message</div>') !!}
                <small class="form-hint d-block">Los grupos inactivos no aparecerán en los formularios de alumnos.</small>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="{{ route('grupos.index') }}" class="btn btn-link">Cancelar</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 5l0 14"/>
                    <path d="M5 12l14 0"/>
                </svg>
                Guardar grupo
            </button>
        </div>
    </div>
</div>

<script>
// Script para actualizar la vista previa del nombre completo
function actualizarVistaPrevia() {
    const semestre = document.getElementById('semestre-select').value;
    const letra = document.getElementById('letra-input').value.toLowerCase();
    const preview = document.getElementById('nombre-preview');
    
    if (semestre && letra) {
        preview.value = semestre + letra;
        preview.classList.remove('text-muted');
        preview.classList.add('text-success');
    } else {
        preview.value = 'Se generará automáticamente...';
        preview.classList.remove('text-success');
        preview.classList.add('text-muted');
    }
}

// Agregar eventos para actualizar vista previa
document.addEventListener('DOMContentLoaded', function() {
    const semestreSelect = document.getElementById('semestre-select');
    const letraInput = document.getElementById('letra-input');
    
    if (semestreSelect && letraInput) {
        semestreSelect.addEventListener('change', actualizarVistaPrevia);
        letraInput.addEventListener('input', actualizarVistaPrevia);
        
        // Actualizar vista previa al cargar la página
        actualizarVistaPrevia();
    }
});

// Validación del formulario antes del envío
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const semestre = document.getElementById('semestre-select').value;
            const letra = document.getElementById('letra-input').value.trim();
            
            if (!semestre) {
                e.preventDefault();
                alert('Por favor seleccione un semestre');
                return false;
            }
            
            if (!letra) {
                e.preventDefault();
                alert('Por favor ingrese la letra del grupo');
                return false;
            }
            
            // Validar que la letra sea solo caracteres alfabéticos
            if (!/^[a-zA-Z]+$/.test(letra)) {
                e.preventDefault();
                alert('La letra del grupo solo puede contener caracteres alfabéticos');
                return false;
            }
        });
    }
});
</script>
