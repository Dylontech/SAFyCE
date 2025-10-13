@extends('tablar::page')

@section('title')
    Materia
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
                        {{ __('Materia ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <!-- Botón completo para escritorio -->
                        <a href="{{ route('materias.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Crear Materia
                        </a>
                        <!-- Botón compacto para móviles -->
                        <a href="{{ route('materias.create') }}" class="btn btn-primary d-sm-none">
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
            @if(config('tablar','display_alert'))
                @include('tablar::common.alert')
            @endif
            
            <!-- Alertas personalizadas -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="12" r="9" />
                        <path d="M9 12l2 2l4 -4" />
                    </svg>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Materia</h3>
                            <!-- Botón de hamburguesa para móviles -->
                            <div class="card-actions d-md-none">
                                <button class="btn btn-icon btn-ghost" type="button" data-bs-toggle="offcanvas" 
                                        data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="4" y1="6" x2="20" y2="6" />
                                        <line x1="4" y1="12" x2="20" y2="12" />
                                        <line x1="4" y1="18" x2="20" y2="18" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Formulario de filtros para desktop -->
                        <div class="card-body border-bottom py-3 d-none d-md-block">
                            <form method="GET" action="{{ route('materias.index') }}">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="me-3 mb-2" style="flex-grow: 2;">
                                        <label for="semestre" class="form-label">Semestre</label>
                                        <div class="d-inline-block" style="width: 200px;">
                                            <select class="form-control form-control-md" name="semestre" id="semestre">
                                                <option value="">Todos</option>
                                                <option value="1" {{ request('semestre') == '1' ? 'selected' : '' }}>Semestre 1</option>
                                                <option value="2" {{ request('semestre') == '2' ? 'selected' : '' }}>Semestre 2</option>
                                                <option value="3" {{ request('semestre') == '3' ? 'selected' : '' }}>Semestre 3</option>
                                                <option value="4" {{ request('semestre') == '4' ? 'selected' : '' }}>Semestre 4</option>
                                                <option value="5" {{ request('semestre') == '5' ? 'selected' : '' }}>Semestre 5</option>
                                                <option value="6" {{ request('semestre') == '6' ? 'selected' : '' }}>Semestre 6</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="me-3 mb-2" style="flex-grow: 2;">
                                        <label for="especialidad" class="form-label">Especialidad</label>
                                                                                <div class="d-inline-block" style="width: 250px;">
                                            <select class="form-control form-control-md" name="especialidad" id="especialidad">
                                                <option value="">Todas</option>
                                                @foreach($especialidades as $especialidad)
                                                    <option value="{{ $especialidad->nombre }}" {{ request('especialidad') == $especialidad->nombre ? 'selected' : '' }}>
                                                        {{ $especialidad->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="me-3 mb-2" style="flex-grow: 1;">
                                        <label for="search" class="form-label">Buscar</label>
                                        <div class="d-inline-block" style="width: 200px;">
                                            <input type="text" class="form-control form-control-md" name="search" 
                                                   id="search" value="{{ request('search') }}" aria-label="Buscar materia" placeholder="Buscar materia...">
                                        </div>
                                    </div>
                                    <div class="align-self-end mb-2">
                                        <button type="submit" class="btn btn-primary btn-md">Filtrar</button>
                                        <a href="{{ route('materias.index') }}" class="btn btn-outline-secondary btn-md">Limpiar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Offcanvas para filtros en móviles -->
                        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="filterOffcanvas" 
                             aria-labelledby="filterOffcanvasLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="filterOffcanvasLabel">Filtros</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" 
                                        aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <form method="GET" action="{{ route('materias.index') }}" id="mobileFilterForm">
                                    <div class="mb-3">
                                        <label for="mobile_semestre" class="form-label">Semestre</label>
                                        <select class="form-control" name="semestre" id="mobile_semestre">
                                            <option value="">Todos</option>
                                            <option value="1" {{ request('semestre') == '1' ? 'selected' : '' }}>Semestre 1</option>
                                            <option value="2" {{ request('semestre') == '2' ? 'selected' : '' }}>Semestre 2</option>
                                            <option value="3" {{ request('semestre') == '3' ? 'selected' : '' }}>Semestre 3</option>
                                            <option value="4" {{ request('semestre') == '4' ? 'selected' : '' }}>Semestre 4</option>
                                            <option value="5" {{ request('semestre') == '5' ? 'selected' : '' }}>Semestre 5</option>
                                            <option value="6" {{ request('semestre') == '6' ? 'selected' : '' }}>Semestre 6</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_especialidad" class="form-label">Especialidad</label>
                                        <select class="form-control" name="especialidad" id="mobile_especialidad">
                                            <option value="">Todas</option>
                                            @foreach($especialidades as $especialidad)
                                                <option value="{{ $especialidad->nombre }}" {{ request('especialidad') == $especialidad->nombre ? 'selected' : '' }}>
                                                    {{ $especialidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_search" class="form-label">Buscar</label>
                                        <input type="text" class="form-control" name="search" id="mobile_search" 
                                               value="{{ request('search') }}" placeholder="Buscar materia...">
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                        <a href="{{ route('materias.index') }}" class="btn btn-outline-secondary">Limpiar Filtros</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Indicadores de filtros activos -->
                        @if(request()->has('search') || request()->has('semestre') || request()->has('especialidad'))
                        <div class="card-body py-2 border-bottom">
                            <div class="d-flex align-items-center">
                                <span class="text-muted me-2">Filtros activos:</span>
                                <div class="d-flex flex-wrap gap-1">
                                    @if(request('search'))
                                        <span class="badge bg-primary">Búsqueda: "{{ request('search') }}"</span>
                                    @endif
                                    @if(request('semestre'))
                                        <span class="badge bg-info">Semestre: {{ request('semestre') }}</span>
                                    @endif
                                    @if(request('especialidad'))
                                        <span class="badge bg-success">Especialidad: {{ ucfirst(request('especialidad')) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tabla responsive con scroll horizontal en móviles -->
                        <div class="table-responsive" style="min-height: 500px;">
                            <table class="table table-lg card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="text-nowrap">Materia</th>
                                    <th class="text-nowrap d-none d-sm-table-cell">Semestre</th>
                                    <th class="text-nowrap d-none d-md-table-cell">Especialidad</th>
                                    <th class="w-1 text-nowrap">Acciones</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($materias as $materia)
                                    <tr>
                                        <!-- Columna principal siempre visible -->
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $materia->materia }}">
                                                {{ $materia->materia }}
                                            </div>
                                            <!-- Información adicional visible solo en móviles -->
                                            <div class="d-sm-none">
                                                <small class="text-muted d-block">Sem: {{ $materia->semestre }}</small>
                                                <small class="text-muted d-block d-md-none">{{ $materia->especialidad }}</small>
                                            </div>
                                        </td>
                                        <!-- Semestre - oculto en móviles muy pequeños -->
                                        <td class="d-none d-sm-table-cell">{{ $materia->semestre }}</td>
                                        <!-- Especialidad - oculto en tablets pequeños -->
                                        <td class="d-none d-md-table-cell">{{ $materia->especialidad }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <!-- Botón de acciones simplificado -->
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle align-text-top px-3 py-2"
                                                            data-bs-toggle="dropdown" 
                                                            style="min-width: 100px; font-weight: 500;">
                                                        Acciones
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end shadow">
                                                        <a class="dropdown-item py-2"
                                                           href="{{ route('materias.show',$materia->id) }}">
                                                            Ver
                                                        </a>
                                                        <a class="dropdown-item py-2"
                                                           href="{{ route('materias.edit',$materia->id) }}">
                                                            Editar
                                                        </a>
                                                        <form
                                                            action="{{ route('materias.destroy',$materia->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    onclick="if(!confirm('¿Está seguro de que desea eliminar esta materia?')){return false;}"
                                                                    class="dropdown-item py-2 text-danger">
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="empty">
                                                <div class="empty-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <rect x="3" y="4" width="18" height="12" rx="1"/>
                                                        <path d="m16 8l-8 5l8 5v-10z"/>
                                                    </svg>
                                                </div>
                                                <p class="empty-title">No se encontraron materias</p>
                                                <p class="empty-subtitle text-muted">
                                                    @if(request()->has('search') || request()->has('semestre') || request()->has('especialidad'))
                                                        Intenta ajustar los filtros de búsqueda
                                                    @else
                                                        Comienza creando una nueva materia
                                                    @endif
                                                </p>
                                                <div class="empty-action">
                                                    @if(request()->has('search') || request()->has('semestre') || request()->has('especialidad'))
                                                        <a href="{{ route('materias.index') }}" class="btn btn-secondary">
                                                            Limpiar filtros
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('materias.create') }}" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                                        </svg>
                                                        Crear primera materia
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                       <div class="card-footer d-flex align-items-center">
                            {!! $materias->appends(request()->except('page'))->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection