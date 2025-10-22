@extends('tablar::page')

@section('title')
    Grupos
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Lista
                    </div>
                    <h2 class="page-title">
                        {{ __('Grupos ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <!-- Botón completo para escritorio -->
                        <a href="{{ route('grupos.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Crear Grupo
                        </a>
                        <!-- Botón compacto para móviles -->
                        <a href="{{ route('grupos.create') }}" class="btn btn-primary d-sm-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span class="d-none d-xs-inline-block">Crear</span>
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
                <div class="col-12">
                    <div class="card">
                        <!-- Botón de filtros para móvil -->
                        <div class="card-header d-block d-md-none">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Grupos</h3>
                                <button class="btn btn-ghost-secondary btn-sm" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#filtros-mobile" aria-expanded="false" aria-controls="filtros-mobile">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" 
                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5.5 5h13a1 1 0 0 1 .5 1.5L14 12L14 19L10 16L10 12L5 6.5a1 1 0 0 1 .5 -1.5"/>
                                    </svg>
                                    Filtros
                                </button>
                            </div>
                        </div>
                        
                        <!-- Formulario de filtros para móvil (colapsible) -->
                        <div class="collapse d-md-none" id="filtros-mobile">
                            <div class="card-body border-bottom py-3">
                                <form method="GET" action="{{ route('grupos.index') }}">
                                    <div class="mb-3">
                                        <label for="mobile_search" class="form-label">Buscar</label>
                                        <input type="text" class="form-control" name="search" id="mobile_search" 
                                               placeholder="Buscar grupo..." value="{{ request('search') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_semestre" class="form-label">Semestre</label>
                                        <select class="form-control" name="semestre" id="mobile_semestre">
                                            <option value="">Todos</option>
                                            @foreach($semestres as $semestre)
                                                <option value="{{ $semestre }}" {{ request('semestre') == $semestre ? 'selected' : '' }}>
                                                    Semestre {{ $semestre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_activo" class="form-label">Estado</label>
                                        <select class="form-control" name="activo" id="mobile_activo">
                                            <option value="">Todos</option>
                                            <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                                            <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                                        </select>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                                        <a href="{{ route('grupos.index') }}" class="btn btn-secondary">Limpiar</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Formulario de filtros para desktop -->
                        <div class="card-body border-bottom py-3 d-none d-md-block">
                            <form method="GET" action="{{ route('grupos.index') }}">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="me-3 mb-2" style="flex-grow: 2;">
                                        <label for="search" class="form-label">Buscar</label>
                                        <div class="d-inline-block" style="width: 200px;">
                                            <input type="text" class="form-control form-control-md" name="search" id="search" 
                                                   placeholder="Buscar grupo..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="me-3 mb-2">
                                        <label for="semestre" class="form-label">Semestre</label>
                                        <div class="d-inline-block" style="width: 150px;">
                                            <select class="form-control form-control-md" name="semestre" id="semestre">
                                                <option value="">Todos</option>
                                                @foreach($semestres as $semestre)
                                                    <option value="{{ $semestre }}" {{ request('semestre') == $semestre ? 'selected' : '' }}>
                                                        Semestre {{ $semestre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="me-3 mb-2">
                                        <label for="activo" class="form-label">Estado</label>
                                        <div class="d-inline-block" style="width: 120px;">
                                            <select class="form-control form-control-md" name="activo" id="activo">
                                                <option value="">Todos</option>
                                                <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                                                <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-md me-2">Filtrar</button>
                                            <a href="{{ route('grupos.index') }}" class="btn btn-secondary btn-md">Limpiar</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="w-1">No.</th>
                                        <th>Grupo</th>
                                        <th>Semestre</th>
                                        <th>Letra</th>
                                        <th>Estado</th>
                                        <th>Alumnos</th>
                                        <th>Fecha Creación</th>
                                        <th class="w-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if ($grupos->count() > 0)
                                    @foreach ($grupos as $grupo)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>
                                                <strong>{{ $grupo->nombre_completo }}</strong>
                                            </td>
                                            <td>{{ $grupo->semestre }}</td>
                                            <td>{{ strtoupper($grupo->letra) }}</td>
                                            <td>
                                                @if($grupo->activo)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-blue">{{ $grupo->alumnos()->count() }}</span>
                                            </td>
                                            <td>{{ $grupo->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('grupos.show', $grupo->id) }}" 
                                                       title="Ver detalles">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                             stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <circle cx="12" cy="12" r="2"/>
                                                            <path d="M12 1c5 0 9 4 9 11c0 1 -1 2 -2 3l-1 1l-1 1l-1 1c-1 1 -1 1 -2 1h-8c-1 0 -1 0 -2 -1l-1 -1l-1 -1l-1 -1c-1 -1 -2 -2 -2 -3c0 -7 4 -11 9 -11z"/>
                                                        </svg>
                                                    </a>
                                                    <a class="btn btn-sm btn-warning" href="{{ route('grupos.edit', $grupo->id) }}" 
                                                       title="Editar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                             stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                            <path d="M16 5l3 3"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('grupos.destroy', $grupo->id) }}" method="POST" 
                                                          style="display: inline-block;" 
                                                          onsubmit="return confirm('¿Está seguro de que desea eliminar este grupo?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                                 stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <line x1="4" y1="7" x2="20" y2="7"/>
                                                                <line x1="10" y1="11" x2="10" y2="17"/>
                                                                <line x1="14" y1="11" x2="14" y2="17"/>
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="empty">
                                                <div class="empty-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                         stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <circle cx="12" cy="7" r="4"/>
                                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                                    </svg>
                                                </div>
                                                <p class="empty-title">No se encontraron grupos</p>
                                                <p class="empty-subtitle text-muted">
                                                    No hay grupos registrados con los filtros aplicados.
                                                </p>
                                                <div class="empty-action">
                                                    <a href="{{ route('grupos.create') }}" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                             stroke-linecap="round" stroke-linejoin="round">
                                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                                        </svg>
                                                        Crear primer grupo
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {!! $grupos->appends(request()->query())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
