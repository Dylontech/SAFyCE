@extends('tablar::page')

@section('title', 'Horarios Académicos')

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
                    Horarios Académicos
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('estudiantes.horarios.semanal') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        Vista Semanal
                    </a>
                    <a href="{{ route('estudiantes.salas') }}" class="btn btn-outline-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 21h18"/>
                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                            <path d="M9 9h6"/>
                            <path d="M9 12h6"/>
                            <path d="M9 15h6"/>
                        </svg>
                        Ver Salas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/>
                                <path d="M21 21l-6 -6"/>
                            </svg>
                            Filtrar Horarios
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('estudiantes.horarios') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Día de la Semana</label>
                                        <select class="form-select" name="dia_semana">
                                            <option value="">Todos los días</option>
                                            @foreach($dias as $valor => $nombre)
                                                <option value="{{ $valor }}" {{ request('dia_semana') == $valor ? 'selected' : '' }}>
                                                    {{ $nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Materia</label>
                                        <select class="form-select" name="materia_id">
                                            <option value="">Todas las materias</option>
                                            @foreach($materias as $materia)
                                                <option value="{{ $materia->id }}" {{ request('materia_id') == $materia->id ? 'selected' : '' }}>
                                                    {{ $materia->materia }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Maestro</label>
                                        <input type="text" class="form-control" name="maestro" 
                                               value="{{ request('maestro') }}" 
                                               placeholder="Buscar por nombre del maestro...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/>
                                                    <path d="M21 21l-6 -6"/>
                                                </svg>
                                                Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(request()->hasAny(['dia_semana', 'materia_id', 'maestro']))
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{ route('estudiantes.horarios') }}" class="btn btn-outline-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M18 6l-12 12"/>
                                                <path d="M6 6l12 12"/>
                                            </svg>
                                            Limpiar Filtros
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de Horarios -->
        <div class="row row-deck row-cards">
            @forelse($horarios as $horario)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="card-title text-primary">
                                        @if($horario->materia)
                                            {{ $horario->materia->materia }}
                                        @else
                                            Sin materia asignada
                                        @endif
                                    </h3>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-{{ $horario->estaActivo() ? 'success' : 'secondary' }}">
                                        {{ $horario->estaActivo() ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Día y Horario -->
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-muted small">DÍA</div>
                                        <div class="h4">
                                            <span class="badge bg-primary">{{ ucfirst($horario->dia_semana) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">HORARIO</div>
                                        <div class="fw-bold">
                                            {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Maestro -->
                            @if($horario->maestro)
                                <div class="mb-3">
                                    <div class="text-muted small">MAESTRO</div>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm me-2 bg-secondary text-white">
                                            {{ substr($horario->maestro->name, 0, 2) }}
                                        </span>
                                        <div>
                                            <div class="fw-bold">{{ $horario->maestro->name }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Sala -->
                            @if($horario->sala)
                                <div class="mb-3">
                                    <div class="text-muted small">SALA</div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="fw-bold">{{ $horario->sala->nombre }}</div>
                                            <small class="text-muted">{{ ucfirst($horario->sala->tipo) }}</small>
                                        </div>
                                        @if($horario->sala->capacidad)
                                            <span class="badge bg-info">{{ $horario->sala->capacidad }} personas</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Período -->
                            <div class="text-muted small">PERÍODO</div>
                            <div class="small">
                                Del {{ \Carbon\Carbon::parse($horario->fecha_inicio)->format('d/m/Y') }} 
                                al {{ \Carbon\Carbon::parse($horario->fecha_fin)->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('estudiantes.horarios.show', $horario) }}" class="btn btn-outline-primary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                    </svg>
                                    Ver Detalles
                                </a>
                                @if($horario->sala)
                                    <a href="{{ route('estudiantes.salas.show', $horario->sala) }}" class="btn btn-outline-info btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty">
                        <div class="empty-img">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="128" height="128" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <rect x="4" y="5" width="16" height="16" rx="2"/>
                                <line x1="16" y1="3" x2="16" y2="7"/>
                                <line x1="8" y1="3" x2="8" y2="7"/>
                                <line x1="4" y1="11" x2="20" y2="11"/>
                            </svg>
                        </div>
                        <p class="empty-title">No se encontraron horarios</p>
                        <p class="empty-subtitle text-muted">
                            @if(request()->hasAny(['dia_semana', 'materia_id', 'maestro']))
                                No hay horarios que coincidan con los filtros aplicados.
                            @else
                                No hay horarios académicos disponibles en este momento.
                            @endif
                        </p>
                        @if(request()->hasAny(['dia_semana', 'materia_id', 'maestro']))
                            <div class="empty-action">
                                <a href="{{ route('estudiantes.horarios') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M18 6l-12 12"/>
                                        <path d="M6 6l12 12"/>
                                    </svg>
                                    Limpiar Filtros
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($horarios->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $horarios->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
