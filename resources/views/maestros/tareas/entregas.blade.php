@extends('tablar::page')

@section('title', 'Entregas de Tarea')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-file-check me-2"></i>
                    Entregas de Tarea
                </h2>
                <div class="text-muted mt-1">
                    {{ $tarea->titulo }} - {{ $tarea->materia->materia }}
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('maestros.tareas.show', $tarea) }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver a Tarea
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Información de la tarea -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Información de la Tarea</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Grupo:</strong> {{ $tarea->grupo }}
                    </div>
                    <div class="col-md-3">
                        <strong>Semestre:</strong> {{ $tarea->semestre }}°
                    </div>
                    <div class="col-md-3">
                        <strong>Fecha límite:</strong> {{ $tarea->fecha_entrega->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-3">
                        <strong>Puntos totales:</strong> {{ $tarea->puntos_totales }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de entregas -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $entregas->where('estado_entrega', 'entregada')->count() }}</h3>
                        <p class="card-text">Entregadas a Tiempo</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-warning text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $entregas->where('estado_entrega', 'tarde')->count() }}</h3>
                        <p class="card-text">Entregadas Tarde</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $entregas->where('estado_entrega', 'calificada')->count() }}</h3>
                        <p class="card-text">Calificadas</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h3 class="card-title">{{ $entregas->total() }}</h3>
                        <p class="card-text">Total Entregas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de entregas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Entregas</h3>
            </div>
            <div class="card-body p-0">
                @if($entregas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Alumno</th>
                                    <th>Fecha de Entrega</th>
                                    <th>Estado</th>
                                    <th>Calificación</th>
                                    <th>Archivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entregas as $entrega)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-3 bg-primary-lt">
                                                    {{ substr($entrega->alumno->nombre, 0, 1) }}{{ substr($entrega->alumno->apellido_paterno, 0, 1) }}
                                                </span>
                                                <div>
                                                    <div class="font-weight-medium">
                                                        {{ $entrega->alumno->nombre }} {{ $entrega->alumno->apellido_paterno }}
                                                    </div>
                                                    <div class="text-muted">{{ $entrega->alumno->numero_control }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                {{ $entrega->fecha_entrega_alumno->format('d/m/Y') }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ $entrega->fecha_entrega_alumno->format('H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($entrega->estado_entrega === 'entregada')
                                                <span class="badge bg-success">
                                                    <i class="ti ti-check me-1"></i>
                                                    A tiempo
                                                </span>
                                            @elseif($entrega->estado_entrega === 'tarde')
                                                <span class="badge bg-warning">
                                                    <i class="ti ti-clock-x me-1"></i>
                                                    Tardía
                                                </span>
                                            @elseif($entrega->estado_entrega === 'calificada')
                                                <span class="badge bg-info">
                                                    <i class="ti ti-star me-1"></i>
                                                    Calificada
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($entrega->calificacion)
                                                <div class="font-weight-medium">
                                                    {{ $entrega->calificacion }}/{{ $tarea->puntos_totales }}
                                                </div>
                                                @if($entrega->fecha_evaluacion)
                                                    <div class="small text-muted">
                                                        {{ $entrega->fecha_evaluacion->format('d/m/Y') }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">Sin calificar</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small text-muted">
                                                {{ basename($entrega->archivo_entrega) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <a href="{{ route('maestros.tareas.descargar-entrega', [$tarea, $entrega]) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Descargar entrega">
                                                    <i class="ti ti-download"></i>
                                                </a>
                                                @if($entrega->estado_entrega !== 'calificada')
                                                    <button type="button" 
                                                            class="btn btn-sm btn-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#calificarModal{{ $entrega->id }}"
                                                            title="Calificar">
                                                        <i class="ti ti-star"></i>
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-secondary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#verCalificacionModal{{ $entrega->id }}"
                                                            title="Ver calificación">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty">
                        <div class="empty-img">
                            <img src="{{ asset('build/icons/no-data.svg') }}" height="128" alt="">
                        </div>
                        <p class="empty-title">No hay entregas</p>
                        <p class="empty-subtitle text-muted">
                            Aún no hay estudiantes que hayan entregado esta tarea.
                        </p>
                    </div>
                @endif
            </div>
            
            @if($entregas->hasPages())
                <div class="card-footer">
                    {{ $entregas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modales para calificar -->
@foreach($entregas as $entrega)
    @if($entrega->estado_entrega !== 'calificada')
        <!-- Modal Calificar -->
        <div class="modal modal-blur fade" id="calificarModal{{ $entrega->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Calificar Entrega</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('maestros.tareas.calificar-entrega', [$tarea, $entrega]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Información del Alumno</h6>
                                    <p><strong>Nombre:</strong> {{ $entrega->alumno->nombre }} {{ $entrega->alumno->apellido_paterno }}</p>
                                    <p><strong>Número de Control:</strong> {{ $entrega->alumno->numero_control }}</p>
                                    <p><strong>Entregada:</strong> {{ $entrega->fecha_entrega_alumno->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Archivo de Entrega</h6>
                                    <p>{{ basename($entrega->archivo_entrega) }}</p>
                                    <a href="{{ route('maestros.tareas.descargar-entrega', [$tarea, $entrega]) }}" 
                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="ti ti-download me-1"></i>
                                        Descargar
                                    </a>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Calificación * (máximo {{ $tarea->puntos_totales }} puntos)</label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   name="calificacion" 
                                                   class="form-control" 
                                                   min="0" 
                                                   max="{{ $tarea->puntos_totales }}" 
                                                   step="0.1" 
                                                   required>
                                            <span class="input-group-text">/ {{ $tarea->puntos_totales }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Estado de Entrega</label>
                                        <div class="form-control-plaintext">
                                            @if($entrega->estado_entrega === 'entregada')
                                                <span class="badge bg-success">A tiempo</span>
                                            @elseif($entrega->estado_entrega === 'tarde')
                                                <span class="badge bg-warning">Tardía</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Comentarios (opcional)</label>
                                <textarea name="comentarios" 
                                          rows="3" 
                                          class="form-control" 
                                          placeholder="Retroalimentación para el estudiante..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-star me-1"></i>
                                Calificar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Modal Ver Calificación -->
        <div class="modal modal-blur fade" id="verCalificacionModal{{ $entrega->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Calificación de {{ $entrega->alumno->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Información del Alumno</h6>
                                <p><strong>Nombre:</strong> {{ $entrega->alumno->nombre }} {{ $entrega->alumno->apellido_paterno }}</p>
                                <p><strong>Número de Control:</strong> {{ $entrega->alumno->numero_control }}</p>
                                <p><strong>Entregada:</strong> {{ $entrega->fecha_entrega_alumno->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Calificación</h6>
                                <p><strong>Puntos:</strong> {{ $entrega->calificacion }} / {{ $tarea->puntos_totales }}</p>
                                <p><strong>Fecha de calificación:</strong> {{ $entrega->fecha_evaluacion->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($entrega->comentarios)
                            <hr>
                            <h6>Comentarios</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $entrega->comentarios }}
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <a href="{{ route('maestros.tareas.descargar-entrega', [$tarea, $entrega]) }}" 
                           class="btn btn-primary" target="_blank">
                            <i class="ti ti-download me-1"></i>
                            Descargar Entrega
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
