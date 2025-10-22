@extends('tablar::page')

@section('title', 'Detalle del Horario')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Portal Estudiantil
                </div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                        <line x1="16" y1="3" x2="16" y2="7"/>
                        <line x1="8" y1="3" x2="8" y2="7"/>
                        <line x1="4" y1="11" x2="20" y2="11"/>
                    </svg>
                    Detalle del Horario
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    @if($horario->sala)
                        <a href="{{ route('estudiantes.salas.show', $horario->sala) }}" class="btn btn-outline-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 21h18"/>
                                <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                <path d="M9 9h6"/>
                                <path d="M9 12h6"/>
                                <path d="M9 15h6"/>
                            </svg>
                            Ver Sala
                        </a>
                    @endif
                    <a href="{{ route('estudiantes.horarios') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <line x1="12" y1="5" x2="19" y2="12"/>
                            <line x1="12" y1="19" x2="19" y2="12"/>
                        </svg>
                        Volver a Horarios
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
            <!-- Información Principal del Horario -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                                        <line x1="16" y1="3" x2="16" y2="7"/>
                                        <line x1="8" y1="3" x2="8" y2="7"/>
                                        <line x1="4" y1="11" x2="20" y2="11"/>
                                    </svg>
                                    Información de la Clase
                                </h3>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-{{ $horario->estaActivo() ? 'success' : 'secondary' }} fs-6 px-3 py-2">
                                    {{ $horario->estaActivo() ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Fila 1: Materia y Horario -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">MATERIA</label>
                                    @if($horario->materia)
                                        <div class="h2 mb-1 text-primary">{{ $horario->materia->materia }}</div>
                                        @if($horario->materia->codigo)
                                            <div class="text-muted">
                                                <span class="badge bg-info me-2">{{ $horario->materia->codigo }}</span>
                                                @if($horario->materia->semestre)
                                                    <span class="badge bg-secondary">{{ $horario->materia->semestre }}° Semestre</span>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="h2 mb-1 text-muted">No asignada</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">HORARIO</label>
                                    <div class="h3 mb-2">
                                        <span class="badge bg-primary fs-5 px-3 py-2">{{ ucfirst($horario->dia_semana) }}</span>
                                    </div>
                                    <div class="h4 text-dark mb-1">
                                        {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                                    </div>
                                    @php
                                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                        $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                        $duracion = $fin->diffInMinutes($inicio);
                                    @endphp
                                    <small class="text-muted">Duración: {{ floor($duracion / 60) }}h {{ $duracion % 60 }}min</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Fila 2: Maestro y Sala -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">PROFESOR</label>
                                    @if($horario->maestro)
                                        <div class="card bg-light border-0">
                                            <div class="card-body py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-lg me-3 bg-primary text-white">
                                                        {{ substr($horario->maestro->name, 0, 2) }}
                                                    </span>
                                                    <div>
                                                        <div class="h4 mb-1">{{ $horario->maestro->name }}</div>
                                                        <div class="text-muted">{{ $horario->maestro->email }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card bg-light border-0">
                                            <div class="card-body py-3 text-center text-muted">
                                                <div class="h4 mb-0">Profesor no asignado</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">AULA / SALA</label>
                                    @if($horario->sala)
                                        <div class="card border-info">
                                            <div class="card-body py-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <div class="h4 mb-1">{{ $horario->sala->nombre }}</div>
                                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                                            <span class="badge bg-info">{{ ucfirst($horario->sala->tipo) }}</span>
                                                            @if($horario->sala->codigo)
                                                                <span class="badge bg-secondary">{{ $horario->sala->codigo }}</span>
                                                            @endif
                                                            @if($horario->sala->capacidad)
                                                                <span class="badge bg-success">{{ $horario->sala->capacidad }} personas</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('estudiantes.salas.show', $horario->sala) }}" class="btn btn-outline-info btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                                    </svg>
                                                    Ver Detalles del Aula
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card bg-light border-0">
                                            <div class="card-body py-3 text-center text-muted">
                                                <div class="h4 mb-0">Aula no asignada</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Período Académico -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold">PERÍODO ACADÉMICO</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center py-3">
                                                <div class="text-white-50 small">Fecha de Inicio</div>
                                                <div class="h4 mb-0">{{ \Carbon\Carbon::parse($horario->fecha_inicio)->format('d/m/Y') }}</div>
                                                <small class="text-white-50">{{ \Carbon\Carbon::parse($horario->fecha_inicio)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center py-3">
                                                <div class="text-white-50 small">Fecha de Fin</div>
                                                <div class="h4 mb-0">{{ \Carbon\Carbon::parse($horario->fecha_fin)->format('d/m/Y') }}</div>
                                                <small class="text-white-50">{{ \Carbon\Carbon::parse($horario->fecha_fin)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center py-3">
                                                <div class="text-white-50 small">Duración Total</div>
                                                @php
                                                    $duracionTotal = \Carbon\Carbon::parse($horario->fecha_inicio)->diffInDays(\Carbon\Carbon::parse($horario->fecha_fin));
                                                @endphp
                                                <div class="h4 mb-0">{{ $duracionTotal }} días</div>
                                                <small class="text-white-50">{{ round($duracionTotal / 7, 1) }} semanas aprox.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($horario->observaciones)
                        <hr class="my-4">
                        
                        <!-- Observaciones -->
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold">OBSERVACIONES</label>
                                <div class="card bg-yellow-lt border-warning">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning me-2 mt-1 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 9v2m0 4v.01"/>
                                                <path d="M5.07 19H19a2 2 0 0 0 1.75 -2.75L13.75 4a2 2 0 0 0 -3.5 0L3.25 16.25a2 2 0 0 0 1.75 2.75"/>
                                            </svg>
                                            <div class="h5 mb-0">{{ $horario->observaciones }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            Estado del Horario
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="text-muted small">Estado Actual</div>
                                    <div class="h4">
                                        @if($horario->estaActivo())
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path d="M9 12l2 2l4 -4"/>
                                                </svg>
                                                Activo
                                            </span>
                                        @else
                                            <span class="text-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path d="M9 9l6 6"/>
                                                    <path d="M15 9l-6 6"/>
                                                </svg>
                                                Inactivo
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="text-muted small">ID del Horario</div>
                                    <div class="h4">#{{ str_pad($horario->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="text-muted small">Última Actualización</div>
                            <div>{{ $horario->updated_at->format('d/m/Y H:i') }}</div>
                            <small class="text-muted">{{ $horario->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 6l0 6l4 2"/>
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                            Acciones Rápidas
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('estudiantes.horarios.semanal') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                    <path d="M8 14h.01"/>
                                    <path d="M12 14h.01"/>
                                    <path d="M16 14h.01"/>
                                    <path d="M8 18h.01"/>
                                    <path d="M12 18h.01"/>
                                    <path d="M16 18h.01"/>
                                </svg>
                                Ver Horario Semanal
                            </a>
                            
                            @if($horario->sala)
                                <a href="{{ route('estudiantes.salas.show', $horario->sala) }}" class="btn btn-outline-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 21h18"/>
                                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                        <path d="M9 9h6"/>
                                        <path d="M9 12h6"/>
                                        <path d="M9 15h6"/>
                                    </svg>
                                    Información del Aula
                                </a>
                            @endif
                            
                            <a href="{{ route('estudiantes.salas') }}" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 21h18"/>
                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                    <path d="M9 9h6"/>
                                    <path d="M9 12h6"/>
                                    <path d="M9 15h6"/>
                                </svg>
                                Explorar Todas las Aulas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
