<div class="row">
    <div class="col-12">
        <div class="form-group mb-3">
            <label class="form-label">{{ Form::label('materia', 'Nombre de la materia') }}</label>
            <div>
                {{ Form::text('materia', $materia->materia, ['class' => 'form-control' .
                ($errors->has('materia') ? ' is-invalid' : ''), 'placeholder' => 'Ingrese el nombre de la materia']) }}
                {!! $errors->first('materia', '<div class="invalid-feedback">:message</div>') !!}
                <small class="form-hint">Escriba el nombre completo de la materia.</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6">
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
                    '6' => 'Semestre 6'
                ], $materia->semestre, ['class' => 'form-select' . ($errors->has('semestre') ? ' is-invalid' : '')]) }}
                {!! $errors->first('semestre', '<div class="invalid-feedback">:message</div>') !!}
                <small class="form-hint">Seleccione el semestre correspondiente.</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ Form::label('especialidad', 'Especialidad') }}</label>
            <div>
                <select name="especialidad" class="form-select{{ $errors->has('especialidad') ? ' is-invalid' : '' }}">
                    <option value="">Seleccione una especialidad</option>
                    @if(isset($especialidades))
                        @foreach($especialidades as $especialidad)
                            <option value="{{ $especialidad->nombre }}" {{ old('especialidad', $materia->especialidad) == $especialidad->nombre ? 'selected' : '' }}>
                                {{ $especialidad->nombre }}
                            </option>
                        @endforeach
                    @endif
                </select>
                {!! $errors->first('especialidad', '<div class="invalid-feedback">:message</div>') !!}
                <small class="form-hint">Seleccione la especialidad correspondiente.</small>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <div class="d-flex flex-column flex-sm-row gap-2">
        <a href="#" class="btn btn-danger order-sm-1" onclick="history.back(); return false;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
            Cancelar
        </a>
        <button type="submit" class="btn btn-primary flex-fill order-sm-2 ajax-submit">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <polyline points="20,6 9,17 4,12"/>
            </svg>
            Guardar materia
        </button>
    </div>
</div>
