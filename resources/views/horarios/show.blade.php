@extends('tablar::page')

@section('title', 'Detalle del Horario')

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
                    Detalle del Horario
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    @can('editar horarios')
                    <a href="{{ route('horarios.edit', $horario) }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Editar Horario
                    </a>
                    @endcan
                    <a href="{{ route('horarios.index') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <line x1="12" y1="5" x2="19" y2="12"/>
                            <line x1="12" y1="19" x2="19" y2="12"/>
                        </svg>
                        Volver al Listado
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
                                    Información del Horario
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
                        <!-- Fila 1: Materia y Día/Horario -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">MATERIA</label>
                                    @if($horario->materia)
                                        <div class="h3 mb-1 text-primary">{{ $horario->materia->materia }}</div>
                                        @if($horario->materia->codigo)
                                            <small class="text-muted">Código: {{ $horario->materia->codigo }}</small>
                                        @endif
                                    @else
                                        <div class="h3 mb-1 text-muted">No asignada</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">DÍA Y HORARIO</label>
                                    <div class="h3 mb-1">
                                        <span class="badge bg-primary fs-6 px-3 py-2">{{ ucfirst($horario->dia_semana) }}</span>
                                    </div>
                                    <div class="text-muted">
                                        <strong>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</strong>
                                        @php
                                            $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                            $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                            $duracion = $fin->diffInMinutes($inicio);
                                        @endphp
                                        <br><small>({{ floor($duracion / 60) }}h {{ $duracion % 60 }}min)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Fila 2: Maestro y Sala -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">MAESTRO</label>
                                    @if($horario->maestro)
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="avatar avatar-md me-3 bg-primary text-white">
                                                {{ substr($horario->maestro->name, 0, 2) }}
                                            </span>
                                            <div>
                                                <div class="h4 mb-0">{{ $horario->maestro->name }}</div>
                                                <small class="text-muted">{{ $horario->maestro->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="h4 mb-0 text-muted">No asignado</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">SALA</label>
                                    @if($horario->sala)
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
                                        <a href="{{ route('salas.show', $horario->sala) }}" class="btn btn-outline-primary btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                            </svg>
                                            Ver Sala
                                        </a>
                                    @else
                                        <div class="h4 mb-0 text-muted">No asignada</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Fila 3: Período de Vigencia -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold">PERÍODO DE VIGENCIA</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center py-3">
                                                <div class="text-muted small">Fecha de Inicio</div>
                                                <div class="h4 mb-0">{{ \Carbon\Carbon::parse($horario->fecha_inicio)->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($horario->fecha_inicio)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center py-3">
                                                <div class="text-muted small">Fecha de Fin</div>
                                                <div class="h4 mb-0">{{ \Carbon\Carbon::parse($horario->fecha_fin)->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($horario->fecha_fin)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-white">
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
                                <div class="card bg-yellow-lt">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow me-2 mt-1 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 9v2m0 4v.01"/>
                                                <path d="M5.07 19H19a2 2 0 0 0 1.75 -2.75L13.75 4a2 2 0 0 0 -3.5 0L3.25 16.25a2 2 0 0 0 1.75 2.75"/>
                                            </svg>
                                            <div>{{ $horario->observaciones }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel de Información Adicional -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            Información del Sistema
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="text-muted small">ID del Horario</div>
                                    <div class="h4">#{{ str_pad($horario->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
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
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="text-muted small">Fecha de Creación</div>
                                    <div>{{ $horario->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $horario->created_at->format('H:i') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="text-muted small">Última Modificación</div>
                                    <div>{{ $horario->updated_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $horario->updated_at->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Acciones -->
            <div class="col-md-4">
                @can('eliminar horarios')
                <div class="card border-danger">
                    <div class="card-header bg-danger-lt">
                        <h3 class="card-title text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 9v2m0 4v.01"/>
                                <path d="M5.07 19H19a2 2 0 0 0 1.75 -2.75L13.75 4a2 2 0 0 0 -3.5 0L3.25 16.25a2 2 0 0 0 1.75 2.75"/>
                            </svg>
                            Zona de Peligro
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            <strong>¡Cuidado!</strong> Esta acción eliminará permanentemente este horario y no se puede deshacer.
                        </p>
                        <form action="{{ route('horarios.destroy', $horario) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este horario?\n\nEsta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="4" y1="7" x2="20" y2="7"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                </svg>
                                Eliminar Horario
                            </button>
                        </form>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
