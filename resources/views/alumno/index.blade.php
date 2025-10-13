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
@endsection

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