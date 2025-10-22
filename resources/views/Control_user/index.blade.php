@extends('tablar::page')

@section('title')
    Nuevas solicitudes de Exámenes
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Gestión de solicitudes
                    </div>
                    <h2 class="page-title">
                        {{ __('Nuevas solicitudes de Servicios de Exámenes') }}
                    </h2>
                </div>
                <!-- Botón de hamburguesa para filtros en móviles -->
                <div class="col-auto d-lg-none">
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
                            <div class="text-white">{{ session('success') }}</div>
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
                            <div class="text-white">{{ session('error') }}</div>
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
                            <h4 class="alert-title text-white">
                                @if($messageType == 'info') Información @else Advertencia @endif
                            </h4>
                            <div class="text-white">{{ $message }}</div>
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
                            <h4 class="alert-title text-white">Errores de validación</h4>
                            <ul class="mb-0 text-white">
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
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Solicitudes</h3>
                            <div>
                                <a href="{{ route('control_documentos.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="ti ti-files"></i> Revisar Documentos
                                </a>
                            </div>
                        </div>
                        
                        <!-- Filtros para desktop -->
                        <div class="card-body border-bottom py-3 d-none d-lg-block">
                            <form method="GET" action="{{ route('control_user.index') }}">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <label class="form-label">Especialidad:</label>
                                        <select class="form-select" name="especialidad">
                                            <option value="">Todas las especialidades</option>
                                            @foreach ($especialidadesDB as $especialidad)
                                                <option value="{{ $especialidad->nombre }}" {{ request('especialidad') == $especialidad->nombre ? 'selected' : '' }}>
                                                    {{ $especialidad->nombre }}
                                                </option>
                                            @endforeach
                                            <option value="No aplica" {{ request('especialidad') == 'No aplica' ? 'selected' : '' }}>
                                                No aplica
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <label class="form-label">Grupo:</label>
                                        <select class="form-select" name="grupo">
                                            <option value="">Todos los grupos</option>
                                            @foreach($grupos as $grupo)
                                                <option value="{{ $grupo }}" {{ request('grupo') == $grupo ? 'selected' : '' }}>{{ $grupo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <label class="form-label">Tipo de Pago:</label>
                                        <select class="form-select" name="tipo_pago">
                                            <option value="">Todos los tipos</option>
                                            @foreach($tipo_pagos as $tipo_pago)
                                                <option value="{{ $tipo_pago }}" {{ request('tipo_pago') == $tipo_pago ? 'selected' : '' }}>{{ $tipo_pago }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <label class="form-label">Buscar:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Nombre, número de control, CURP..." aria-label="Search">
                                            <button type="submit" class="btn btn-primary" title="Buscar solicitudes">
                                                <i class="fas fa-search me-1"></i>
                                                <span>Buscar</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if(request()->hasAny(['search', 'especialidad', 'grupo', 'tipo_pago']))
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <a href="{{ route('control_user.index') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-times me-1"></i>Limpiar Filtros
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </form>
                        </div>
                        
                        <!-- Offcanvas para filtros en móviles -->
                        <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="filterOffcanvas" 
                             aria-labelledby="filterOffcanvasLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="filterOffcanvasLabel">Filtros de Búsqueda</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" 
                                        aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <form method="GET" action="{{ route('control_user.index') }}" id="mobileFilterForm">
                                    <div class="mb-3">
                                        <label for="mobile_especialidad" class="form-label">Especialidad</label>
                                        <select class="form-select" name="especialidad" id="mobile_especialidad">
                                            <option value="">Todas las especialidades</option>
                                            @foreach ($especialidadesDB as $especialidad)
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
                                        <label for="mobile_grupo" class="form-label">Grupo</label>
                                        <select class="form-select" name="grupo" id="mobile_grupo">
                                            <option value="">Todos los grupos</option>
                                            @foreach($grupos as $grupo)
                                                <option value="{{ $grupo }}" {{ request('grupo') == $grupo ? 'selected' : '' }}>{{ $grupo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_tipo_pago" class="form-label">Tipo de Pago</label>
                                        <select class="form-select" name="tipo_pago" id="mobile_tipo_pago">
                                            <option value="">Todos los tipos</option>
                                            @foreach($tipo_pagos as $tipo_pago)
                                                <option value="{{ $tipo_pago }}" {{ request('tipo_pago') == $tipo_pago ? 'selected' : '' }}>{{ $tipo_pago }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_search" class="form-label">Buscar</label>
                                        <input type="text" class="form-control" name="search" id="mobile_search" 
                                               value="{{ request('search') }}" placeholder="Nombre, número de control, CURP...">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        <i class="fas fa-search me-2"></i>Aplicar Filtros
                                    </button>
                                    @if(request()->hasAny(['search', 'especialidad', 'grupo', 'tipo_pago']))
                                        <a href="{{ route('control_user.index') }}" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-times me-2"></i>Limpiar Filtros
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>
                        
                        <!-- Tabla Responsiva -->
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter text-nowrap">
                                <thead class="table-light">
                                <tr>
                                    <th class="w-1">No.</th>
                                    <th>Nombre del Alumno</th>
                                    <th class="d-none d-md-table-cell">Número de Control</th>
                                    <th class="d-none d-lg-table-cell">Especialidad</th>
                                    <th class="d-none d-lg-table-cell">Grupo</th>
                                    <th class="d-none d-xl-table-cell">Tipo de Pago</th>
                                    <th class="d-none d-xl-table-cell">Fecha de Pago</th>
                                    <th class="d-none d-md-table-cell">Materias</th>
                                    <th>Status</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($formularios as $formulario)
                                   @php
    // Verificación robusta de documentos
    $tieneLiga = !empty(trim($formulario->liga_de_pago ?? ''));
    $tieneCompAlumno = !empty(trim($formulario->comprobante_alumno ?? ''));
    $tieneCompOficial = !empty(trim($formulario->comprobante_oficial ?? ''));
    
    // SOLUCIÓN: Solo estados manuales irreversibles tienen prioridad
    if ($formulario->status == 'declinada') {
        $estado = 'Rechazada';
        $colorClase = 'bg-danger';
        $tooltipText = 'Solicitud rechazada.';
    } 
    // "generando_liga_pago" solo se muestra si NO hay documentos
    elseif ($formulario->status == 'generando_liga_pago' && !$tieneLiga && !$tieneCompAlumno && !$tieneCompOficial) {
        $estado = 'Generando Liga de Pago';
        $colorClase = 'bg-orange';
        $tooltipText = 'En proceso de generar liga de pago';
    }
    // Estados automáticos basados en documentos (tienen prioridad sobre generando_liga_pago cuando hay documentos)
    elseif ($tieneCompOficial) {
        $estado = 'Finalizada';
        $colorClase = 'bg-success';
        $tooltipText = 'Proceso completado - Comprobante oficial generado';
    }
    elseif ($tieneCompAlumno) {
        $estado = 'Comprobante Cargado';
        $colorClase = 'bg-primary';
        $tooltipText = 'Alumno cargó comprobante - Esperando comprobante oficial';
    }
    elseif ($tieneLiga) {
        $estado = 'Liga Generada';
        $colorClase = 'bg-warning';
        $tooltipText = 'Liga de pago disponible - Esperando comprobante del alumno';
    }
    // Solo si no hay documentos y el status es generando_liga_pago
    elseif ($formulario->status == 'generando_liga_pago') {
        $estado = 'Generando Liga de Pago';
        $colorClase = 'bg-orange';
        $tooltipText = 'En proceso de generar liga de pago';
    }
    else {
        $estado = 'Pendiente';
        $colorClase = 'bg-secondary';
        $tooltipText = 'Solicitud creada - Esperando liga de pago';
    }
@endphp
                                    <tr>
                                        <td><span class="badge bg-primary text-white">{{ $loop->iteration + ($formularios->currentPage() - 1) * $formularios->perPage() }}</span></td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <!-- NOMBRE DEL ALUMNO -->
                                                <span class="fw-bold texto-visible">{{ $formulario->nombre }}</span>
                                                <!-- Información adicional visible en móvil - SIN FONDO BLANCO -->
                                                <div class="d-md-none mt-2">
                                                    <div class="informacion-movil">
                                                        <small class="d-block mb-1">
                                                            <strong class="texto-visible">Control:</strong> 
                                                            <span class="texto-visible">{{ $formulario->numero_control }}</span>
                                                        </small>
                                                        <small class="d-block mb-1">
                                                            <strong class="texto-visible">Especialidad:</strong> 
                                                            <span class="texto-visible">{{ $formulario->especialidad }}</span>
                                                        </small>
                                                        <small class="d-block mb-1">
                                                            <strong class="texto-visible">Grupo:</strong> 
                                                            <span class="texto-visible">{{ $formulario->grupo }}</span>
                                                        </small>
                                                        <small class="d-block mb-1">
                                                            <strong class="texto-visible">Pago:</strong> 
                                                            <span class="texto-visible">{{ $formulario->tipo_pago }}</span>
                                                        </small>
                                                        <small class="d-block mb-1">
                                                            <strong class="texto-visible">Fecha:</strong> 
                                                            <span class="texto-visible">{{ \Carbon\Carbon::parse($formulario->fecha_pago)->format('d/m/Y') }}</span>
                                                        </small>
                                                        <small class="d-block">
                                                            <strong class="texto-visible">Materias:</strong> 
                                                            @if($formulario->materias && trim($formulario->materias) !== '')
                                                                <span class="texto-visible">{{ Str::limit($formulario->materias, 50) }}</span>
                                                            @else
                                                                <span class="text-muted">Sin materias registradas</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- NÚMERO DE CONTROL -->
                                        <td class="d-none d-md-table-cell">
                                            <span class="texto-visible fw-medium">{{ $formulario->numero_control }}</span>
                                        </td>
                                        <!-- ESPECIALIDAD -->
                                        <td class="d-none d-lg-table-cell">
                                            <span class="badge bg-info text-white">{{ $formulario->especialidad }}</span>
                                        </td>
                                        <!-- GRUPO -->
                                        <td class="d-none d-lg-table-cell">
                                            <span class="badge bg-purple text-white">{{ $formulario->grupo }}</span>
                                        </td>
                                        <!-- TIPO DE PAGO -->
                                        <td class="d-none d-xl-table-cell">
                                            <span class="badge bg-{{ $formulario->tipo_pago == 'efectivo' ? 'success' : 'warning' }} text-white">
                                                {{ $formulario->tipo_pago }}
                                            </span>
                                        </td>
                                        <!-- FECHA DE PAGO -->
                                        <td class="d-none d-xl-table-cell">
                                            <span class="texto-visible">{{ \Carbon\Carbon::parse($formulario->fecha_pago)->format('d/m/Y') }}</span>
                                        </td>
                                        <!-- MATERIAS -->
                                        <td class="d-none d-md-table-cell">
                                            @if($formulario->materias && trim($formulario->materias) !== '')
                                                <span class="materias-tooltip" data-bs-toggle="tooltip" title="{{ $formulario->materias }}">
                                                    <span class="badge bg-primary text-white">Ver materias</span>
                                                </span>
                                            @else
                                                <span class="badge bg-secondary text-white">Sin materias</span>
                                            @endif
                                        </td>
                                        <!-- STATUS -->
                                        <td>
                                            <span class="badge {{ $colorClase }} text-white" data-bs-toggle="tooltip" title="{{ $tooltipText }}">
                                                {{ $estado }}
                                            </span>
                                        </td>
                                        <!-- ACCIONES - BOTÓN MÁS GRANDE EN MÓVILES -->
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('control_user.show', ['id' => $formulario->id]) }}" class="btn btn-primary btn-accion" title="Ver detalles de la solicitud">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="d-none d-md-inline ms-1">Ver</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <div class="empty">
                                                <div class="empty-icon">
                                                    <i class="fas fa-inbox fa-3x text-muted"></i>
                                                </div>
                                                <p class="empty-title texto-visible">No hay solicitudes disponibles</p>
                                                <p class="empty-subtitle texto-visible">
                                                    No se encontraron solicitudes que coincidan con los criterios de búsqueda.
                                                </p>
                                                @if(request()->hasAny(['search', 'especialidad', 'grupo', 'tipo_pago']))
                                                    <div class="empty-action">
                                                        <a href="{{ route('control_user.index') }}" class="btn btn-primary">
                                                            <i class="fas fa-times me-1"></i>
                                                            Limpiar filtros
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center w-100 gap-2">
                                <div class="texto-visible">
                                    Mostrando {{ $formularios->firstItem() ?? 0 }} a {{ $formularios->lastItem() ?? 0 }} de {{ $formularios->total() }} registros
                                </div>
                                {!! $formularios->appends(request()->except('page'))->links('tablar::pagination') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto-ocultar alertas después de 5 segundos
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Sincronizar valores entre formularios desktop y móvil
            const syncFormValues = () => {
                document.getElementById('mobile_especialidad').value = document.querySelector('select[name="especialidad"]').value;
                document.getElementById('mobile_grupo').value = document.querySelector('select[name="grupo"]').value;
                document.getElementById('mobile_tipo_pago').value = document.querySelector('select[name="tipo_pago"]').value;
                document.getElementById('mobile_search').value = document.querySelector('input[name="search"]').value;
            };

            // Sincronizar al cargar la página
            syncFormValues();

            // Sincronizar cuando se abre el offcanvas
            document.getElementById('filterOffcanvas').addEventListener('show.bs.offcanvas', syncFormValues);
        });
    </script>

    <style>
        /* ESTILOS PARA TODOS LOS TEXTOS VISIBLES */
        .texto-visible {
            color: #1f2937 !important;
            font-weight: 500 !important;
        }
        
        /* Información en móviles - SIN FONDO BLANCO */
        .informacion-movil {
            padding: 0.5rem 0;
        }
        
        .informacion-movil small {
            line-height: 1.4;
        }
        
        /* BOTÓN DE ACCIÓN - TAMAÑO MEJORADO PARA MÓVILES */
        .btn-accion {
            min-width: 60px !important;
            min-height: 32px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        /* Mejoras de responsividad adicionales */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            
            /* BOTÓN MÁS GRANDE EN MÓVILES */
            .btn-accion {
                min-width: 50px !important;
                min-height: 36px !important;
                padding: 0.5rem 0.75rem !important;
                font-size: 0.9rem !important;
            }
            
            .btn-accion i {
                font-size: 1rem !important;
            }
            
            .badge {
                font-size: 0.7rem;
            }
            .texto-visible {
                font-size: 0.85rem;
            }
            
            /* Mejor espaciado para información en móviles */
            .informacion-movil {
                border-top: 1px solid #e5e7eb;
                margin-top: 0.5rem;
                padding-top: 0.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .card-body .row.g-3 > [class*="col-"] {
                margin-bottom: 1rem;
            }
            .input-group .btn {
                min-width: auto;
                padding: 0.375rem 0.75rem;
            }
            .texto-visible {
                font-size: 0.8rem;
            }
            
            /* BOTÓN AÚN MÁS GRANDE EN PANTALLAS MUY PEQUEÑAS */
            .btn-accion {
                min-width: 55px !important;
                min-height: 38px !important;
                padding: 0.6rem 0.8rem !important;
            }
            
            .btn-accion i {
                font-size: 1.1rem !important;
            }
        }

        @media (max-width: 400px) {
            /* BOTÓN OPTIMIZADO PARA PANTALLAS MUY PEQUEÑAS */
            .btn-accion {
                min-width: 48px !important;
                min-height: 40px !important;
                padding: 0.7rem 0.5rem !important;
            }
        }

        /* Badges con mejor contraste */
        .bg-primary { background-color: #3b82f6 !important; }
        .bg-success { background-color: #10b981 !important; }
        .bg-warning { background-color: #f59e0b !important; }
        .bg-danger { background-color: #ef4444 !important; }
        .bg-info { background-color: #06b6d4 !important; }
        .bg-purple { background-color: #8b5cf6 !important; }
        .bg-secondary { background-color: #6b7280 !important; }
        .bg-orange { background-color: #f97316 !important; }
        
        /* Asegurar que todos los textos sean visibles en la tabla */
        .table td, .table th {
            color: #1f2937 !important;
        }
        
        /* Mejor contraste para filas en hover */
        .card-table tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection