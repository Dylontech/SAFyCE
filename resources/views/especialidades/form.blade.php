
<div class="form-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group mb-3">
                {{ Form::label('nombre', 'Nombre de la Especialidad', ['class' => 'form-label']) }}
                {{ Form::text('nombre', $especialidade->nombre ?? old('nombre'), [
                    'class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 
                    'placeholder' => 'Ingrese el nombre de la especialidad',
                    'required' => true
                ]) }}
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="form-group mb-3">
                {{ Form::label('descripcion', 'Descripción', ['class' => 'form-label']) }}
                {{ Form::textarea('descripcion', $especialidade->descripcion ?? old('descripcion'), [
                    'class' => 'form-control' . ($errors->has('descripcion') ? ' is-invalid' : ''), 
                    'placeholder' => 'Ingrese una descripción de la especialidad (opcional)',
                    'rows' => 4
                ]) }}
                @if($errors->has('descripcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('descripcion') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="{{ route('especialidades.index') }}" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">
                {{ isset($especialidade) && $especialidade->id ? 'Actualizar' : 'Crear' }} Especialidad
            </button>
        </div>
    </div>
</div>
