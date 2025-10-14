@extends('tablar::page')

@section('title', 'Crear Horario')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Control Escolar
                </div>
                <h2 class="page-title">
                    Crear Horario
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('horarios.index') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <line x1="12" y1="5" x2="19" y2="12"/>
                            <line x1="12" y1="19" x2="19" y2="12"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información del Horario</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('horarios.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Maestro <span class="text-danger">*</span></label>
                                        <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                                            <option value="">Seleccionar maestro...</option>
                                            @foreach($maestros as $maestro)
                                                <option value="{{ $maestro->id }}" {{ old('user_id') == $maestro->id ? 'selected' : '' }}>
                                                    {{ $maestro->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Materia <span class="text-danger">*</span></label>
                                        <select class="form-select @error('materia_id') is-invalid @enderror" name="materia_id" required>
                                            <option value="">Seleccionar materia...</option>
                                            @foreach($materias as $materia)
                                                <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                                    {{ $materia->materia }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('materia_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sala <span class="text-danger">*</span></label>
                                        <select class="form-select @error('sala_id') is-invalid @enderror" name="sala_id" required>
                                            <option value="">Seleccionar sala...</option>
                                            @foreach($salas as $sala)
                                                <option value="{{ $sala->id }}" {{ old('sala_id') == $sala->id ? 'selected' : '' }}>
                                                    {{ $sala->nombre }} ({{ $sala->tipo }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sala_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Día de la Semana <span class="text-danger">*</span></label>
                                        <select class="form-select @error('dia_semana') is-invalid @enderror" name="dia_semana" required>
                                            <option value="">Seleccionar día...</option>
                                            <option value="lunes" {{ old('dia_semana') == 'lunes' ? 'selected' : '' }}>Lunes</option>
                                            <option value="martes" {{ old('dia_semana') == 'martes' ? 'selected' : '' }}>Martes</option>
                                            <option value="miercoles" {{ old('dia_semana') == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                                            <option value="jueves" {{ old('dia_semana') == 'jueves' ? 'selected' : '' }}>Jueves</option>
                                            <option value="viernes" {{ old('dia_semana') == 'viernes' ? 'selected' : '' }}>Viernes</option>
                                            <option value="sabado" {{ old('dia_semana') == 'sabado' ? 'selected' : '' }}>Sábado</option>
                                        </select>
                                        @error('dia_semana')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hora de Inicio <span class="text-danger">*</span></label>
                                        <input type="time" 
                                               class="form-control @error('hora_inicio') is-invalid @enderror" 
                                               name="hora_inicio" 
                                               value="{{ old('hora_inicio') }}" 
                                               required>
                                        @error('hora_inicio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hora de Fin <span class="text-danger">*</span></label>
                                        <input type="time" 
                                               class="form-control @error('hora_fin') is-invalid @enderror" 
                                               name="hora_fin" 
                                               value="{{ old('hora_fin') }}" 
                                               required>
                                        @error('hora_fin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                        <input type="date" 
                                               class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                               name="fecha_inicio" 
                                               value="{{ old('fecha_inicio', date('Y-m-d')) }}" 
                                               required>
                                        @error('fecha_inicio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                        <input type="date" 
                                               class="form-control @error('fecha_fin') is-invalid @enderror" 
                                               name="fecha_fin" 
                                               value="{{ old('fecha_fin') }}" 
                                               required>
                                        @error('fecha_fin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                          name="observaciones" 
                                          rows="3" 
                                          placeholder="Notas adicionales sobre el horario...">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card-footer text-end">
                                <div class="d-flex">
                                    <a href="{{ route('horarios.index') }}" class="btn btn-link">Cancelar</a>
                                    <button type="submit" class="btn btn-primary ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                        </svg>
                                        Crear Horario
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validar que la hora de fin sea mayor que la de inicio
document.addEventListener('DOMContentLoaded', function() {
    const horaInicio = document.querySelector('input[name="hora_inicio"]');
    const horaFin = document.querySelector('input[name="hora_fin"]');
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
    const fechaFin = document.querySelector('input[name="fecha_fin"]');

    function validarHoras() {
        if (horaInicio.value && horaFin.value) {
            if (horaInicio.value >= horaFin.value) {
                horaFin.setCustomValidity('La hora de fin debe ser mayor que la hora de inicio');
            } else {
                horaFin.setCustomValidity('');
            }
        }
    }

    function validarFechas() {
        if (fechaInicio.value && fechaFin.value) {
            if (fechaInicio.value > fechaFin.value) {
                fechaFin.setCustomValidity('La fecha de fin debe ser mayor o igual a la fecha de inicio');
            } else {
                fechaFin.setCustomValidity('');
            }
        }
    }

    horaInicio.addEventListener('change', validarHoras);
    horaFin.addEventListener('change', validarHoras);
    fechaInicio.addEventListener('change', validarFechas);
    fechaFin.addEventListener('change', validarFechas);
});
</script>
@endsection
