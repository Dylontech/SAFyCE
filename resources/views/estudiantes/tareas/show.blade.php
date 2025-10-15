@extends('tablar::page')

@section('title', 'Detalles de Tarea')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Tarea de {{ $tarea->materia->materia }}
                </div>
                <h2 class="page-title text-primary">
                    <i class="ti ti-checklist me-2"></i>
                    {{ $tarea->titulo }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('estudiantes.tareas') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver a Tareas
                    </a>
                    @if($tarea->archivo_adjunto)
                        <a href="{{ asset('storage/' . $tarea->archivo_adjunto) }}" 
                           class="btn btn-primary" 
                           target="_blank">
                            <i class="ti ti-download me-1"></i>
                            Descargar Archivo
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <!-- Información principal de la tarea -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Descripción de la Tarea</h3>
                        <div class="card-actions">
                            <span class="badge bg-{{ $tarea->tipo === 'examen' ? 'danger' : ($tarea->tipo === 'proyecto' ? 'info' : 'primary') }}">
                                {{ ucfirst($tarea->tipo) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h4>Descripción:</h4>
                            <p class="text-muted">{{ $tarea->descripcion }}</p>
                        </div>

                        @if($tarea->instrucciones)
                            <div class="mb-4">
                                <h4>Instrucciones:</h4>
                                <div class="bg-light p-3 rounded">
                                    {!! nl2br(e($tarea->instrucciones)) !!}
                                </div>
                            </div>
                        @endif

                        @if($tarea->archivo_adjunto)
                            <div class="mb-4">
                                <h4>Archivo Adjunto:</h4>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-paperclip me-2 text-primary"></i>
                                    <a href="{{ asset('storage/' . $tarea->archivo_adjunto) }}" 
                                       target="_blank" 
                                       class="text-decoration-none">
                                        {{ basename($tarea->archivo_adjunto) }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Estado de entrega -->
                @if($calificacion)
                    <div class="card mt-4">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title text-white">
                                <i class="ti ti-check me-2"></i>
                                Tarea Calificada
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Calificación: 
                                        <span class="badge bg-success fs-5">{{ $calificacion->calificacion }}/{{ $tarea->puntos_totales }}</span>
                                    </h4>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted">
                                        <strong>Fecha de calificación:</strong> 
                                        {{ $calificacion->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            @if($calificacion->comentarios)
                                <div class="mt-3">
                                    <h5>Comentarios del maestro:</h5>
                                    <div class="bg-light p-3 rounded">
                                        {{ $calificacion->comentarios }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card mt-4">
                        <div class="card-header bg-{{ $tarea->estaVencida() ? 'danger' : 'warning' }} text-white">
                            <h3 class="card-title text-white">
                                <i class="ti ti-clock me-2"></i>
                                {{ $tarea->estaVencida() ? 'Tarea Vencida' : 'Pendiente de Calificación' }}
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($tarea->estaVencida())
                                <div class="alert alert-danger">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    Esta tarea venció el {{ $tarea->fecha_entrega->format('d/m/Y a las H:i') }}.
                                    Contacta a tu maestro si necesitas una extensión.
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Esta tarea aún no ha sido calificada. 
                                    Tienes hasta el {{ $tarea->fecha_entrega->format('d/m/Y a las H:i') }} para completarla.
                                </div>
                                <p><strong>Tiempo restante:</strong> {{ $tarea->diasRestantes() }} días</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Panel lateral con información -->
            <div class="col-lg-4">
                <!-- Información de la tarea -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información de la Tarea</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Materia:</label>
                            <div class="fw-bold">{{ $tarea->materia->materia }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Maestro:</label>
                            <div class="fw-bold">{{ $tarea->maestro->name }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Grupo:</label>
                            <div class="fw-bold">{{ $tarea->grupo }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Semestre:</label>
                            <div class="fw-bold">{{ $tarea->semestre }}°</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Tipo:</label>
                            <span class="badge bg-primary">{{ ucfirst($tarea->tipo) }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Estado:</label>
                            @if($tarea->estaVencida())
                                <span class="badge bg-danger">Vencida</span>
                            @elseif($tarea->estado === 'activa')
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($tarea->estado) }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Puntos totales:</label>
                            <div class="fw-bold text-primary">{{ $tarea->puntos_totales }} puntos</div>
                        </div>
                    </div>
                </div>

                <!-- Fechas importantes -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Fechas Importantes</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Fecha de asignación:</label>
                            <div class="fw-bold">
                                <i class="ti ti-calendar me-2"></i>
                                {{ $tarea->fecha_asignacion ? $tarea->fecha_asignacion->format('d/m/Y H:i') : $tarea->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Fecha de entrega:</label>
                            <div class="fw-bold {{ $tarea->estaVencida() ? 'text-danger' : ($tarea->diasRestantes() <= 3 ? 'text-warning' : 'text-success') }}">
                                <i class="ti ti-clock me-2"></i>
                                {{ $tarea->fecha_entrega->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        @if(!$tarea->estaVencida())
                            <div class="mb-3">
                                <label class="form-label text-muted">Tiempo restante:</label>
                                <div class="fw-bold">
                                    @if($tarea->diasRestantes() > 0)
                                        <i class="ti ti-hourglass me-2"></i>
                                        {{ $tarea->diasRestantes() }} días
                                    @else
                                        <i class="ti ti-alert-triangle me-2 text-warning"></i>
                                        <span class="text-warning">Menos de 1 día</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progreso/Calificación -->
                @if($calificacion)
                    <div class="card mt-4">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title text-white">Mi Calificación</h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="display-4 fw-bold text-success">
                                {{ $calificacion->calificacion }}
                            </div>
                            <div class="text-muted">de {{ $tarea->puntos_totales }} puntos</div>
                            <div class="progress mt-3">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ ($calificacion->calificacion / $tarea->puntos_totales) * 100 }}%">
                                </div>
                            </div>
                            <div class="text-muted mt-2">
                                {{ number_format(($calificacion->calificacion / $tarea->puntos_totales) * 100, 1) }}%
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@section('css')
<style>
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .card-header.bg-success,
    .card-header.bg-warning,
    .card-header.bg-danger {
        border: none;
    }
    .progress {
        height: 8px;
    }
</style>
@endsection
@endsection
