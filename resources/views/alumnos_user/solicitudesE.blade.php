@extends('tablar::page')

@section('title')
    Mis Solicitudes de Exámenes
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item active">Mis Solicitudes</li>
                            </ol>
                        </nav>
                    </div>
                    <h2 class="page-title">
                        <i class="fas fa-clipboard-list me-2 text-primary d-none d-sm-inline"></i>
                        <i class="fas fa-clipboard-list text-primary d-sm-none"></i>
                        <span class="d-none d-md-inline">{{ __('Mis Solicitudes de Exámenes') }}</span>
                        <span class="d-md-none">{{ __('Mis Solicitudes') }}</span>
                    </h2>
                </div>
               
                <!-- Botón de filtros - Responsivo -->
                <div class="col-auto">
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
    </svg>
</button>
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
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-column flex-md-row align-items-start align-items-md-center">
                            <h3 class="card-title mb-2 mb-md-0">Mis Solicitudes</h3>
                            <!-- Indicadores responsivos -->
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <small class="text-muted d-none d-lg-inline">
                                    <i class="fas fa-info-circle me-1"></i>
                                
                                </small>
                            </div>
                        </div>
                        
                        <!-- Formulario de filtros para desktop y tablet (oculto en móviles) -->
                        <div class="card-body border-bottom py-3 d-none d-lg-block">
                            <form id="filterForm" method="GET">
                                <div class="row g-2 align-items-end">
                                    <div class="col-12 col-xl-auto">
                                        <label for="materias" class="form-label">
                                            <i class="fas fa-book me-1"></i>Materias
                                        </label>
                                       <select id="materias" name="materias" class="form-select">
    <option value="">Todas las materias</option>
    @php
        // Limpiar y formatear las materias
        $materiasLimpias = [];
        
        if (is_array($materias)) {
            foreach ($materias as $materia) {
                $materiaLimpia = trim(strip_tags($materia));
                $materiaLimpia = preg_replace('/^[\"\[\{]+|[\"\]\}]+$/', '', $materiaLimpia);
                if (!empty($materiaLimpia)) {
                    $materiasLimpias[] = $materiaLimpia;
                }
            }
        } else {
            // Si es string, separar por comas y limpiar
            $materiasArray = explode(',', $materias);
            foreach ($materiasArray as $materia) {
                $materiaLimpia = trim(strip_tags($materia));
                $materiaLimpia = preg_replace('/^[\"\[\{]+|[\"\]\}]+$/', '', $materiaLimpia);
                if (!empty($materiaLimpia)) {
                    $materiasLimpias[] = $materiaLimpia;
                }
            }
        }
        
        // Eliminar duplicados y ordenar
        $materiasLimpias = array_unique($materiasLimpias);
        sort($materiasLimpias);
    @endphp
    
    @foreach ($materiasLimpias as $materia)
        <option value="{{ $materia }}" {{ request('materias') == $materia ? 'selected' : '' }}>
            {{ $materia }}
        </option>
    @endforeach
