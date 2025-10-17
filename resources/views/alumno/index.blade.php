@extends('tablar::page')

@section('title')
    Alumnos
@endsection

@section('css')
    @vite(['resources/css/pagination-responsive.css'])
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">Gestión</div>
                    <h2 class="page-title">
                        {{ __('Alumnos ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <!-- Dropdown para edición masiva (solo escritorio) -->
                        <div class="dropdown d-none d-md-inline-block">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <polyline points="3 7 12 13 21 7" />
                                </svg>
                                Edición Masiva
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route('alumnos.incrementar-semestre') }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de incrementar un semestre a TODOS los alumnos? Esta operación no se puede deshacer.')">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <line x1="12" y1="5" x2="12" y2="19"/>
                                                <line x1="5" y1="12" x2="19" y2="12"/>
                                            </svg>
                                            Incrementar Semestre (Todos)
                                        </button>
                                    </form>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item" id="btnActualizarSeleccionados" onclick="mostrarModalActualizacion()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 12l2 2l4 -4" />
                                            <circle cx="12" cy="12" r="9" />
                                        </svg>
                                        <span id="textoBotonSeleccionados">Actualizar Seleccionados (0)</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Botón completo para escritorio -->
                        <a href="{{ route('alumnos.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Registrar alumno
                        </a>
                        <!-- Botón compacto para móviles -->
                        <a href="{{ route('alumnos.create') }}" class="btn btn-primary d-sm-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span class="d-none d-xs-inline-block">Nuevo</span>
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
            
            <!-- Mostrar mensajes de éxito y error de Laravel -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9" />
                                <path d="M9 12l2 2l4 -4" />
                            </svg>
                        </div>
                        <div class="flex-fill">
                            <h4 class="alert-title">Éxito</h4>
                            <div class="text-muted">{{ session('success') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </div>
                        <div class="flex-fill">
                            <h4 class="alert-title">Error</h4>
                            <div class="text-muted">{{ session('error') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 9v2m0 4v.01" />
                                <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                            </svg>
                        </div>
                        <div class="flex-fill">
                            <h4 class="alert-title">Advertencia</h4>
                            <div class="text-muted">{{ session('warning') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Mostrar mensajes informativos de búsqueda -->
            @if(isset($message) && isset($messageType))
                <div class="alert alert-{{ $messageType }} alert-dismissible fade show mb-3" role="alert">
                    <div class="d-flex">
                        <div>
                            @if($messageType == 'info')
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9" />
                                    <line x1="12" y1="8" x2="12.01" y2="8" />
                                    <polyline points="11 12 12 12 12 16 13 16" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 9v2m0 4v.01" />
                                    <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-fill">
                            <h4 class="alert-title">
                                @if($messageType == 'info') Información @else Advertencia @endif
                            </h4>
                            <div class="text-muted">{{ $message }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Mostrar errores de validación -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </div>
                        <div class="flex-fill">
                            <h4 class="alert-title">Errores de validación</h4>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card" style="min-height: 700px;">
                        <div class="card-header">
                            <h3 class="card-title">Alumnos</h3>
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
                            <form method="GET" action="{{ route('alumnos.index') }}" id="desktopFilterForm">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="text-muted me-3 mb-2" style="flex-grow: 2;">
                                        <label for="grupo" class="form-label">Grupos</label>
                                        <div class="d-inline-block" style="width: 250px;">
                                            <select class="form-control form-control-md" name="grupo" id="grupo">
                                                <option value="">Todos los grupos</option>
                                                @foreach($grupos as $grupo)
                                                    <option value="{{ $grupo }}" {{ request('grupo') == $grupo ? 'selected' : '' }}>{{ $grupo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-muted me-3 mb-2" style="flex-grow: 2;">
                                        <label for="especialidad" class="form-label">Especialidad</label>
                                        <div class="d-inline-block" style="width: 250px;">
                                            <select class="form-control form-control-md" name="especialidad" id="especialidad">
                                                <option value="">Todas las especialidades</option>
                                                @foreach($especialidadesDB as $especialidad)
                                                    <option value="{{ $especialidad->nombre }}" {{ request('especialidad') == $especialidad->nombre ? 'selected' : '' }}>
                                                        {{ $especialidad->nombre }}
                                                    </option>
                                                @endforeach
                                                <option value="No aplica" {{ request('especialidad') == 'No aplica' ? 'selected' : '' }}>
                                                    No aplica
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-muted me-3 mb-2" style="flex-grow: 1;">
                                        <label for="search" class="form-label">Buscar</label>
                                        <div class="d-inline-block" style="width: 200px;">
                                            <input type="text" class="form-control form-control-md" name="search" 
                                                   id="search" value="{{ request('search') }}" placeholder="Nombre, control, CURP..." aria-label="Search invoice">
                                        </div>
                                    </div>
                                    <div class="align-self-end mb-2">
                                        <button type="submit" class="btn btn-primary btn-md">Filtrar</button>
                                        @if(request()->hasAny(['search', 'grupo', 'especialidad']))
                                            <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary btn-md ms-2">
                                                Limpiar
                                            </a>
                                        @endif
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
                                <form method="GET" action="{{ route('alumnos.index') }}" id="mobileFilterForm">
                                    <div class="mb-3">
                                        <label for="mobile_grupo" class="form-label">Grupos</label>
                                        <select class="form-control" name="grupo" id="mobile_grupo">
                                            <option value="">Todos los grupos</option>
                                            @foreach($grupos as $grupo)
                                                <option value="{{ $grupo }}" {{ request('grupo') == $grupo ? 'selected' : '' }}>{{ $grupo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_especialidad" class="form-label">Especialidad</label>
                                        <select class="form-control" name="especialidad" id="mobile_especialidad">
                                            <option value="">Todas las especialidades</option>
                                            @foreach($especialidadesDB as $especialidad)
                                                <option value="{{ $especialidad->nombre }}" {{ request('especialidad') == $especialidad->nombre ? 'selected' : '' }}>
                                                    {{ $especialidad->nombre }}
                                                </option>
                                            @endforeach
                                            <option value="No aplica" {{ request('especialidad') == 'No aplica' ? 'selected' : '' }}>
                                                No aplica
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_search" class="form-label">Buscar</label>
                                        <input type="text" class="form-control" name="search" id="mobile_search" 
                                               value="{{ request('search') }}" placeholder="Nombre, control, CURP...">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mb-2">Aplicar Filtros</button>
                                    @if(request()->hasAny(['search', 'grupo', 'especialidad']))
                                        <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary w-100">
                                            Limpiar Filtros
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>
                        
                        <!-- Tabla responsive con altura expandida -->
                        <div class="table-responsive" style="min-height: 600px;">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="w-1">
                                        <input class="form-check-input m-0 align-middle" type="checkbox" id="selectAll" title="Seleccionar todo">
                                    </th>
                                    <th class="text-nowrap">N° Control</th>
                                    <th class="text-nowrap d-none d-lg-table-cell">CURP</th>
                                    <th class="text-nowrap d-none d-md-table-cell">Especialidad</th>
                                    <th class="text-nowrap d-none d-sm-table-cell">Sem.</th>
                                    <th class="text-nowrap d-none d-md-table-cell">Grupo</th>
                                    <th class="text-nowrap">Nombre</th>
                                    <th class="text-nowrap d-none d-lg-table-cell">Email</th>
                                    <th class="text-nowrap d-none d-sm-table-cell">Estatus</th>
                                    <th class="w-1 text-nowrap">Acciones</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($alumnos as $alumno)
                                    <tr>
                                        <!-- Checkbox para selección -->
                                        <td>
                                            <input class="form-check-input m-0 align-middle alumno-checkbox" type="checkbox" 
                                                   value="{{ $alumno->id }}" name="selected_alumnos[]" 
                                                   data-numero-control="{{ $alumno->numero_control }}"
                                                   data-nombre="{{ $alumno->Nombre }}">
                                        </td>
                                        <!-- Número de control - siempre visible -->
                                        <td>
                                            <div class="fw-bold">{{ $alumno->numero_control }}</div>
                                            <!-- Información adicional visible solo en móviles -->
                                            <div class="d-sm-none">
                                                <small class="text-muted d-block">{{ $alumno->especialidad }}</small>
                                                <small class="text-muted d-block">Sem: {{ $alumno->semestre }} | {{ $alumno->Grupo }}</small>
                                                <small class="text-muted d-block d-lg-none">{{ $alumno->email }}</small>
                                            </div>
                                        </td>
                                        <!-- CURP - oculta en tablets y móviles -->
                                        <td class="d-none d-lg-table-cell">
                                            <span class="text-muted">{{ $alumno->CURP }}</span>
                                        </td>
                                        <!-- Especialidad - oculta en móviles -->
                                        <td class="d-none d-md-table-cell">
                                            <span>{{ $alumno->especialidad }}</span>
                                        </td>
                                        <!-- Semestre - oculta en móviles muy pequeños -->
                                        <td class="d-none d-sm-table-cell">
                                            <span>{{ $alumno->semestre }}</span>
                                        </td>
                                        <!-- Grupo - oculta en móviles -->
                                        <td class="d-none d-md-table-cell">
                                            <span class="fw-bold">{{ $alumno->Grupo }}</span>
                                        </td>
                                        <!-- Nombre - siempre visible -->
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $alumno->Nombre }}">
                                                {{ $alumno->Nombre }}
                                            </div>
                                            <!-- Estatus visible en móviles -->
                                            <div class="d-sm-none">
                                                <span class="mt-1">
                                                    {{ ucfirst($alumno->estatus) }}
                                                </span>
                                            </div>
                                        </td>
                                        <!-- Email - oculta en tablets y móviles -->
                                        <td class="d-none d-lg-table-cell">
                                            <div class="text-truncate" style="max-width: 180px;" title="{{ $alumno->email }}">
                                                {{ $alumno->email }}
                                            </div>
                                        </td>
                                        <!-- Estatus - oculta en móviles muy pequeños -->
                                        <td class="d-none d-sm-table-cell">
                                            <span>
                                                {{ ucfirst($alumno->estatus) }}
                                            </span>
                                        </td>
                                        <!-- Acciones - MEJORADO -->
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle align-text-top table-action-btn" 
                                                            data-bs-toggle="dropdown" aria-expanded="false" 
                                                            style="min-width: 100px; padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                                        <i class="fas fa-ellipsis-v d-md-none"></i>
                                                        <span class="d-none d-md-inline">Acciones</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end table-dropdown">
                                                        <a class="dropdown-item py-2"
                                                           href="{{ route('alumnos.show',$alumno->id) }}">
                                                            <i class="fa fa-fw fa-eye me-2"></i>
                                                            Vista general
                                                        </a>
                                                        <a class="dropdown-item py-2"
                                                           href="{{ route('alumnos.edit',$alumno->id) }}">
                                                            <i class="fa fa-fw fa-edit me-2"></i>
                                                            Editar
                                                        </a>
                                                        <form
                                                            action="{{ route('alumnos.destroy',$alumno->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item text-red py-2" onclick="confirmDelete(event)">
                                                                <i class="fa fa-fw fa-trash me-2"></i>
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
                                        <td colspan="9" class="text-center py-5">
                                            <div class="empty">
                                                <div class="empty-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <circle cx="9" cy="7" r="4"/>
                                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/>
                                                    </svg>
                                                </div>
                                                <p class="empty-title">No se encontraron alumnos</p>
                                                <p class="empty-subtitle text-muted">
                                                    No hay alumnos que coincidan con los filtros seleccionados
                                                </p>
                                                <div class="empty-action">
                                                    <a href="{{ route('alumnos.create') }}" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                                        </svg>
                                                        Registrar primer alumno
                                                    </a>
                                                    @if(request()->hasAny(['search', 'grupo', 'especialidad']))
                                                        <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary mt-2">
                                                            Limpiar filtros
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Footer responsive con paginación -->
                        <div class="card-footer">
                            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                                <!-- Mostrar información de paginación -->
                                <div class="text-muted">
                                    Mostrando {{ $alumnos->firstItem() ?? 0 }} a {{ $alumnos->lastItem() ?? 0 }} de {{ $alumnos->total() }} registros
                                </div>
                                
                                <!-- Paginación personalizada que mantiene los filtros -->
                                @if($alumnos->hasPages())
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <!-- Enlace anterior -->
                                            <li class="page-item {{ $alumnos->onFirstPage() ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ $alumnos->previousPageUrl() }}" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            
                                            <!-- Enlaces de páginas -->
                                            @foreach ($alumnos->getUrlRange(max(1, $alumnos->currentPage() - 2), min($alumnos->lastPage(), $alumnos->currentPage() + 2)) as $page => $url)
                                                <li class="page-item {{ $page == $alumnos->currentPage() ? 'active' : '' }}">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endforeach
                                            
                                            <!-- Enlace siguiente -->
                                            <li class="page-item {{ !$alumnos->hasMorePages() ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ $alumnos->nextPageUrl() }}" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para actualización selectiva -->
    <div class="modal modal-blur fade" id="modalActualizacionSelectiva" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualizar Alumnos Seleccionados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formActualizacionSelectiva" action="{{ route('alumnos.actualizacion-selectiva') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Alumnos seleccionados <span class="badge bg-primary" id="contadorSeleccionados">0</span></label>
                            <div id="listaSeleccionados" class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                <small class="text-muted">No hay alumnos seleccionados</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tipo de actualización <span class="text-danger">*</span></label>
                            <select class="form-control" name="tipo_actualizacion_selectiva" id="tipoActualizacionSelectiva" required>
                                <option value="">Seleccione el tipo de actualización</option>
                                <option value="grupo">Actualizar Grupo</option>
                                <option value="especialidad">Actualizar Especialidad</option>
                            </select>
                        </div>

                        <div id="campoGrupoSelectivo" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Nuevo grupo <span class="text-danger">*</span></label>
                                <select class="form-control" name="nuevo_grupo" id="nuevoGrupo">
                                    <option value="">Seleccione el nuevo grupo</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo }}">{{ $grupo }}</option>
                                    @endforeach
                                    <option value="_nuevo_">➕ Crear nuevo grupo...</option>
                                </select>
                                <!-- Campo de texto para nuevo grupo (oculto inicialmente) -->
                                <div id="campoNuevoGrupo" style="display: none; margin-top: 10px;">
                                    <input type="text" class="form-control" name="grupo_personalizado" id="grupoPersonalizado" 
                                           placeholder="Ingrese el nombre del nuevo grupo">
                                </div>
                            </div>
                        </div>

                        <div id="campoEspecialidadSelectivo" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Nueva especialidad <span class="text-danger">*</span></label>
                                <select class="form-control" name="nueva_especialidad" id="nuevaEspecialidad">
                                    <option value="">Seleccione la nueva especialidad</option>
                                    @foreach($especialidadesDB as $especialidad)
                                        <option value="{{ $especialidad->nombre }}">{{ $especialidad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="alumnos_seleccionados" id="alumnosSeleccionadosHidden">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar seleccionados</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
// Variables globales
let alumnosSeleccionados = [];

document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.alumno-checkbox');
    const btnActualizar = document.getElementById('btnActualizarSeleccionados');
    const textoBoton = document.getElementById('textoBotonSeleccionados');
    
    // Manejar selección de todos
    selectAll.addEventListener('change', function() {
        const isChecked = this.checked;
        checkboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        actualizarSeleccionados();
    });
    
    // Manejar selección individual
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            actualizarSeleccionados();
            // Actualizar estado del checkbox "seleccionar todo"
            const checkedCount = document.querySelectorAll('.alumno-checkbox:checked').length;
            selectAll.checked = checkedCount === checkboxes.length;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        });
    });
    
    // Modal de actualización selectiva
    const tipoSelect = document.getElementById('tipoActualizacionSelectiva');
    const campoGrupo = document.getElementById('campoGrupoSelectivo');
    const campoEspecialidad = document.getElementById('campoEspecialidadSelectivo');
    
    tipoSelect.addEventListener('change', function() {
        campoGrupo.style.display = 'none';
        campoEspecialidad.style.display = 'none';
        
        if (this.value === 'grupo') {
            campoGrupo.style.display = 'block';
        } else if (this.value === 'especialidad') {
            campoEspecialidad.style.display = 'block';
        }
    });
    
    // Manejar selección de grupo (existente o nuevo)
    const selectGrupo = document.getElementById('nuevoGrupo');
    const campoNuevoGrupo = document.getElementById('campoNuevoGrupo');
    
    selectGrupo.addEventListener('change', function() {
        if (this.value === '_nuevo_') {
            campoNuevoGrupo.style.display = 'block';
            document.getElementById('grupoPersonalizado').required = true;
        } else {
            campoNuevoGrupo.style.display = 'none';
            document.getElementById('grupoPersonalizado').required = false;
        }
    });
});

function actualizarSeleccionados() {
    const checkboxes = document.querySelectorAll('.alumno-checkbox:checked');
    const btnActualizar = document.getElementById('btnActualizarSeleccionados');
    const textoBoton = document.getElementById('textoBotonSeleccionados');
    
    alumnosSeleccionados = Array.from(checkboxes).map(checkbox => ({
        id: checkbox.value,
        numeroControl: checkbox.dataset.numeroControl,
        nombre: checkbox.dataset.nombre
    }));
    
    const count = alumnosSeleccionados.length;
    textoBoton.textContent = `Actualizar Seleccionados (${count})`;
    
    if (count > 0) {
        btnActualizar.classList.remove('disabled');
        btnActualizar.disabled = false;
    } else {
        btnActualizar.classList.add('disabled');
        btnActualizar.disabled = true;
    }
}

function mostrarModalActualizacion() {
    if (alumnosSeleccionados.length === 0) {
        alert('Debe seleccionar al menos un alumno para actualizar.');
        return;
    }
    
    // Actualizar lista de seleccionados en el modal
    const listaDiv = document.getElementById('listaSeleccionados');
    const contadorSpan = document.getElementById('contadorSeleccionados');
    
    contadorSpan.textContent = alumnosSeleccionados.length;
    
    listaDiv.innerHTML = alumnosSeleccionados.map(alumno => 
        `<div class="d-flex justify-content-between align-items-center mb-1">
            <small><strong>${alumno.numeroControl}</strong> - ${alumno.nombre}</small>
        </div>`
    ).join('');
    
    // Preparar IDs para envío
    document.getElementById('alumnosSeleccionadosHidden').value = 
        alumnosSeleccionados.map(a => a.id).join(',');
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalActualizacionSelectiva'));
    modal.show();
}

// Manejar envío del formulario selectivo
document.getElementById('formActualizacionSelectiva').addEventListener('submit', function(e) {
    if (!confirm(`¿Está seguro de actualizar ${alumnosSeleccionados.length} alumnos seleccionados? Esta operación no se puede deshacer.`)) {
        e.preventDefault();
    }
});
</script>
@endsection

@section('css')
    @vite(['resources/css/pagination-responsive.css'])
    <style>
    /* Estilos para el botón de acciones mejorado */
    .table-action-btn {
        min-width: 100px !important;
        padding: 0.375rem 0.75rem !important;
        font-size: 0.875rem !important;
    }
    
    .table-dropdown {
        min-width: 180px;
        font-size: 0.875rem !important;
    }
    
    .table-dropdown .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
    }
    
    .table-dropdown .dropdown-item i {
        width: 16px;
        margin-right: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .table-action-btn {
            min-width: 80px !important;
            padding: 0.3rem 0.6rem !important;
        }
    }
    
    /* Estilos para checkboxes */
    .alumno-checkbox:checked {
        background-color: #206bc4;
        border-color: #206bc4;
    }
    
    #selectAll:indeterminate {
        background-color: #f59f00;
        border-color: #f59f00;
    }
    </style>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, elimínalo",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });
    }
    
    // Sincronizar valores entre formularios
    document.addEventListener('DOMContentLoaded', function() {
        // Sincronizar desde formulario móvil a desktop
        document.getElementById('mobileFilterForm').addEventListener('submit', function() {
            document.getElementById('grupo').value = document.getElementById('mobile_grupo').value;
            document.getElementById('especialidad').value = document.getElementById('mobile_especialidad').value;
            document.getElementById('search').value = document.getElementById('mobile_search').value;
        });
        
        // Sincronizar desde formulario desktop a móvil (al cargar la página)
        document.getElementById('mobile_grupo').value = document.getElementById('grupo').value;
        document.getElementById('mobile_especialidad').value = document.getElementById('especialidad').value;
        document.getElementById('mobile_search').value = document.getElementById('search').value;
    });
</script>
<style>
    /* Estilos para el botón de acciones mejorado */
    .table-action-btn {
        min-width: 100px !important;
        padding: 0.375rem 0.75rem !important;
        font-size: 0.875rem !important;
    }
    
    .table-dropdown {
        min-width: 180px;
        font-size: 0.875rem !important;
    }
    
    .table-dropdown .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
    }
    
    .table-dropdown .dropdown-item i {
        width: 16px;
        margin-right: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .table-action-btn {
            min-width: 80px !important;
            padding: 0.3rem 0.6rem !important;
        }
    }
</style>
@endsection