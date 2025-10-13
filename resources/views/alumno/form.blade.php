<!-- Información Personal -->
<div class="card mb-4">
    <div class="card-header">
        <h4 class="card-title">Información Personal</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">{{ Form::label('numero_control', 'Número de Control') }}</label>
                    <div>
                        {{ Form::text('numero_control', $alumno->numero_control, ['class' => 'form-control' . ($errors->has('numero_control') ? ' is-invalid' : ''), 'placeholder' => 'Ej: 24308051230001']) }}
                        {!! $errors->first('numero_control', '<div class="invalid-feedback">:message</div>') !!}
                        <small class="form-hint">Ingrese el número de control único del alumno.</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">{{ Form::label('CURP', 'CURP') }}</label>
                    <div>
                        {{ Form::text('CURP', $alumno->CURP, ['class' => 'form-control' . ($errors->has('CURP') ? ' is-invalid' : ''), 'placeholder' => 'Ej: ABCD123456HDFGHJ07', 'maxlength' => '18']) }}
                        {!! $errors->first('CURP', '<div class="invalid-feedback">:message</div>') !!}
                        <small class="form-hint">CURP de 18 caracteres del alumno.</small>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group mb-3">
                    <label class="form-label">{{ Form::label('Nombre', 'Nombre Completo') }}</label>
                    <div>
                        {{ Form::text('Nombre', $alumno->Nombre, ['class' => 'form-control' . ($errors->has('Nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre completo del alumno']) }}
                        {!! $errors->first('Nombre', '<div class="invalid-feedback">:message</div>') !!}
                        <small class="form-hint">Nombre completo: Apellidos, Nombres.</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">{{ Form::label('email', 'Correo Electrónico') }}</label>
                    <div>
                        {{ Form::email('email', $alumno->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'correo@ejemplo.com']) }}
                        {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                        <small class="form-hint">Correo electrónico válido del alumno.</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">{{ Form::label('estatus', 'Estatus') }}</label>
                    <div>
                        <select id="estatus-select" name="estatus" class="form-select{{ $errors->has('estatus') ? ' is-invalid' : '' }}">
                            <option value="" disabled {{ !$alumno->estatus ? 'selected' : '' }}>Selecciona un estatus</option>
                            <option value="Activo" {{ $alumno->estatus == 'Activo' ? 'selected' : '' }}>
                                <span class="text-success">✓ Activo</span>
                            </option>
                            
                        </select>
                        {!! $errors->first('estatus', '<div class="invalid-feedback">:message</div>') !!}
                        <small class="form-hint">Estado actual del alumno en el sistema.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información Académica -->
<div class="card mb-4">
    <div class="card-header">
        <h4 class="card-title">Información Académica</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">{{ Form::label('especialidad', 'Especialidad') }}</label>
                    <div>
                        <select name="especialidad" class="form-select{{ $errors->has('especialidad') ? ' is-invalid' : '' }}">
                            <option value="">Selecciona una especialidad</option>
                            @if(isset($especialidades))
                                @foreach($especialidades as $especialidad)
                                    <option value="{{ $especialidad->nombre }}" {{ old('especialidad', $alumno->especialidad) == $especialidad->nombre ? 'selected' : '' }}>
                                        {{ $especialidad->nombre }}
                                    </option>
                                @endforeach
                            @endif
                            <option value="No aplica" {{ old('especialidad', $alumno->especialidad) == 'No aplica' ? 'selected' : '' }}>
                                No aplica
                            </option>
                        </select>
                        {!! $errors->first('especialidad', '<div class="invalid-feedback">:message</div>') !!}
                        <small class="form-hint">Especialidad técnica del alumno.</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">{{ Form::label('semestre', 'Semestre') }}</label>
                    <div>
                        <select id="semestre-select" name="semestre" class="form-select{{ $errors->has('semestre') ? ' is-invalid' : '' }}">
                            <option value="" disabled {{ !$alumno->semestre ? 'selected' : '' }}>Selecciona un semestre</option>
                            <option value="1" {{ $alumno->semestre == '1' ? 'selected' : '' }}>Primer Semestre</option>
                            <option value="2" {{ $alumno->semestre == '2' ? 'selected' : '' }}>Segundo Semestre</option>
                            <option value="3" {{ $alumno->semestre == '3' ? 'selected' : '' }}>Tercer Semestre</option>
                            <option value="4" {{ $alumno->semestre == '4' ? 'selected' : '' }}>Cuarto Semestre</option>
                            <option value="5" {{ $alumno->semestre == '5' ? 'selected' : '' }}>Quinto Semestre</option>
                            <option value="6" {{ $alumno->semestre == '6' ? 'selected' : '' }}>Sexto Semestre</option>
                        </select>
                        {!! $errors->first('semestre', '<div class="invalid-feedback">:message</div>') !!}
                        <small class="form-hint">Semestre actual del alumno.</small>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group mb-3">
                    <label class="form-label">{{ Form::label('Grupo', 'Grupo') }}</label>
                    <div>
                        <select id="grupo-select" name="Grupo" class="form-select{{ $errors->has('Grupo') ? ' is-invalid' : '' }}">
                            <option value="" disabled selected>Primero selecciona un semestre</option>
                            <!-- Los grupos se agregarán dinámicamente aquí -->
                        </select>
                        {!! $errors->first('Grupo', '<div class="invalid-feedback">:message</div>') !!}
                        <small class="form-hint">El grupo se actualiza según el semestre seleccionado.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mensaje de éxito -->
<div id="mensajeExito" class="alert alert-success d-none">
    <div class="d-flex">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <polyline points="20,6 9,17 4,12"/>
            </svg>
        </div>
        <div>
            <h4 class="alert-title">¡Éxito!</h4>
            <div class="text-muted">Registro guardado correctamente.</div>
        </div>
    </div>
</div>

<!-- Botones de acción -->
<div class="form-footer">
    <div class="d-flex flex-column flex-sm-row gap-2">
        <a href="{{ route('alumnos.index') }}" class="btn btn-danger order-sm-1">
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
                <path d="M12 5l0 14"/>
                <path d="M5 12l14 0"/>
            </svg>
            Guardar alumno
        </button>
    </div>
</div>

<script>
// Lógica para actualizar grupos según semestre seleccionado
document.getElementById('semestre-select').addEventListener('change', function() {
    const semestre = this.value;
    const grupoSelect = document.getElementById('grupo-select');
    grupoSelect.innerHTML = '<option value="" disabled selected>Selecciona un grupo</option>';

    const grupos = {
        1: ['124', '128', '129a', '129b', '129c'],
        2: ['224', '228', '229a', '229b', '229c'],
        3: ['324', '328', '329a', '329b', '329c'],
        4: ['424', '428', '429a', '429b', '429c'],
        5: ['524', '528', '529a', '529b', '529c'],
        6: ['624', '628', '629a', '629b']
    };

    if (grupos[semestre]) {
        grupos[semestre].forEach(function(grupo) {
            const option = document.createElement('option');
            option.value = grupo;
            option.textContent = grupo;
            // Mantener selección si coincide con valor actual
            if (grupo === '{{ $alumno->Grupo }}') {
                option.selected = true;
            }
            grupoSelect.appendChild(option);
        });
        grupoSelect.disabled = false;
    } else {
        grupoSelect.disabled = true;
    }
});

// Cargar grupos al inicializar la página si ya hay un semestre seleccionado
document.addEventListener('DOMContentLoaded', function() {
    const semestreSelect = document.getElementById('semestre-select');
    if (semestreSelect.value) {
        semestreSelect.dispatchEvent(new Event('change'));
    }
});

// Validación del formulario antes del envío
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = ['numero_control', 'CURP', 'Nombre', 'email', 'especialidad', 'semestre', 'Grupo', 'estatus'];
            let isValid = true;
            
            requiredFields.forEach(function(fieldName) {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field && (!field.value || field.value.trim() === '')) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor, completa todos los campos requeridos.');
            }
        });
    }
});
</script>
