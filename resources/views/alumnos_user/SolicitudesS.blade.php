@extends('tablar::page')

@section('title')
    Solicitudes de Servicios
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
                                <li class="breadcrumb-item active">Solicitudes</li>
                            </ol>
                        </nav>
                    </div>
                    <h2 class="page-title">
                        <i class="fas fa-file-alt me-2 text-primary d-none d-sm-inline"></i>
                        <i class="fas fa-file-alt text-primary d-sm-none"></i>
                        <span class="d-none d-md-inline">{{ __('Solicitudes de Servicios') }}</span>
                        <span class="d-md-none">{{ __('Servicios') }}</span>
                    </h2>
                </div>
                <!-- Botón de filtros - Responsivo -->
                <div class="col-auto">
                    <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                        <i class="fas fa-filter me-1 d-sm-inline d-none"></i>
                        <i class="fas fa-filter d-sm-none"></i>
                        <span class="d-none d-sm-inline">Filtros</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-column flex-md-row align-items-start align-items-md-center">
                            <h3 class="card-title mb-2 mb-md-0">Solicitudes</h3>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <small class="text-muted d-none d-lg-inline">
                                    <i class="fas fa-info-circle me-1"></i>
                                </small>
                            </div>
                        </div>
                        
                        <!-- Formulario de filtros para desktop y tablet -->
                        <div class="card-body border-bottom py-3 d-none d-lg-block">
                            <form id="filterForm" method="GET">
                                <div class="row g-2 align-items-end">
                                    <div class="col-12 col-xl-auto">
                                        <label for="tipo_servicio" class="form-label">
                                            <i class="fas fa-cogs me-1"></i>Tipo de Servicio
                                        </label>
                                        <select id="tipo_servicio" name="tipo_servicio" class="form-select">
                                            <option value="">Todos los servicios</option>
                                            <option value="Constancia de Inscripción y/o Estudios" {{ request('tipo_servicio') == 'Constancia de Inscripción y/o Estudios' ? 'selected' : '' }}>📄 Constancia de Inscripción y/o Estudios</option>
                                            <option value="Duplicado de Credencial" {{ request('tipo_servicio') == 'Duplicado de Credencial' ? 'selected' : '' }}>🆔 Duplicado de Credencial</option>
                                            <option value="Certificado Incompleto (Parcial)" {{ request('tipo_servicio') == 'Certificado Incompleto (Parcial)' ? 'selected' : '' }}>📋 Certificado Incompleto (Parcial)</option>
                                            <option value="Duplicado de Certificado de Estudios" {{ request('tipo_servicio') == 'Duplicado de Certificado de Estudios' ? 'selected' : '' }}>📜 Duplicado de Certificado de Estudios</option>
                                            <option value="Examen de Titulación (Protocolo)" {{ request('tipo_servicio') == 'Examen de Titulación (Protocolo)' ? 'selected' : '' }}>🎓 Examen de Titulación (Protocolo)</option>
                                            <option value="Titulación (Tit. y Exp. de Ced. Prof.)" {{ request('tipo_servicio') == 'Titulación (Tit. y Exp. de Ced. Prof.)' ? 'selected' : '' }}>🏆 Titulación (Tit. y Exp. de Ced. Prof.)</option>
                                            <option value="reinscripcion" {{ request('tipo_servicio') == 'reinscripcion' ? 'selected' : '' }}>📚 Reinscripción</option>
                                        </select>
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
                        
                        <!-- Offcanvas para filtros en móviles -->
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
                                        <label for="mobile_tipo_servicio" class="form-label fw-semibold">
                                            <i class="fas fa-cogs me-1 text-primary"></i>Tipo de Servicio
                                        </label>
                                        <select id="mobile_tipo_servicio" name="tipo_servicio" class="form-select">
                                            <option value="">Todos los servicios</option>
                                            <option value="Constancia de Inscripción y/o Estudios" {{ request('tipo_servicio') == 'Constancia de Inscripción y/o Estudios' ? 'selected' : '' }}>📄 Constancia de Inscripción y/o Estudios</option>
                                            <option value="Duplicado de Credencial" {{ request('tipo_servicio') == 'Duplicado de Credencial' ? 'selected' : '' }}>🆔 Duplicado de Credencial</option>
                                            <option value="Certificado Incompleto (Parcial)" {{ request('tipo_servicio') == 'Certificado Incompleto (Parcial)' ? 'selected' : '' }}>📋 Certificado Incompleto (Parcial)</option>
                                            <option value="Duplicado de Certificado de Estudios" {{ request('tipo_servicio') == 'Duplicado de Certificado de Estudios' ? 'selected' : '' }}>📜 Duplicado de Certificado de Estudios</option>
                                            <option value="Examen de Titulación (Protocolo)" {{ request('tipo_servicio') == 'Examen de Titulación (Protocolo)' ? 'selected' : '' }}>🎓 Examen de Titulación (Protocolo)</option>
                                            <option value="Titulación (Tit. y Exp. de Ced. Prof.)" {{ request('tipo_servicio') == 'Titulación (Tit. y Exp. de Ced. Prof.)' ? 'selected' : '' }}>🏆 Titulación (Tit. y Exp. de Ced. Prof.)</option>
                                            <option value="reinscripcion" {{ request('tipo_servicio') == 'reinscripcion' ? 'selected' : '' }}>📚 Reinscripción</option>
                                        </select>
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

                        <!-- Table Container Personalizado -->
                        <div class="table-container">
                            <!-- Indicador de scroll horizontal para móviles -->
                            <div class="scroll-indicator d-lg-none">
                                <i class="fas fa-arrows-alt-h me-1"></i>
                                Desliza horizontalmente para ver todas las columnas
                            </div>
                            
                            <table class="table card-table table-vcenter text-nowrap">
                                <thead class="table-light sticky-header">
                                    <tr>
                                        <th class="w-1 d-none d-md-table-cell">No.</th>
                                        <th class="text-nowrap">
                                            <span class="d-none d-sm-inline">Nombre del Alumno</span>
                                            <span class="d-sm-none">Alumno</span>
                                        </th>
                                        <th class="d-none d-lg-table-cell">Número de Control</th>
                                        <th class="d-none d-xl-table-cell">Especialidad</th>
                                        <th class="d-none d-xl-table-cell">Grupo</th>
                                        <th class="d-none d-md-table-cell">
                                            <span class="d-none d-lg-inline">Tipo de Servicio</span>
                                            <span class="d-lg-none">Servicio</span>
                                        </th>
                                        <th class="d-none d-lg-table-cell">Fecha de Solicitud</th>
                                        <th>Status</th>
                                        <th class="w-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($formularios as $formulario)
                                    @if($formulario->alumno_id === Auth::user()->id)
                                        @php
                                            // Lógica corregida para determinar el estado automáticamente
                                            $tieneLiga = !empty(trim($formulario->liga_de_pago ?? ''));
                                            $tieneCompAlumno = !empty(trim($formulario->comprobante_alumno ?? ''));
                                            $tieneCompOficial = !empty(trim($formulario->comprobante_oficial ?? ''));
                                            
                                            // Estados manuales tienen prioridad
                                            if ($formulario->status == 'declinada') {
                                                $estado = 'Rechazada';
                                                $colorClase = 'bg-danger text-white';
                                                $tooltipText = 'Solicitud rechazada.';
                                            } 
                                            // "generando_liga_pago" solo se muestra si NO hay documentos
                                            elseif ($formulario->status == 'generando_liga_pago' && !$tieneLiga && !$tieneCompAlumno && !$tieneCompOficial) {
                                                $estado = 'Generando Liga de Pago';
                                                $colorClase = 'bg-orange text-white';
                                                $tooltipText = 'En proceso de generar liga de pago';
                                            }
                                            // Estados automáticos basados en documentos
                                            elseif ($tieneCompOficial) {
                                                $estado = 'Finalizada';
                                                $colorClase = 'bg-success text-white';
                                                $tooltipText = 'Proceso completado - Comprobante oficial generado';
                                            }
                                            elseif ($tieneCompAlumno) {
                                                $estado = 'Comprobante Cargado';
                                                $colorClase = 'bg-primary text-white';
                                                $tooltipText = 'Alumno cargó comprobante - Esperando comprobante oficial';
                                            }
                                            elseif ($tieneLiga) {
                                                $estado = 'Liga Generada';
                                                $colorClase = 'bg-warning text-dark';
                                                $tooltipText = 'Liga de pago disponible - Esperando comprobante del alumno';
                                            }
                                            // Solo si no hay documentos y el status es generando_liga_pago
                                            elseif ($formulario->status == 'generando_liga_pago') {
                                                $estado = 'Generando Liga de Pago';
                                                $colorClase = 'bg-orange text-white';
                                                $tooltipText = 'En proceso de generar liga de pago';
                                            }
                                            else {
                                                $estado = 'Pendiente';
                                                $colorClase = 'bg-secondary text-white';
                                                $tooltipText = 'Solicitud creada - Esperando liga de pago';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="d-none d-md-table-cell">{{ $loop->iteration }}</td>
                                            <td class="text-break">
                                                <div class="d-flex flex-column">
                                                    <strong class="text-body">{{ $formulario->nombre }}</strong>
                                                    <small class="text-muted d-lg-none">{{ $formulario->control }}</small>
                                                    <small class="text-muted d-xl-none d-lg-block">
                                                        {{ $formulario->especialidad }} - {{ $formulario->grupo }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-body">{{ $formulario->control }}</td>
                                            <td class="d-none d-xl-table-cell text-body">{{ $formulario->especialidad }}</td>
                                            <td class="d-none d-xl-table-cell text-body">{{ $formulario->grupo }}</td>
                                            <td class="d-none d-md-table-cell">
                                                <span class="badge badge-outline text-body">{{ $formulario->tipo_servicio }}</span>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <span class="text-nowrap text-body">{{ $formulario->fecha }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center" data-bs-toggle="tooltip" title="{{ $tooltipText }}">
                                                    <span class="badge {{ $colorClase }}">{{ $estado }}</span>
                                                    
                                                    <!-- Información adicional para móviles -->
                                                    <div class="d-md-none mt-1">
                                                        <small class="text-muted d-block">{{ $formulario->fecha }}</small>
                                                        <small class="text-muted d-sm-none d-block">{{ $formulario->tipo_servicio }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary btn-sm dropdown-toggle align-text-top" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v d-md-none"></i>
                                                            <span class="d-none d-md-inline">Acciones</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <h6 class="dropdown-header d-md-none">{{ $formulario->nombre }}</h6>
                                                            <form action="{{ route('formulario.destroy', $formulario->id) }}" method="POST" class="delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-red delete-button">
                                                                    <i class="fa fa-fw fa-trash"></i> Eliminar
                                                                </button>
                                                            </form>
                                                            @if ($formulario->comentario)
                                                                <div class="dropdown-divider"></div>
                                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#comentarioModal{{ $formulario->id }}">
                                                                    <i class="fa fa-fw fa-eye"></i> Ver Comentario
                                                                </button>
                                                            @endif
                                                            @if ($formulario->liga_de_pago)
                                                                <div class="dropdown-divider"></div>
                                                                <a href="{{ route('formularios.downloadLigaPagoFormularios', $formulario->id) }}" target="_blank" class="dropdown-item">
    <i class="fa fa-fw fa-download"></i> Descargar Liga de Pago
</a>
                                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#cargarComprobanteModal{{ $formulario->id }}">
                                                                    <i class="fa fa-fw fa-upload"></i> Cargar Comprobante de pago
                                                                </button>
                                                            @endif
                                                            @if ($formulario->comprobante)
                                                                <a href="{{ route('formularios.downloadComprobante', $formulario->id) }}" target="_blank" class="dropdown-item">
                                                                    <i class="fa fa-fw fa-download"></i> Descargar Comprobante Oficial
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="empty">
                                                    <div class="empty-icon">
                                                        <i class="fas fa-search fa-2x text-muted"></i>
                                                    </div>
                                                    <p class="empty-title text-body">Sin información</p>
                                                    <p class="empty-subtitle text-muted">No se encontraron solicitudes con los filtros aplicados</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
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

    <!-- Modal para mostrar el comentario -->
    @foreach ($formularios as $formulario)
        @if ($formulario->comentario)
            <div class="modal fade" id="comentarioModal{{ $formulario->id }}" tabindex="-1" aria-labelledby="comentarioModalLabel{{ $formulario->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fs-6" id="comentarioModalLabel{{ $formulario->id }}">
                                <i class="fas fa-comment me-2"></i>Comentario
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small mb-2">{{ $formulario->nombre }}</p>
                            <p class="text-body">{{ $formulario->comentario }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Modal para cargar comprobante -->
    @foreach ($formularios as $formulario)
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
                        <form action="{{ route('formularios.subirComprobanteAlumno', $formulario->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="comprobante_alumno{{ $formulario->id }}" class="form-label text-body">Seleccionar archivo</label>
                                <input type="file" class="form-control" id="comprobante_alumno{{ $formulario->id }}" name="comprobante_alumno" required accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text text-body">Formatos: PDF, JPG, PNG (máx. 5MB)</div>
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
    @endforeach
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Table Container Personalizado */
    .table-container {
        position: relative;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        background: var(--tblr-bg-surface);
    }

    .table-container::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-container::-webkit-scrollbar-track {
        background: var(--tblr-border-color);
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb {
        background: var(--tblr-primary);
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb:hover {
        background: var(--tblr-primary-dark);
    }

    /* Indicador de scroll */
    .scroll-indicator {
        background: var(--tblr-primary);
        color: white;
        padding: 8px 12px;
        text-align: center;
        font-size: 0.875rem;
        border-bottom: 1px solid var(--tblr-border-color);
    }

    /* Header sticky */
    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background: var(--tblr-bg-surface);
    }

    /* Botón de acciones mejorado */
    .btn-primary {
        background-color: var(--tblr-primary);
        border-color: var(--tblr-primary);
        color: white !important;
    }

    .btn-primary:hover {
        background-color: var(--tblr-primary-dark);
        border-color: var(--tblr-primary-dark);
    }

    /* Textos que respetan el tema */
    .text-body {
        color: var(--tblr-body-color) !important;
    }

    /* Ajustes responsivos */
    @media (max-width: 768px) {
        .table-container {
            font-size: 0.875rem;
        }
        
        .dropdown-menu {
            font-size: 0.875rem;
        }
        
        .badge {
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .table-container {
            font-size: 0.8rem;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
        }
        
        .dropdown-menu {
            font-size: 0.8rem;
            min-width: 180px;
        }
    }

    /* Colores para badges */
    .bg-orange {
        background-color: #f97316 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sincronización de filtros
    const desktopSelect = document.getElementById('tipo_servicio');
    const mobileSelect = document.getElementById('mobile_tipo_servicio');
    
    if (desktopSelect && mobileSelect) {
        desktopSelect.addEventListener('change', function() {
            mobileSelect.value = this.value;
        });
        
        mobileSelect.addEventListener('change', function() {
            desktopSelect.value = this.value;
        });
    }
    
    // Botones de reset
    const resetButton = document.getElementById('resetButton');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (desktopSelect) desktopSelect.value = '';
            if (mobileSelect) mobileSelect.value = '';
            document.getElementById('filterForm').submit();
        });
    }
    
    const mobileResetButton = document.getElementById('mobileResetButton');
    if (mobileResetButton) {
        mobileResetButton.addEventListener('click', function() {
            if (desktopSelect) desktopSelect.value = '';
            if (mobileSelect) mobileSelect.value = '';
            const offcanvasElement = document.getElementById('filterOffcanvas');
            const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
            if (offcanvas) offcanvas.hide();
            setTimeout(() => {
                document.getElementById('mobileFilterForm').submit();
            }, 300);
        });
    }
    
    // Confirmación para eliminación
    document.querySelectorAll('.delete-button').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closest('form').submit();
                }
            });
        });
    });
});
</script>
@endsection