@extends('tablar::page')

@section('title', 'Editar Tarea')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-edit me-2"></i>
                    Editar Tarea
                </h2>
                <div class="text-muted mt-1">Modifica los detalles de la tarea</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('maestros.tareas.show', $tarea) }}" class="btn btn-outline-info">
                        <i class="ti ti-eye me-1"></i>
                        Ver Tarea
                    </a>
                    <a href="{{ route('maestros.tareas.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver a Tareas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form action="{{ route('maestros.tareas.update', $tarea) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información básica -->
                    <div class="card mb-4 ">
                        <div class="card-header ">
                            <h3 class="card-title">
                                <i class="ti ti-info-circle me-2 text-primary"></i>
                                Información Básica
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Título de la Tarea *</label>
                                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" 
                                               value="{{ old('titulo', $tarea->titulo) }}" placeholder="Ej: Ensayo sobre la Revolución Mexicana">
                                        @error('titulo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Evaluación *</label>
                                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                                            <option value="">Seleccionar tipo</option>
                                            <option value="tarea" {{ old('tipo', $tarea->tipo) === 'tarea' ? 'selected' : '' }}>Tarea</option>
                                            <option value="proyecto" {{ old('tipo', $tarea->tipo) === 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                                            <option value="examen" {{ old('tipo', $tarea->tipo) === 'examen' ? 'selected' : '' }}>Examen</option>
                                            <option value="practica" {{ old('tipo', $tarea->tipo) === 'practica' ? 'selected' : '' }}>Práctica</option>
                                            <option value="ensayo" {{ old('tipo', $tarea->tipo) === 'ensayo' ? 'selected' : '' }}>Ensayo</option>
                                        </select>
                                        @error('tipo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Descripción *</label>
                                <textarea name="descripcion" rows="4" 
                                          class="form-control @error('descripcion') is-invalid @enderror" 
                                          placeholder="Describe detalladamente qué deben hacer los estudiantes...">{{ old('descripcion', $tarea->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configuración académica -->
                    <div class="card mb-4 ">
                        <div class="card-header ">
                            <h3 class="card-title">
                                <i class="ti ti-school me-2 text-success"></i>
                                Configuración Académica
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Materia *</label>
                                        <select name="materia_id" class="form-select @error('materia_id') is-invalid @enderror">
                                            <option value="">Seleccionar materia</option>
                                            @foreach($materias as $materia)
                                                <option value="{{ $materia->id }}" {{ old('materia_id', $tarea->materia_id) == $materia->id ? 'selected' : '' }}>
                                                    {{ $materia->nombre }}
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
                                        <label class="form-label">Grupo *</label>
                                        <input type="text" name="grupo" class="form-control @error('grupo') is-invalid @enderror" 
                                               value="{{ old('grupo', $tarea->grupo) }}" placeholder="Ej: A, B, 1A, 2B">
                                        @error('grupo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Semestre *</label>
                                        <select name="semestre" class="form-select @error('semestre') is-invalid @enderror">
                                            <option value="">Seleccionar</option>
                                            @for($i = 1; $i <= 8; $i++)
                                                <option value="{{ $i }}" {{ old('semestre', $tarea->semestre) == $i ? 'selected' : '' }}>
                                                    {{ $i }}° Semestre
                                                </option>
                                            @endfor
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
                    <div class="card mb-4 ">
                        <div class="card-header ">
                            <h3 class="card-title">
                                <i class="ti ti-calendar-clock me-2"></i>
                                Fechas y Calificación
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha y Hora de Entrega *</label>
                                        <input type="datetime-local" name="fecha_entrega" 
                                               class="form-control @error('fecha_entrega') is-invalid @enderror" 
                                               value="{{ old('fecha_entrega', $tarea->fecha_entrega?->format('Y-m-d\TH:i')) }}">
                                        @error('fecha_entrega')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Puntos Totales *</label>
                                        <div class="input-group">
                                            <input type="number" name="puntos_totales" 
                                                   class="form-control @error('puntos_totales') is-invalid @enderror" 
                                                   value="{{ old('puntos_totales', $tarea->puntos_totales) }}" min="1" max="100">
                                            <span class="input-group-text">pts</span>
                                        </div>
                                        @error('puntos_totales')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Estado *</label>
                                        <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                            <option value="activa" {{ old('estado', $tarea->estado) === 'activa' ? 'selected' : '' }}>Activa</option>
                                            <option value="vencida" {{ old('estado', $tarea->estado) === 'vencida' ? 'selected' : '' }}>Vencida</option>
                                            <option value="cancelada" {{ old('estado', $tarea->estado) === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                        @error('estado')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instrucciones adicionales -->
                    <div class="card mb-4 ">
                        <div class="card-header ">
                            <h3 class="card-title">
                                <i class="ti ti-file-text me-2"></i>
                                Instrucciones y Archivos
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Instrucciones Adicionales</label>
                                <textarea name="instrucciones" rows="4" 
                                          class="form-control @error('instrucciones') is-invalid @enderror" 
                                          placeholder="Criterios de evaluación, formato requerido, recursos adicionales...">{{ old('instrucciones', $tarea->instrucciones) }}</textarea>
                                @error('instrucciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            @if($tarea->archivo_adjunto)
                                <div class="mb-3">
                                    <label class="form-label">Archivo Actual</label>
                                    <div class="card bg-secondary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i class="ti ti-file-text me-2"></i>
                                                    <span>{{ basename($tarea->archivo_adjunto) }}</span>
                                                </div>
                                                <a href="{{ Storage::url($tarea->archivo_adjunto) }}" 
                                                   class="btn btn-sm btn-outline-light" target="_blank">
                                                    <i class="ti ti-download me-1"></i>
                                                    Descargar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $tarea->archivo_adjunto ? 'Reemplazar Archivo' : 'Archivo Adjunto' }}
                                </label>
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
                                <a href="{{ route('maestros.tareas.show', $tarea) }}" class="btn btn-outline-light">
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
                <p>¿Estás seguro de que deseas eliminar esta tarea?</p>
                <p class="text-warning"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('maestros.tareas.destroy', $tarea) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar Tarea</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
