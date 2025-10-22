@extends('tablar::page')

@section('title', 'Salas de Clase')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Control Escolar
                    </div>
                    <h2 class="page-title">
                        Salas de Clase
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    @can('gestionar salas')
                    <div class="btn-list">
                        <a href="{{ route('salas.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Nueva Sala
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('salas.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Tipo de Sala</label>
                                            <select name="tipo" class="form-select">
                                                <option value="">Todos los tipos</option>
                                                <option value="aula" {{ request('tipo') == 'aula' ? 'selected' : '' }}>Aula</option>
                                                <option value="laboratorio" {{ request('tipo') == 'laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                                                <option value="taller" {{ request('tipo') == 'taller' ? 'selected' : '' }}>Taller</option>
                                                <option value="auditorio" {{ request('tipo') == 'auditorio' ? 'selected' : '' }}>Auditorio</option>
                                                <option value="sala_de_juntas" {{ request('tipo') == 'sala_de_juntas' ? 'selected' : '' }}>Sala de Juntas</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Estado</label>
                                            <select name="estado" class="form-select">
                                                <option value="">Todos los estados</option>
                                                <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                                <option value="ocupada" {{ request('estado') == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                                                <option value="mantenimiento" {{ request('estado') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                                <option value="fuera_de_servicio" {{ request('estado') == 'fuera_de_servicio' ? 'selected' : '' }}>Fuera de Servicio</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Buscar</label>
                                            <input type="text" name="buscar" class="form-control" placeholder="Nombre, código o ubicación..." value="{{ request('buscar') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards">
                @forelse($salas as $sala)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-status-top 
                            @if($sala->estado == 'disponible') bg-success
                            @elseif($sala->estado == 'ocupada') bg-warning
                            @elseif($sala->estado == 'mantenimiento') bg-info
                            @else bg-danger
                            @endif">
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    @if($sala->tipo == 'laboratorio')
                                        <div class="avatar bg-primary text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M9 2v6l-2 4v4a2 2 0 0 0 2 2h6a2 2 0 0 0 2 -2v-4l-2 -4v-6"/>
                                                <line x1="7" y1="2" x2="17" y2="2"/>
                                            </svg>
                                        </div>
                                    @elseif($sala->tipo == 'taller')
                                        <div class="avatar bg-warning text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5"/>
                                            </svg>
                                        </div>
                                    @elseif($sala->tipo == 'auditorio')
                                        <div class="avatar bg-info text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M6 20a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2"/>
                                                <path d="M8 16h8"/>
                                                <path d="M7 12l10 0"/>
                                                <path d="M7 8l10 0"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="avatar bg-secondary text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/>
                                                <path d="M20 12h-13m9 -3l3 3l-3 3"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="card-title m-0">{{ $sala->nombre }}</h3>
                                    <div class="text-muted">{{ $sala->codigo }}</div>
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <span class="badge bg-{{ $sala->estado == 'disponible' ? 'success' : ($sala->estado == 'ocupada' ? 'warning' : ($sala->estado == 'mantenimiento' ? 'info' : 'danger')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $sala->estado)) }}
                                </span>
                                <span class="badge bg-secondary ms-1">{{ ucfirst($sala->tipo) }}</span>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-muted">Capacidad</div>
                                    <div><strong>{{ $sala->capacidad }} personas</strong></div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Ubicación</div>
                                    <div><strong>{{ $sala->ubicacion ?: 'No especificada' }}</strong></div>
                                </div>
                            </div>
                            
                            @if($sala->descripcion)
                            <div class="mt-2">
                                <div class="text-muted">Descripción</div>
                                <div class="text-wrap">{{ $sala->descripcion }}</div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="card-footer">
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route('salas.show', $sala) }}" class="btn btn-outline-primary btn-sm">
                                        Ver Detalles
                                    </a>
                                </div>
                                <div class="col-auto">
                                    @can('gestionar salas')
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('salas.edit', $sala) }}">Editar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('salas.destroy', $sala) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Estás seguro de eliminar esta sala?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="empty">
                        <div class="empty-img"><img src="{{ asset('dist/img/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                        </div>
                        <p class="empty-title">No se encontraron salas</p>
                        <p class="empty-subtitle text-muted">
                            Intenta ajustar tu búsqueda o filtros para encontrar lo que buscas.
                        </p>
                        @can('gestionar salas')
                        <div class="empty-action">
                            <a href="{{ route('salas.create') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Crear primera sala
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
                @endforelse
            </div>
            
            <!-- Paginación -->
            @if($salas->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $salas->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
