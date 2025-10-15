@extends('tablar::page')

@section('title', 'Crear Nueva Tarea')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-plus me-2"></i>
                    Crear Nueva Tarea
                </h2>
                <div class="text-muted mt-1">Asigna una nueva tarea a tus estudiantes</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('maestros.tareas.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>
                    Volver a Tareas
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form action="{{ route('maestros.tareas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Información básica -->
                    <div class="card mb-4 bg-dark text-light">
                        <div class="card-header bg-gradient-primary">
                            <h3 class="card-title text-white">
                                <i class="ti ti-info-circle me-2"></i>
                                Información Básica
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Título de la Tarea *</label>
                                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" 
                                               value="{{ old('titulo') }}" placeholder="Ej: Ensayo sobre la Revolución Mexicana">
                                        @error('titulo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Tipo de Evaluación *</label>
                                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                                            <option value="">Seleccionar tipo</option>
                                            <option value="tarea" {{ old('tipo') === 'tarea' ? 'selected' : '' }}>Tarea</option>
                                            <option value="proyecto" {{ old('tipo') === 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                                            <option value="examen" {{ old('tipo') === 'examen' ? 'selected' : '' }}>Examen</option>
                                            <option value="practica" {{ old('tipo') === 'practica' ? 'selected' : '' }}>Práctica</option>
                                            <option value="ensayo" {{ old('tipo') === 'ensayo' ? 'selected' : '' }}>Ensayo</option>
                                        </select>
                                        @error('tipo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-light">Descripción *</label>
                                <textarea name="descripcion" rows="4" 
                                          class="form-control @error('descripcion') is-invalid @enderror" 
                                          placeholder="Describe detalladamente qué deben hacer los estudiantes...">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configuración académica -->
                    <div class="card mb-4 bg-dark text-light">
                        <div class="card-header bg-gradient-success">
                            <h3 class="card-title text-white">
                                <i class="ti ti-school me-2"></i>
                                Configuración Académica
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Materia *</label>
                                        <select name="materia_id" class="form-select @error('materia_id') is-invalid @enderror">
                                            <option value="">Seleccionar materia</option>
                                            @foreach($materias as $materia)
                                                <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                                    {{ $materia->materia }} - {{ $materia->semestre }}° Semestre ({{ $materia->especialidad }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('materia_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Grupo *</label>
                                        <select name="grupo" class="form-select @error('grupo') is-invalid @enderror">
                                            <option value="">Seleccionar grupo</option>
                                            @foreach($grupos as $grupo)
                                                <option value="{{ $grupo->nombre_completo }}" {{ old('grupo') == $grupo->nombre_completo ? 'selected' : '' }}>
                                                    {{ strtoupper($grupo->nombre_completo) }} ({{ $grupo->semestre }}° Sem)
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('grupo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Semestre *</label>
                                        <select name="semestre" class="form-select @error('semestre') is-invalid @enderror">
                                            <option value="">Seleccionar</option>
                                            @foreach($semestres as $sem)
                                                <option value="{{ $sem['id'] }}" {{ old('semestre') == $sem['id'] ? 'selected' : '' }}>
                                                    {{ $sem['nombre'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('semestre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fechas y calificación -->
                    <div class="card mb-4 bg-dark text-light">
                        <div class="card-header bg-gradient-warning">
                            <h3 class="card-title text-white">
                                <i class="ti ti-calendar-clock me-2"></i>
                                Fechas y Calificación
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Fecha y Hora de Entrega *</label>
                                        <input type="datetime-local" name="fecha_entrega" 
                                               class="form-control @error('fecha_entrega') is-invalid @enderror" 
                                               value="{{ old('fecha_entrega') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                                        @error('fecha_entrega')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Puntos Totales *</label>
                                        <div class="input-group">
                                            <input type="number" name="puntos_totales" 
                                                   class="form-control @error('puntos_totales') is-invalid @enderror" 
                                                   value="{{ old('puntos_totales', 100) }}" min="1" max="100">
                                            <span class="input-group-text">pts</span>
                                        </div>
                                        @error('puntos_totales')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instrucciones adicionales -->
                    <div class="card mb-4 bg-dark text-light">
                        <div class="card-header bg-gradient-info">
                            <h3 class="card-title text-white">
                                <i class="ti ti-file-text me-2"></i>
                                Instrucciones y Archivos
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-light">Instrucciones Adicionales</label>
                                <textarea name="instrucciones" rows="4" 
                                          class="form-control @error('instrucciones') is-invalid @enderror" 
                                          placeholder="Criterios de evaluación, formato requerido, recursos adicionales...">{{ old('instrucciones') }}</textarea>
                                @error('instrucciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-light">Archivo Adjunto</label>
                                <input type="file" name="archivo_adjunto" 
                                       class="form-control @error('archivo_adjunto') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx,.ppt,.pptx">
                                <div class="form-text text-muted">
                                    Formatos permitidos: PDF, DOC, DOCX, PPT, PPTX. Tamaño máximo: 10MB
                                </div>
                                @error('archivo_adjunto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="card bg-secondary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('maestros.tareas.index') }}" class="btn btn-outline-light">
                                    <i class="ti ti-x me-1"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    Crear Tarea
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const semestreSelect = document.querySelector('select[name="semestre"]');
    const grupoSelect = document.querySelector('select[name="grupo"]');
    const materiaSelect = document.querySelector('select[name="materia_id"]');
    
    // Filtrar grupos por semestre seleccionado
    semestreSelect.addEventListener('change', function() {
        const semestreSeleccionado = this.value;
        const grupoOptions = grupoSelect.querySelectorAll('option');
        
        grupoOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const grupoSemestre = option.textContent.match(/\((\d+)° Sem\)/);
            if (grupoSemestre && grupoSemestre[1] === semestreSeleccionado) {
                option.style.display = 'block';
            } else if (semestreSeleccionado === '') {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        
        // Reset grupo selection if current selection is now hidden
        if (grupoSelect.value && grupoSelect.querySelector(`option[value="${grupoSelect.value}"]`).style.display === 'none') {
            grupoSelect.value = '';
        }
    });
    
    // Filtrar materias por semestre seleccionado
    semestreSelect.addEventListener('change', function() {
        const semestreSeleccionado = this.value;
        const materiaOptions = materiaSelect.querySelectorAll('option');
        
        materiaOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const materiaSemestre = option.textContent.match(/(\d+)° Semestre/);
            if (materiaSemestre && materiaSemestre[1] === semestreSeleccionado) {
                option.style.display = 'block';
            } else if (semestreSeleccionado === '') {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        
        // Reset materia selection if current selection is now hidden
        if (materiaSelect.value && materiaSelect.querySelector(`option[value="${materiaSelect.value}"]`).style.display === 'none') {
            materiaSelect.value = '';
        }
    });
});
</script>
@endsection
