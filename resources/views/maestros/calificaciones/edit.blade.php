@extends('tablar::page')

@section('title', 'Editar Calificación')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-edit me-2"></i>
                    Editar Calificación
                </h2>
                <div class="text-muted mt-1">Modifica los detalles de la calificación</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('maestros.calificaciones.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>
                    Volver a Calificaciones
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form action="{{ route('maestros.calificaciones.update', $calificacion) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información del estudiante -->
                    <div class="card mb-4 ">
                        <div class="card-header ">
                            <h3 class="card-title">
                                <i class="ti ti-user me-2"></i>
                                Información del Estudiante
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Estudiante *</label>
                                        <select name="alumno_id" class="form-select @error('alumno_id') is-invalid @enderror" required>
                                            <option value="">Seleccionar estudiante</option>
                                            @foreach($alumnos as $alumno)
                                                <option value="{{ $alumno->id }}" {{ old('alumno_id', $calificacion->alumno_id) == $alumno->id ? 'selected' : '' }}>
                                                    {{ $alumno->nombres }} {{ $alumno->apellidos }} - {{ $alumno->matricula }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('alumno_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Materia *</label>
                                        <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                                            <option value="">Seleccionar materia</option>
                                            @foreach($materias as $materia)
                                                <option value="{{ $materia->id }}" {{ old('materia_id', $calificacion->materia_id) == $materia->id ? 'selected' : '' }}>
                                                    {{ $materia->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('materia_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de la evaluación -->
                    <div class="card mb-4 ">
                        <div class="card-header ">
                            <h3 class="card-title">
                                <i class="ti ti-clipboard me-2"></i>
                                Información de la Evaluación
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Evaluación *</label>
                                        <select name="tipo_evaluacion" class="form-select @error('tipo_evaluacion') is-invalid @enderror" required>
                                            <option value="">Seleccionar tipo</option>
                                            <option value="tarea" {{ old('tipo_evaluacion', $calificacion->tipo_evaluacion) === 'tarea' ? 'selected' : '' }}>Tarea</option>
                                            <option value="examen_parcial" {{ old('tipo_evaluacion', $calificacion->tipo_evaluacion) === 'examen_parcial' ? 'selected' : '' }}>Examen Parcial</option>
                                            <option value="examen_final" {{ old('tipo_evaluacion', $calificacion->tipo_evaluacion) === 'examen_final' ? 'selected' : '' }}>Examen Final</option>
                                            <option value="proyecto" {{ old('tipo_evaluacion', $calificacion->tipo_evaluacion) === 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                                            <option value="participacion" {{ old('tipo_evaluacion', $calificacion->tipo_evaluacion) === 'participacion' ? 'selected' : '' }}>Participación</option>
                                            <option value="practica" {{ old('tipo_evaluacion', $calificacion->tipo_evaluacion) === 'practica' ? 'selected' : '' }}>Práctica</option>
                                        </select>
                                        @error('tipo_evaluacion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tarea Relacionada (Opcional)</label>
                                        <select name="tarea_id" id="tarea_id" class="form-select @error('tarea_id') is-invalid @enderror">
                                            <option value="">Sin tarea específica</option>
                                            @foreach($tareas as $tarea)
                                                <option value="{{ $tarea->id }}" 
                                                        data-materia="{{ $tarea->materia_id }}"
                                                        data-puntos="{{ $tarea->puntos_totales }}"
                                                        {{ old('tarea_id', $calificacion->tarea_id) == $tarea->id ? 'selected' : '' }}>
                                                    {{ $tarea->titulo }} ({{ $tarea->puntos_totales }} pts)
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tarea_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Periodo Escolar *</label>
                                        <input type="text" name="periodo_escolar" class="form-control @error('periodo_escolar') is-invalid @enderror" 
                                               value="{{ old('periodo_escolar', $calificacion->periodo_escolar) }}" placeholder="Ej: 2024-2025" required>
                                        @error('periodo_escolar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Parcial</label>
                                        <select name="parcial" class="form-select @error('parcial') is-invalid @enderror">
                                            <option value="">Sin parcial específico</option>
                                            <option value="1" {{ old('parcial', $calificacion->parcial) == '1' ? 'selected' : '' }}>Primer Parcial</option>
                                            <option value="2" {{ old('parcial', $calificacion->parcial) == '2' ? 'selected' : '' }}>Segundo Parcial</option>
                                            <option value="3" {{ old('parcial', $calificacion->parcial) == '3' ? 'selected' : '' }}>Tercer Parcial</option>
                                        </select>
                                        @error('parcial')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Evaluación *</label>
                                        <input type="date" name="fecha_evaluacion" class="form-control @error('fecha_evaluacion') is-invalid @enderror" 
                                               value="{{ old('fecha_evaluacion', $calificacion->fecha_evaluacion?->format('Y-m-d')) }}" required>
                                        @error('fecha_evaluacion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calificación y puntos -->
                    <div class="card mb-4 ">
                        <div class="card-header ">
                            <h3 class="card-title">
                                <i class="ti ti-calculator me-2"></i>
                                Calificación y Puntos
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Calificación *</label>
                                        <div class="input-group">
                                            <input type="number" name="calificacion" id="calificacion" 
                                                   class="form-control @error('calificacion') is-invalid @enderror" 
                                                   value="{{ old('calificacion', $calificacion->calificacion) }}" min="0" max="100" step="0.01" required>
                                            <span class="input-group-text">/ 100</span>
                                        </div>
                                        @error('calificacion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Puntos Obtenidos</label>
                                        <input type="number" name="puntos_obtenidos" id="puntos_obtenidos" 
                                               class="form-control @error('puntos_obtenidos') is-invalid @enderror" 
                                               value="{{ old('puntos_obtenidos', $calificacion->puntos_obtenidos) }}" min="0">
                                        @error('puntos_obtenidos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Puntos Totales</label>
                                        <input type="number" name="puntos_totales" id="puntos_totales" 
                                               class="form-control @error('puntos_totales') is-invalid @enderror" 
                                               value="{{ old('puntos_totales', $calificacion->puntos_totales) }}" min="1">
                                        @error('puntos_totales')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comentarios -->
                    <div class="card mb-4 ">
                        <div class="card-header ">
                            <h3 class="card-title">
                                <i class="ti ti-message-circle me-2"></i>
                                Comentarios Adicionales
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Comentarios</label>
                                <textarea name="comentarios" rows="4" 
                                          class="form-control @error('comentarios') is-invalid @enderror" 
                                          placeholder="Observaciones, fortalezas, áreas de mejora...">{{ old('comentarios', $calificacion->comentarios) }}</textarea>
                                @error('comentarios')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="card bg-secondary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('maestros.calificaciones.index') }}" class="btn btn-outline-light">
                                    <i class="ti ti-x me-1"></i>
                                    Cancelar
                                </a>
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>
                                        Guardar Cambios
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="ti ti-trash me-1"></i>
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar esta calificación?</p>
                <p class="text-warning"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('maestros.calificaciones.destroy', $calificacion) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar Calificación</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const materiaSelect = document.getElementById('materia_id');
    const tareaSelect = document.getElementById('tarea_id');
    const calificacionInput = document.getElementById('calificacion');
    const puntosObtenidosInput = document.getElementById('puntos_obtenidos');
    const puntosTotalesInput = document.getElementById('puntos_totales');

    // Filtrar tareas por materia
    materiaSelect.addEventListener('change', function() {
        const materiaId = this.value;
        const tareaOptions = tareaSelect.querySelectorAll('option[data-materia]');
        
        // Mostrar/ocultar opciones de tarea según la materia
        tareaOptions.forEach(option => {
            if (!materiaId || option.dataset.materia === materiaId) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
                // Si la opción oculta estaba seleccionada, deseleccionar
                if (option.selected) {
                    tareaSelect.value = '';
                }
            }
        });
    });

    // Actualizar puntos cuando se selecciona una tarea
    tareaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.dataset.puntos) {
            puntosTotalesInput.value = selectedOption.dataset.puntos;
            calculatePoints();
        }
    });

    // Calcular puntos obtenidos basado en calificación
    calificacionInput.addEventListener('input', calculatePoints);
    puntosTotalesInput.addEventListener('input', calculatePoints);

    function calculatePoints() {
        const calificacion = parseFloat(calificacionInput.value) || 0;
        const puntosTotales = parseFloat(puntosTotalesInput.value) || 0;
        
        if (calificacion > 0 && puntosTotales > 0) {
            const puntosObtenidos = Math.round((calificacion / 100) * puntosTotales);
            puntosObtenidosInput.value = puntosObtenidos;
        }
    }

    // Trigger inicial para filtrar tareas
    if (materiaSelect.value) {
        materiaSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
