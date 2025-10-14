@extends('tablar::page')

@section('title', 'Detalle del Aula - ' . $sala->nombre)

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
                        <path d="M3 21h18"/>
                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                        <path d="M9 9h6"/>
                        <path d="M9 12h6"/>
                        <path d="M9 15h6"/>
                    </svg>
                    {{ $sala->nombre }}
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('estudiantes.salas') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <line x1="12" y1="5" x2="19" y2="12"/>
                            <line x1="12" y1="19" x2="19" y2="12"/>
                        </svg>
                        Volver a Salas
                    </a>
                    <a href="{{ route('estudiantes.horarios') }}" class="btn btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                            <line x1="16" y1="3" x2="16" y2="7"/>
                            <line x1="8" y1="3" x2="8" y2="7"/>
                            <line x1="4" y1="11" x2="20" y2="11"/>
                        </svg>
                        Ver Horarios
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
            <!-- Información Principal del Aula -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 21h18"/>
                                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                        <path d="M9 9h6"/>
                                        <path d="M9 12h6"/>
                                        <path d="M9 15h6"/>
                                    </svg>
                                    Información del Aula
                                </h3>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-{{ $sala->estado == 'disponible' ? 'success' : ($sala->estado == 'mantenimiento' ? 'warning' : 'danger') }} fs-6 px-3 py-2">
                                    {{ ucfirst($sala->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Información Básica -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">NOMBRE DEL AULA</label>
                                    <div class="h2 mb-1 text-primary">{{ $sala->nombre }}</div>
                                    @if($sala->codigo)
                                        <div class="text-muted">
                                            <span class="badge bg-info me-2">Código: {{ $sala->codigo }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">TIPO DE SALA</label>
                                    <div class="h4">
                                        <span class="badge bg-primary fs-5 px-3 py-2">{{ ucfirst($sala->tipo) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Capacidad y Ubicación -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">CAPACIDAD</label>
                                    @if($sala->capacidad)
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center py-3">
                                                <div class="h1 mb-0">{{ $sala->capacidad }}</div>
                                                <div class="text-white-50">personas</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card bg-light border-0">
                                            <div class="card-body text-center py-3 text-muted">
                                                <div class="h4 mb-0">Capacidad no especificada</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">UBICACIÓN</label>
                                    @if($sala->ubicacion)
                                        <div class="card bg-info text-white">
                                            <div class="card-body py-3">
                                                <div class="d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <circle cx="12" cy="11" r="3"/>
                                                        <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/>
                                                    </svg>
                                                    <div class="h5 mb-0">{{ $sala->ubicacion }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card bg-light border-0">
                                            <div class="card-body text-center py-3 text-muted">
                                                <div class="h5 mb-0">Ubicación no especificada</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($sala->descripcion)
                        <hr class="my-4">
                        
                        <!-- Descripción -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold">DESCRIPCIÓN</label>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $sala->descripcion }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($sala->equipamiento)
                        <hr class="my-4">
                        
                        <!-- Equipamiento -->
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold">EQUIPAMIENTO DISPONIBLE</label>
                                <div class="card border-primary">
                                    <div class="card-body">
                                        @php
                                            $equipos = explode(',', $sala->equipamiento);
                                        @endphp
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($equipos as $equipo)
                                                <span class="badge bg-primary fs-6 px-3 py-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <circle cx="12" cy="12" r="9"/>
                                                        <path d="M9 12l2 2l4 -4"/>
                                                    </svg>
                                                    {{ trim($equipo) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-lg-4">
                <!-- Estado del Aula -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            Estado Actual
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            @if($sala->estado == 'disponible')
                                <div class="mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-success" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M9 12l2 2l4 -4"/>
                                    </svg>
                                </div>
                                <div class="h3 text-success">Disponible</div>
                                <p class="text-muted mb-0">El aula está disponible para uso académico</p>
                            @elseif($sala->estado == 'ocupada')
                                <div class="mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-danger" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M15 9l-6 6"/>
                                        <path d="M9 9l6 6"/>
                                    </svg>
                                </div>
                                <div class="h3 text-danger">Ocupada</div>
                                <p class="text-muted mb-0">El aula está actualmente en uso</p>
                            @else
                                <div class="mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-warning" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 8l0 4"/>
                                        <path d="M12 16l.01 0"/>
                                    </svg>
                                </div>
                                <div class="h3 text-warning">En Mantenimiento</div>
                                <p class="text-muted mb-0">El aula no está disponible temporalmente</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 6l0 6l4 2"/>
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                            Información del Sistema
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-muted small">ID del Aula</div>
                            <div class="h5">#{{ str_pad($sala->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="text-muted small">Horarios Activos</div>
                            <div class="h5">{{ $horariosActuales->count() }} clases</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Última Actualización</div>
                            <div>{{ $sala->updated_at->format('d/m/Y H:i') }}</div>
                            <small class="text-muted">{{ $sala->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horarios del Aula -->
            @if($horariosActuales->count() > 0)
                <div class="col-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="4" y="5" width="16" height="16" rx="2"/>
                                    <line x1="16" y1="3" x2="16" y2="7"/>
                                    <line x1="8" y1="3" x2="8" y2="7"/>
                                    <line x1="4" y1="11" x2="20" y2="11"/>
                                </svg>
                                Horarios de Clases en Esta Aula
                            </h3>
                            <div class="card-actions">
                                <span class="badge bg-primary">{{ $horariosActuales->count() }} clase{{ $horariosActuales->count() != 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Día</th>
                                            <th>Horario</th>
                                            <th>Materia</th>
                                            <th>Profesor</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($horariosActuales as $horario)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">{{ ucfirst($horario->dia_semana) }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</strong>
                                                    @php
                                                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                                        $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                                        $duracion = $fin->diffInMinutes($inicio);
                                                    @endphp
                                                    <br><small class="text-muted">{{ floor($duracion / 60) }}h {{ $duracion % 60 }}min</small>
                                                </td>
                                                <td>
                                                    @if($horario->materia)
                                                        <strong>{{ $horario->materia->materia }}</strong>
                                                        @if($horario->materia->codigo)
                                                            <br><small class="text-muted">{{ $horario->materia->codigo }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Sin materia asignada</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($horario->maestro)
                                                        <div class="d-flex align-items-center">
                                                            <span class="avatar avatar-sm me-2 bg-secondary text-white">
                                                                {{ substr($horario->maestro->name, 0, 2) }}
                                                            </span>
                                                            <div>
                                                                <div>{{ $horario->maestro->name }}</div>
                                                                <small class="text-muted">{{ $horario->maestro->email }}</small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Sin profesor asignado</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $horario->estaActivo() ? 'success' : 'secondary' }}">
                                                        {{ $horario->estaActivo() ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('estudiantes.horarios.show', $horario) }}" class="btn btn-outline-primary btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                                        </svg>
                                                        Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="4" y="5" width="16" height="16" rx="2"/>
                                    <line x1="16" y1="3" x2="16" y2="7"/>
                                    <line x1="8" y1="3" x2="8" y2="7"/>
                                    <line x1="4" y1="11" x2="20" y2="11"/>
                                </svg>
                                Horarios de Clases
                            </h3>
                        </div>
                        <div class="card-body text-center py-5">
                            <div class="empty">
                                <div class="empty-img">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="96" height="96" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                                        <line x1="16" y1="3" x2="16" y2="7"/>
                                        <line x1="8" y1="3" x2="8" y2="7"/>
                                        <line x1="4" y1="11" x2="20" y2="11"/>
                                    </svg>
                                </div>
                                <p class="empty-title">Sin horarios asignados</p>
                                <p class="empty-subtitle text-muted">
                                    Esta aula no tiene clases programadas actualmente.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