</select>
                                    </div>
                                    <div class="col-12 col-xl-auto">
                                        <label for="tipo_pago" class="form-label">
                                            <i class="fas fa-credit-card me-1"></i>Tipo de pago
                                        </label>
                                        <select id="tipo_pago" name="tipo_pago" class="form-select">
                                            <option value="">Todos los tipos</option>
                                            <option value="recuperacion" {{ request('tipo_pago') == 'recuperacion' ? 'selected' : '' }}>Recuperación (R2)</option>
                                            <option value="regularizacion" {{ request('tipo_pago') == 'regularizacion' ? 'selected' : '' }}>Regularización</option>
                                            <option value="curso_intensivo" {{ request('tipo_pago') == 'curso_intensivo' ? 'selected' : '' }}>Curso Intensivo</option>
                                            <option value="titulo_suficiencia" {{ request('tipo_pago') == 'titulo_suficiencia' ? 'selected' : '' }}>Título Suficiencia</option>
                                            <option value="segundo_curso_intensivo" {{ request('tipo_pago') == 'segundo_curso_intensivo' ? 'selected' : '' }}>2º Curso Intensivo</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-xl-auto">
                                        <label for="fecha_pago" class="form-label">
                                            <i class="fas fa-calendar me-1"></i>Fecha de Pago
                                        </label>
                                        <input type="date" id="fecha_pago" name="fecha_pago" class="form-control" value="{{ request('fecha_pago') }}">
                                    </div>
                                    <div class="col-12 col-xl-auto">
                                        <div class="btn-group w-100">
                                            <button type="submit" id="filterButton" class="btn btn-primary">
                                                <i class="fas fa-filter me-1"></i>Filtrar
                                            </button>
                                            <button type="button" id="resetButton" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Limpiar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Offcanvas para filtros en móviles - Mejorado -->
                        <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
                            <div class="offcanvas-header border-bottom">
                                <h5 class="offcanvas-title" id="filterOffcanvasLabel">
                                    <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <form id="mobileFilterForm" method="GET">
                                    <div class="mb-3">
                                        <label for="mobile_materias" class="form-label fw-semibold">
                                            <i class="fas fa-book me-1 text-primary"></i>Materias
                                        </label>
                                        <select id="mobile_materias" name="materias" class="form-select">
                                            <option value="">Todas las materias</option>
                                            @foreach ($materiasLimpias as $materia)
                                                <option value="{{ $materia }}" {{ request('materias') == $materia ? 'selected' : '' }}>{{ $materia }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_tipo_pago" class="form-label fw-semibold">
                                            <i class="fas fa-credit-card me-1 text-primary"></i>Tipo de pago
                                        </label>
                                        <select id="mobile_tipo_pago" name="tipo_pago" class="form-select">
                                            <option value="">Todos los tipos</option>
                                            <option value="recuperacion" {{ request('tipo_pago') == 'recuperacion' ? 'selected' : '' }}>Recuperación (R2)</option>
                                            <option value="regularizacion" {{ request('tipo_pago') == 'regularizacion' ? 'selected' : '' }}>Regularización</option>
                                            <option value="curso_intensivo" {{ request('tipo_pago') == 'curso_intensivo' ? 'selected' : '' }}>Curso Intensivo</option>
                                            <option value="titulo_suficiencia" {{ request('tipo_pago') == 'titulo_suficiencia' ? 'selected' : '' }}>Título Suficiencia</option>
                                            <option value="segundo_curso_intensivo" {{ request('tipo_pago') == 'segundo_curso_intensivo' ? 'selected' : '' }}>2º Curso Intensivo</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_fecha_pago" class="form-label fw-semibold">
                                            <i class="fas fa-calendar me-1 text-primary"></i>Fecha de Pago
                                        </label>
                                        <input type="date" id="mobile_fecha_pago" name="fecha_pago" class="form-control" value="{{ request('fecha_pago') }}">
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-search me-2"></i>Aplicar Filtros
                                        </button>
                                        <button type="button" id="mobileResetButton" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>Limpiar Filtros
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Tabla sin limitación de tamaño horizontal -->
                        <div class="table-container">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="w-1 d-none d-md-table-cell">No.</th>
                                        <th class="text-nowrap">
                                            <span class="d-none d-sm-inline">Nombre del Alumno</span>
                                            <span class="d-sm-none">Alumno</span>
                                        </th>
                                        <th class="d-none d-lg-table-cell">Número de Control</th>
                                        <th class="d-none d-xl-table-cell">Especialidad</th>
                                        <th class="d-none d-xl-table-cell">Grupo</th>
                                        <th class="d-none d-sm-table-cell">
                                            <span class="d-none d-lg-inline">Tipo de Pago</span>
                                            <span class="d-lg-none">Tipo</span>
                                        </th>
                                        <th class="d-none d-lg-table-cell">Fecha de Pago</th>
                                        <th class="d-none d-sm-table-cell">Materias</th>
                                        <th>Status</th>
                                        <th class="w-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @forelse ($formularios as $formulario)
                                        @php
                                            // Lógica corregida para determinar el estado automáticamente
                                            // Estados manuales tienen prioridad
                                            if ($formulario->status == 'declinada') {
                                                $estado = 'Rechazada';
                                                $colorClase = 'bg-danger';
                                                $tooltipText =  'Solicitud rechazada, comunicarse con el departamento de servicios escolares';
                                            } 
                                            elseif ($formulario->status == 'Generando Liga de Pago') {
                                                $estado = 'Generando Liga de Pago';
                                                $colorClase = 'bg-orange';
                                                $tooltipText = 'En proceso de generar liga de pago';
                                            }
                                            // Estados automáticos basados en documentos
                                            elseif ($formulario->comprobante_oficial) {
                                                $estado = 'Finalizada';
                                                $colorClase = 'bg-success';
                                                $tooltipText = 'Proceso completado - Comprobante oficial generado';
                                            } 
                                            elseif ($formulario->comprobante) {
                                                $estado = 'Comprobante Generado';
                                                $colorClase = 'bg-info';
                                                $tooltipText = 'Comprobante del sistema generado - Proceso en revisión final';
                                            } 
                                            elseif ($formulario->comprobante_alumno) {
                                                $estado = 'Comprobante Cargado';
                                                $colorClase = 'bg-primary';
                                                $tooltipText = 'Alumno cargó comprobante - Esperando validación';
                                            } 
                                            elseif ($formulario->liga_de_pago) {
                                                $estado = 'Liga Generada';
                                                $colorClase = 'bg-warning';
                                                $tooltipText = 'Liga de pago disponible - Esperando pago del alumno';
                                            } 
                                            else {
                                                $estado = 'Pendiente';
                                                $colorClase = 'bg-secondary';
                                                $tooltipText = 'Solicitud creada - Esperando procesamiento';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="d-none d-md-table-cell">{{ $loop->iteration }}</td>
                                            <td class="text-break">
                                                <div class="d-flex flex-column">
                                                    <strong class="table-text">{{ $formulario->nombre }}</strong>
                                                    <small class="text-muted d-lg-none table-text-sm">{{ $formulario->numero_control }}</small>
                                                    <small class="text-muted d-xl-none d-lg-block table-text-sm">
                                                        {{ $formulario->especialidad }} - {{ $formulario->grupo }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="d-none d-lg-table-cell table-text">{{ $formulario->numero_control }}</td>
                                            <td class="d-none d-xl-table-cell table-text">{{ $formulario->especialidad }}</td>
                                            <td class="d-none d-xl-table-cell table-text">{{ $formulario->grupo }}</td>
                                            <td class="d-none d-sm-table-cell">
                                                <span class="badge text-white table-badge
                                                    {{ $formulario->tipo_pago == 'recuperacion' ? 'bg-blue' : 
                                                       ($formulario->tipo_pago == 'regularizacion' ? 'bg-green' : 
                                                       ($formulario->tipo_pago == 'curso_intensivo' ? 'bg-orange' : 
                                                       ($formulario->tipo_pago == 'titulo_suficiencia' ? 'bg-purple' : 
                                                       ($formulario->tipo_pago == 'segundo_curso_intensivo' ? 'bg-red' : 'bg-secondary')))) }}">
                                                    {{ $formulario->tipo_pago }}
                                                </span>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <span class="text-nowrap table-text">{{ $formulario->fecha_pago }}</span>
                                            </td>
                                            <td class="d-none d-sm-table-cell">
                                                @php
                                                    $materiasTexto = $formulario->materias;
                                                    $materiasArray = explode(', ', $materiasTexto);
                                                    $materiasCount = count(array_filter($materiasArray));
                                                @endphp
                                                @if ($materiasCount > 1)
                                                    <span data-bs-toggle="tooltip" title="{{ $materiasTexto }}" class="cursor-pointer table-text">
                                                        <span>{{ $materiasCount }} materias</span>
                                                    </span>
                                                @else
                                                    <span class="table-text">{{ $materiasTexto }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                                                    <span class="badge {{ $colorClase }} text-white table-badge" data-bs-toggle="tooltip" title="{{ $tooltipText }}">
                                                        {{ $estado }}
                                                    </span>
                                                    <!-- Información adicional para móviles -->
                                                    <div class="d-md-none mt-1">
                                                        <small class="text-muted d-block table-text-sm">{{ $formulario->fecha_pago }}</small>
                                                        <small class="text-muted d-sm-none table-text-sm">
                                                            @if ($materiasCount > 1)
                                                                {{ $materiasCount }} materias
                                                            @else
                                                                {{ $materiasTexto }}
                                                            @endif
                                                        </small>
                                                        <!-- Mostrar tipo de pago en móviles -->
                                                        <small class="text-muted d-sm-none">
                                                            <span class="badge bg-primary text-white table-badge-sm">{{ $formulario->tipo_pago }}</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary btn-sm dropdown-toggle align-text-top table-action-btn" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 100px; padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                                            <i class="fas fa-ellipsis-v d-md-none"></i>
                                                            <span class="d-none d-md-inline">Acciones</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end table-dropdown">
                                                            <h6 class="dropdown-header d-md-none py-2">{{ $formulario->nombre }}</h6>
                                                            <form action="{{ route('solicitudesE.destroy', $formulario->id) }}" method="POST" class="delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-red delete-button py-2">
                                                                    <i class="fa fa-fw fa-trash me-2"></i> Eliminar
                                                                </button>
                                                            </form>
                                                
                                                            @if($formulario->liga_de_pago)
    <div class="dropdown-divider"></div>
    <a href="{{ route('formularios.downloadLigaDePago', $formulario->id) }}" target="_blank" class="dropdown-item py-2">
        <i class="fa fa-fw fa-download me-2"></i> Descargar Liga de Pago
    </a>
    <a href="#" class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#cargarComprobanteModal{{ $formulario->id }}">
        <i class="fa fa-fw fa-upload me-2"></i> Cargar Comprobante
    </a>
@endif

@if($formulario->comprobante)
    <a href="{{ route('formularios.downloadStudentReceipt', $formulario->id) }}" target="_blank" class="dropdown-item py-2">
        <i class="fa fa-fw fa-download me-2"></i> Descargar Comprobante
    </a>
@endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Modal para cargar comprobante - Responsivo -->
                                                <div class="modal fade" id="cargarComprobanteModal{{ $formulario->id }}" tabindex="-1" aria-labelledby="cargarComprobanteModalLabel{{ $formulario->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fs-6" id="cargarComprobanteModalLabel{{ $formulario->id }}">
                                                                    <i class="fas fa-upload me-2"></i>Cargar Comprobante
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body p-3">
                                                                <p class="text-muted small mb-3">{{ $formulario->nombre }}</p>
                                                                <form action="{{ route('formularios.uploadComprobanteAlumno', $formulario->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <label for="comprobante_alumno" class="form-label">Seleccionar archivo</label>
                                                                        <input type="file" class="form-control" id="comprobante_alumno" name="comprobante_alumno" required accept=".pdf,.jpg,.jpeg,.png">
                                                                        <div class="form-text">Formatos: PDF, JPG, PNG (máx. 5MB)</div>
                                                                    </div>
                                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fas fa-upload me-1"></i>Cargar
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <div class="empty">
                                                    <div class="empty-icon">
                                                        <i class="fas fa-search fa-2x text-muted"></i>
                                                    </div>
                                                    <p class="empty-title">Sin información</p>
                                                    <p class="empty-subtitle text-muted">No se encontraron solicitudes con los filtros aplicados</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación responsiva -->
                        @if($formularios->hasPages())
                        <div class="card-footer">
                            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                                <div class="mb-2 mb-md-0">
                                    <small class="text-muted">
                                        Mostrando {{ $formularios->firstItem() }} - {{ $formularios->lastItem() }} 
                                        de {{ $formularios->total() }} resultados
                                    </small>
                                </div>
                                <div class="pagination-wrapper">
                                    {{ $formularios->links('tablar::pagination') }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ✅ ELIMINADA LA LIMITACIÓN DE TAMAÑO HORIZONTAL */
        
        /* Contenedor de tabla sin limitaciones */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        /* Tabla ocupa todo el ancho disponible */
        .table {
            width: 100% !important;
            table-layout: auto;
            min-width: 100%;
        }

        /* Columnas con tamaño adecuado */
        .table th,
        .table td {
            white-space: nowrap;
            padding: 0.75rem 0.5rem;
        }

        /* Responsividad para móviles */
        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
        
        /* Tamaños de texto consistentes para la tabla */
        .table-text {
            font-size: 0.875rem !important;
            line-height: 1.4;
        }
        
        .table-text-sm {
            font-size: 0.8rem !important;
            line-height: 1.3;
        }
        
        /* Badges con tamaño consistente */
        .table-badge {
            font-size: 0.75rem !important;
            font-weight: 500;
            padding: 0.35em 0.65em;
        }
        
        .table-badge-sm {
            font-size: 0.7rem !important;
            padding: 0.3em 0.6em;
        }
        
        /* Botón de acciones con tamaño apropiado */
        .table-action-btn {
            min-width: 100px !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 0.875rem !important;
        }
        
        /* Dropdown con tamaño adecuado */
        .table-dropdown {
            min-width: 200px;
            font-size: 0.875rem !important;
        }
        
        .table-dropdown .dropdown-item {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        /* Estilos responsivos adicionales */
        @media (max-width: 768px) {
            .dropdown-menu {
                font-size: 0.875rem !important;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .card-title {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 576px) {
            .container-xl {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .card {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
                border-radius: 0;
            }
            
            .page-title {
                font-size: 1.25rem;
            }
            
            /* Asegurar que el tipo de pago se vea en móviles */
            .d-sm-none .table-badge-sm {
                font-size: 0.65rem !important;
            }
        }
        
        /* Colores para los tipos de pago */
        .bg-blue { background-color: #206bc4 !important; }
        .bg-green { background-color: #2fb344 !important; }
        .bg-orange { background-color: #f76707 !important; }
        .bg-purple { background-color: #ae3ec9 !important; }
        .bg-red { background-color: #d63939 !important; }
        
        /* Mejoras para la tabla */
        .table thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            background-color: #f8f9fa;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Animación para tooltips */
        .cursor-pointer {
            cursor: pointer;
        }
        
        /* Offcanvas mejorado */
        .offcanvas-body {
            overflow-y: auto;
        }
        
        /* Estados de loading */
        .btn-loading::after {
            content: '';
            display: inline-block;
            width: 0.75rem;
            height: 0.75rem;
            margin-left: 0.5rem;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive pagination */
        @media (max-width: 576px) {
            .pagination {
                justify-content: center;
                font-size: 0.875rem;
            }
            
            .pagination .page-link {
                padding: 0.375rem 0.5rem;
            }
        }
        
        /* Prevenir scroll horizontal innecesario en dropdowns */
        .dropdown-menu {
            max-height: 80vh;
            overflow-y: auto;
        }
        
        /* Mejorar la visualización en dispositivos pequeños */
        @media (max-width: 400px) {
            .table-action-btn {
                min-width: 80px;
                padding: 0.3rem 0.6rem !important;
            }
            
            .table-dropdown {
                min-width: 180px;
            }
        }
    </style>
    <script>
        $(document).ready(function() {
            // Inicializar tooltips
            function initTooltips() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover focus',
                        placement: 'auto'
                    });
                });
            }
            
            initTooltips();
            
            // Detectar dispositivo móvil
            function isMobile() {
                return window.innerWidth < 768;
            }
            
            // Ajustar comportamiento según dispositivo
            function adjustForDevice() {
                if (isMobile()) {
                    // En móviles, hacer más accesible el scroll horizontal
                    $('.table-container').on('scroll', function() {
                        const scrollLeft = $(this).scrollLeft();
                        const scrollWidth = $(this)[0].scrollWidth;
                        const clientWidth = $(this)[0].clientWidth;
                        
                        // Indicador visual de scroll
                        if (scrollLeft > 0) {
                            $(this).addClass('scrolled-left');
                        } else {
                            $(this).removeClass('scrolled-left');
                        }
                        
                        if (scrollLeft < scrollWidth - clientWidth - 10) {
                            $(this).addClass('can-scroll-right');
                        } else {
                            $(this).removeClass('can-scroll-right');
                        }
                    });
                }
            }
            
            // Sincronizar valores entre formularios
            function syncFilterValues() {
                $('#mobile_materias').val($('#materias').val());
                $('#mobile_tipo_pago').val($('#tipo_pago').val());
                $('#mobile_fecha_pago').val($('#fecha_pago').val());
            }
            
            // Sincronizar al cargar la página
            syncFilterValues();
            adjustForDevice();
            
            // Reajustar en cambio de tamaño de ventana
            $(window).on('resize', function() {
                adjustForDevice();
            });
            
            // Sincronizar en ambos sentidos
            $('#materias, #tipo_pago, #fecha_pago').on('change input', function() {
                const id = $(this).attr('id');
                $('#mobile_' + id).val($(this).val());
            });
            
            $('#mobile_materias, #mobile_tipo_pago, #mobile_fecha_pago').on('change input', function() {
                const id = $(this).attr('id').replace('mobile_', '');
                $('#' + id).val($(this).val());
            });
            
            // Función para obtener datos filtrados con loading
            function fetchFilteredData(url, data, showAll = false, button = null) {
                if (showAll) {
                    data = {}; // Limpiar datos para obtener todos los registros
                }
                
                // Mostrar estado de loading
                if (button) {
                    button.addClass('btn-loading').prop('disabled', true);
                }
                
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        var newTableBody = $(response).find('#tableBody').html();
                        $('#tableBody').html(newTableBody);
                        
                        // Reinicializar tooltips después de actualizar la tabla
                        initTooltips();
                        
                        // Cerrar el offcanvas en móviles después de filtrar
                        var offcanvas = document.getElementById('filterOffcanvas');
                        var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas);
                        if (bsOffcanvas) {
                            bsOffcanvas.hide();
                        }
                        
                        // Mostrar mensaje de éxito sutil
                        if (isMobile()) {
                            const toast = `
                                <div class="toast align-items-center text-white bg-success border-0 position-fixed top-0 start-50 translate-middle-x" role="alert" style="z-index: 9999; margin-top: 1rem;">
                                    <div class="d-flex">
                                        <div class="toast-body">
                                            <i class="fas fa-check me-2"></i>Filtros aplicados
                                        </div>
                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                                    </div>
                                </div>
                            `;
                            $('body').append(toast);
                            $('.toast').toast({ delay: 2000 }).toast('show').on('hidden.bs.toast', function() {
                                $(this).remove();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: ' + error);
                        if (isMobile()) {
                            const errorToast = `
                                <div class="toast align-items-center text-white bg-danger border-0 position-fixed top-0 start-50 translate-middle-x" role="alert" style="z-index: 9999; margin-top: 1rem;">
                                    <div class="d-flex">
                                        <div class="toast-body">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Error al cargar datos
                                        </div>
                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                                    </div>
                                </div>
                            `;
                            $('body').append(errorToast);
                            $('.toast').toast({ delay: 3000 }).toast('show').on('hidden.bs.toast', function() {
                                $(this).remove();
                            });
                        }
                    },
                    complete: function() {
                        // Quitar estado de loading
                        if (button) {
                            button.removeClass('btn-loading').prop('disabled', false);
                        }
                    }
                });
            }

            // Filtrar utilizando AJAX (formulario desktop)
            $('#filterForm').on('submit', function(event) {
                event.preventDefault();
                var data = $(this).serialize();
                var button = $('#filterButton');
                fetchFilteredData('{{ route("solicitudesE.index") }}', data, false, button);
            });
            
            // Filtrar utilizando AJAX (formulario móvil)
            $('#mobileFilterForm').on('submit', function(event) {
                event.preventDefault();
                var data = $(this).serialize();
                var button = $(this).find('button[type="submit"]');
                fetchFilteredData('{{ route("solicitudesE.index") }}', data, false, button);
            });
            
            // Mostrar todos los registros (botón desktop)
            $('#resetButton').on('click', function(event) {
                event.preventDefault();
                $('#filterForm').trigger('reset');
                $('#mobileFilterForm').trigger('reset');
                fetchFilteredData('{{ route("solicitudesE.index") }}', {}, true, $(this));
            });
            
            // Mostrar todos los registros (botón móvil)
            $('#mobileResetButton').on('click', function(event) {
                event.preventDefault();
                $('#filterForm').trigger('reset');
                $('#mobileFilterForm').trigger('reset');
                fetchFilteredData('{{ route("solicitudesE.index") }}', {}, true, $(this));
            });
            
            // Confirmación para eliminar con el mensaje específico
            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                    }
                });
            });
            
            // Mejora para modales en dispositivos móviles
            $('.modal').on('show.bs.modal', function() {
                if (isMobile()) {
                    $('body').addClass('modal-open-mobile');
                }
            }).on('hide.bs.modal', function() {
                $('body').removeClass('modal-open-mobile');
            });
            
            // Prevenir zoom accidental en inputs de fecha en iOS
            if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                $('input[type="date"]').attr('readonly', true).on('focus', function() {
                    $(this).removeAttr('readonly');
                }).on('blur', function() {
                    $(this).attr('readonly', true);
                });
            }
        });
    </script>
@endsection