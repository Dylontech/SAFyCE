@extends('tablar::page')

@section('title', 'Update Alumno')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Actualizar información
                    </div>
                    <h2 class="page-title">
                        {{ __('Alumno ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('alumnos.index') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0"/>
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                            </svg>
                            <span class="d-none d-sm-inline">Inicio</span>
                            <span class="d-sm-none">Lista</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if(config('tablar','display_alert'))
                @include('tablar::common.alert')
            @endif
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Información del Alumno</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST"
                                  action="{{ route('alumnos.update', $alumno->id) }}" id="ajaxForm" role="form"
                                  enctype="multipart/form-data">
                                {{ method_field('PATCH') }}
                                @csrf
                                
                                <!-- Campos del formulario -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="numero_control"><strong>Número de Control:</strong></label>
                                            <input type="text" name="numero_control" id="numero_control" 
                                                   class="form-control" value="{{ old('numero_control', $alumno->numero_control) }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="CURP"><strong>CURP:</strong></label>
                                            <input type="text" name="CURP" id="CURP" 
                                                   class="form-control" value="{{ old('CURP', $alumno->CURP) }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="especialidad"><strong>Especialidad:</strong></label>
                                            <input type="text" name="especialidad" id="especialidad" 
                                                   class="form-control" value="{{ old('especialidad', $alumno->especialidad) }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="semestre"><strong>Semestre:</strong></label>
                                            <input type="text" name="semestre" id="semestre" 
                                                   class="form-control" value="{{ old('semestre', $alumno->semestre) }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="Grupo"><strong>Grupo:</strong></label>
                                            <input type="text" name="Grupo" id="Grupo" 
                                                   class="form-control" value="{{ old('Grupo', $alumno->Grupo) }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="Nombre"><strong>Nombre:</strong></label>
                                            <input type="text" name="Nombre" id="Nombre" 
                                                   class="form-control" value="{{ old('Nombre', $alumno->Nombre) }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="email"><strong>Email:</strong></label>
                                            <input type="email" name="email" id="email" 
                                                   class="form-control" value="{{ old('email', $alumno->email) }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="estatus"><strong>Estatus:</strong></label>
                                            <select name="estatus" id="estatus" class="form-control" required>
                                                <option value="Activo" 
                                                    {{ old('estatus', $alumno->estatus) == 'Activo' ? 'selected' : '' }}
                                                    data-color="bg-success text-white">Activo</option>
                                                <option value="Inactivo" 
                                                    {{ old('estatus', $alumno->estatus) == 'Inactivo' ? 'selected' : '' }}
                                                    data-color="bg-danger text-white">Inactivo</option>
                                                <option value="Egresado" 
                                                    {{ old('estatus', $alumno->estatus) == 'Egresado' ? 'selected' : '' }}
                                                    data-color="bg-info text-white">Egresado</option>
                                                <option value="Baja" 
                                                    {{ old('estatus', $alumno->estatus) == 'Baja' ? 'selected' : '' }}
                                                    data-color="bg-warning text-dark">Baja</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones del formulario -->
                                <div class="form-footer">
                                    <button type="submit" class="btn btn-primary">Actualizar Alumno</button>
                                    <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const estatusSelect = document.getElementById('estatus');
    
    // Función para actualizar el estilo del select según el estatus seleccionado
    function updateSelectStyle() {
        const selectedOption = estatusSelect.options[estatusSelect.selectedIndex];
        const colorClass = selectedOption.getAttribute('data-color');
        
        // Remover clases de color anteriores
        estatusSelect.classList.remove('bg-success', 'text-white', 'bg-danger', 'text-white', 
                                      'bg-info', 'text-white', 'bg-warning', 'text-dark');
        
        // Agregar nuevas clases de color
        if (colorClass) {
            const classes = colorClass.split(' ');
            classes.forEach(className => {
                estatusSelect.classList.add(className);
            });
        }
    }
    
    // Aplicar estilo inicial
    updateSelectStyle();
    
    // Actualizar estilo cuando cambie la selección
    estatusSelect.addEventListener('change', updateSelectStyle);
});
</script>
@endpush


