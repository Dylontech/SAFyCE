@extends('tablar::page')

@section('title', 'Aulas y Salas')

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
                    Aulas y Salas
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
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
                    <a href="{{ route('estudiantes.horarios.semanal') }}" class="btn btn-outline-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Horario Semanal
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
                            Buscar Aulas y Salas
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('estudiantes.salas') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Buscar</label>
                                        <input type="text" class="form-control" name="buscar" 
                                               value="{{ request('buscar') }}" 
                                               placeholder="Nombre, código o descripción...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Sala</label>
                                        <select class="form-select" name="tipo">
                                            <option value="">Todos los tipos</option>
                                            @foreach($tipos as $tipo)
                                                <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                                                    {{ ucfirst($tipo) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Estado</label>
                                        <select class="form-select" name="estado">
                                            <option value="">Todos los estados</option>
                                            @foreach($estados as $estado)
                                                <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                                                    {{ ucfirst($estado) }}
                                                </option>
                                            @endforeach
                                        </select>
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
                            @if(request()->hasAny(['buscar', 'tipo', 'estado']))
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{ route('estudiantes.salas') }}" class="btn btn-outline-secondary">
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

        <!-- Listado de Salas -->
        <div class="row row-deck row-cards">
            @forelse($salas as $sala)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="card-title text-primary">{{ $sala->nombre }}</h3>
                                    @if($sala->codigo)
                                        <div class="card-subtitle">Código: {{ $sala->codigo }}</div>
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-{{ $sala->estado == 'disponible' ? 'success' : ($sala->estado == 'mantenimiento' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($sala->estado) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Tipo y Capacidad -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="text-muted small">TIPO</div>
                                    <div class="fw-bold">
                                        <span class="badge bg-info">{{ ucfirst($sala->tipo) }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">CAPACIDAD</div>
                                    <div class="fw-bold">
                                        @if($sala->capacidad)
                                            <span class="badge bg-success">{{ $sala->capacidad }} personas</span>
                                        @else
                                            <span class="text-muted">No especificada</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            @if($sala->descripcion)
                                <div class="mb-3">
                                    <div class="text-muted small">DESCRIPCIÓN</div>
                                    <div class="text-truncate" title="{{ $sala->descripcion }}">
                                        {{ $sala->descripcion }}
                                    </div>
                                </div>
                            @endif

                            <!-- Equipamiento -->
                            @if($sala->equipamiento)
                                <div class="mb-3">
                                    <div class="text-muted small">EQUIPAMIENTO</div>
                                    <div class="small">
                                        @php
                                            $equipos = explode(',', $sala->equipamiento);
                                        @endphp
                                        @foreach(array_slice($equipos, 0, 3) as $equipo)
                                            <span class="badge bg-light text-dark me-1">{{ trim($equipo) }}</span>
                                        @endforeach
                                        @if(count($equipos) > 3)
                                            <span class="badge bg-secondary">+{{ count($equipos) - 3 }} más</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Estado de Uso -->
                            <div class="text-muted small">ESTADO</div>
                            <div class="d-flex align-items-center">
                                @if($sala->estado == 'disponible')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M9 12l2 2l4 -4"/>
                                    </svg>
                                    <span class="text-success">Disponible</span>
                                @elseif($sala->estado == 'ocupada')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M15 9l-6 6"/>
                                        <path d="M9 9l6 6"/>
                                    </svg>
                                    <span class="text-danger">Ocupada</span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 8l0 4"/>
                                        <path d="M12 16l.01 0"/>
                                    </svg>
                                    <span class="text-warning">En mantenimiento</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('estudiantes.salas.show', $sala) }}" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                </svg>
                                Ver Detalles y Horarios
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty">
                        <div class="empty-img">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="128" height="128" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 21h18"/>
                                <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                <path d="M9 9h6"/>
                                <path d="M9 12h6"/>
                                <path d="M9 15h6"/>
                            </svg>
                        </div>
                        <p class="empty-title">No se encontraron salas</p>
                        <p class="empty-subtitle text-muted">
                            @if(request()->hasAny(['buscar', 'tipo', 'estado']))
                                No hay salas que coincidan con los filtros aplicados.
                            @else
                                No hay salas disponibles para mostrar en este momento.
                            @endif
                        </p>
                        @if(request()->hasAny(['buscar', 'tipo', 'estado']))
                            <div class="empty-action">
                                <a href="{{ route('estudiantes.salas') }}" class="btn btn-primary">
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

        <!-- Estadísticas Rápidas -->
        @if($salas->total() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                    <path d="M9 12l2 2l4 -4"/>
                                </svg>
                                Resumen de Instalaciones
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="h1 text-primary">{{ $salas->total() }}</div>
                                        <div class="text-muted">Total de Salas</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @php
                                            $disponibles = $salas->where('estado', 'disponible')->count();
                                        @endphp
                                        <div class="h1 text-success">{{ $disponibles }}</div>
                                        <div class="text-muted">Disponibles</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @php
                                            $ocupadas = $salas->where('estado', 'ocupada')->count();
                                        @endphp
                                        <div class="h1 text-danger">{{ $ocupadas }}</div>
                                        <div class="text-muted">Ocupadas</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @php
                                            $mantenimiento = $salas->where('estado', 'mantenimiento')->count();
                                        @endphp
                                        <div class="h1 text-warning">{{ $mantenimiento }}</div>
                                        <div class="text-muted">En Mantenimiento</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Paginación -->
        @if($salas->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $salas->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
