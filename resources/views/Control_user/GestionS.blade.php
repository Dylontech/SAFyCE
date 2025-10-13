@extends('tablar::page')

@section('title')
    Nuevas solicitudes de Servicios
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
                        {{ __('Nuevas Solicitudes de Servicios') }}
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
                        <div class="card-header">
                            <h3 class="card-title">Solicitudes</h3>
                        </div>
                        
                        <!-- Filtros para desktop -->
                        <div class="card-body border-bottom py-3 d-none d-lg-block">
                            <form method="GET" action="{{ route('Control_user.GestionS') }}">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <label class="form-label">Especialidad:</label>
                                        <select class="form-select" name="especialidad">
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
                                        <label class="form-label">No. Control:</label>
                                        <select class="form-select" name="control">
                                            <option value="">Todos</option>
                                            @foreach($controles as $control)
                                                <option value="{{ $control }}" {{ request('control') == $control ? 'selected' : '' }}>{{ $control }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <label class="form-label">Tipo de Servicio:</label>
                                        <select class="form-select" name="tipo_servicio">
                                            <option value="">Todos los servicios</option>
                                            @foreach($tipos_servicio as $tipo_servicio)
                                                <option value="{{ $tipo_servicio }}" {{ request('tipo_servicio') == $tipo_servicio ? 'selected' : '' }}>{{ $tipo_servicio }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-8 col-xl-6">
                                        <label class="form-label">Buscar:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre, número de control, CURP..." aria-label="Search">
                                            <button type="submit" class="btn btn-primary" title="Buscar solicitudes">
                                                <i class="fas fa-search me-1"></i>
                                                <span>Buscar</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if(request()->hasAny(['buscar', 'especialidad', 'grupo', 'control', 'tipo_servicio']))
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <a href="{{ route('Control_user.GestionS') }}" class="btn btn-outline-secondary btn-sm">
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
                                <form method="GET" action="{{ route('Control_user.GestionS') }}" id="mobileFilterForm">
                                    <div class="mb-3">
                                        <label for="mobile_especialidad" class="form-label">Especialidad</label>
                                        <select class="form-select" name="especialidad" id="mobile_especialidad">
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
                                        <label for="mobile_grupo" class="form-label">Grupo</label>
                                        <select class="form-select" name="grupo" id="mobile_grupo">
                                            <option value="">Todos los grupos</option>
                                            @foreach($grupos as $grupo)
                                                <option value="{{ $grupo }}" {{ request('grupo') == $grupo ? 'selected' : '' }}>{{ $grupo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_control" class="form-label">No. Control</label>
                                        <select class="form-select" name="control" id="mobile_control">
                                            <option value="">Todos</option>
                                            @foreach($controles as $control)
                                                <option value="{{ $control }}" {{ request('control') == $control ? 'selected' : '' }}>{{ $control }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_tipo_servicio" class="form-label">Tipo de Servicio</label>
                                        <select class="form-select" name="tipo_servicio" id="mobile_tipo_servicio">
                                            <option value="">Todos los servicios</option>
                                            @foreach($tipos_servicio as $tipo_servicio)
                                                <option value="{{ $tipo_servicio }}" {{ request('tipo_servicio') == $tipo_servicio ? 'selected' : '' }}>{{ $tipo_servicio }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_buscar" class="form-label">Buscar</label>
                                        <input type="text" class="form-control" name="buscar" id="mobile_buscar" 
                                               value="{{ request('buscar') }}" placeholder="Nombre, número de control, CURP...">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        <i class="fas fa-search me-2"></i>Aplicar Filtros
                                    </button>
                                    @if(request()->hasAny(['buscar', 'especialidad', 'grupo', 'control', 'tipo_servicio']))
                                        <a href="{{ route('Control_user.GestionS') }}" class="btn btn-outline-secondary w-100">
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
                                    <th class="d-none d-xl-table-cell">Semestre</th>
                                    <th class="d-none d-md-table-cell">Fecha</th>
                                    <th class="d-none d-xl-table-cell">CURP</th>
                                    <th class="d-none d-sm-table-cell">Servicio</th>
                                    <th class="d-md-none">Status</th>
                                    <th class="d-none d-md-table-cell">Status</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($formularios as $formulario)
                                    @if($formulario->comprobante && $formulario->updated_at->lt(\Carbon\Carbon::now()->subDay()))
                                        @continue
                                    @endif
                                    <tr>
                                        <td><span class="badge bg-primary text-white text-large">{{ $loop->iteration + ($formularios->currentPage() - 1) * $formularios->perPage() }}</span></td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <!-- NOMBRE DEL ALUMNO -->
                                                <span class="fw-bold text-xlarge">{{ $formulario->nombre }}</span>
                                                <!-- Información adicional visible en móvil -->
                                                <div class="d-md-none mt-2">
                                                    <div class="informacion-movil">
                                                        <div class="mobile-details-grid">
                                                            <div class="detail-item">
                                                                <span class="detail-label text-large">Control:</span>
                                                                <span class="detail-value text-large">{{ $formulario->control }}</span>
                                                            </div>
                                                            <div class="detail-item">
                                                                <span class="detail-label text-large">Especialidad:</span>
                                                                <span class="detail-value text-large">{{ $formulario->especialidad }}</span>
                                                            </div>
                                                            <div class="detail-item">
                                                                <span class="detail-label text-large">Grupo:</span>
                                                                <span class="detail-value text-large">{{ $formulario->grupo }}</span>
                                                            </div>
                                                            <div class="detail-item">
                                                                <span class="detail-label text-large">Semestre:</span>
                                                                <span class="detail-value text-large">{{ $formulario->semestre }}</span>
                                                            </div>
                                                            <div class="detail-item">
                                                                <span class="detail-label text-large">Fecha:</span>
                                                                <span class="detail-value text-large">{{ $formulario->fecha }}</span>
                                                            </div>
                                                            <div class="detail-item">
                                                                <span class="detail-label text-large">Servicio:</span>
                                                                <span class="detail-value text-large servicio-mobile">{{ $formulario->tipo_servicio }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- NÚMERO DE CONTROL -->
                                        <td class="d-none d-md-table-cell">
                                            <span class="text-xlarge fw-medium">{{ $formulario->control }}</span>
                                        </td>
                                        <!-- ESPECIALIDAD -->
                                        <td class="d-none d-lg-table-cell">
                                            <span class="badge bg-info text-white text-large">{{ $formulario->especialidad }}</span>
                                        </td>
                                        <!-- GRUPO -->
                                        <td class="d-none d-lg-table-cell">
                                            <span class="badge bg-purple text-white text-large">{{ $formulario->grupo }}</span>
                                        </td>
                                        <!-- SEMESTRE -->
                                        <td class="d-none d-xl-table-cell">
                                            <span class="text-xlarge">{{ $formulario->semestre }}</span>
                                        </td>
                                        <!-- FECHA -->
                                        <td class="d-none d-md-table-cell">
                                            <span class="text-xlarge">{{ $formulario->fecha }}</span>
                                        </td>
                                        <!-- CURP -->
                                        <td class="d-none d-xl-table-cell">
                                            <span class="curp-tooltip" data-bs-toggle="tooltip" title="{{ $formulario->curp }}">
                                                <span class="badge bg-secondary text-white text-large">Ver CURP</span>
                                            </span>
                                        </td>
                                        <!-- TIPO SERVICIO -->
                                        <td class="d-none d-sm-table-cell">
                                            <span class="servicio-tooltip" data-bs-toggle="tooltip" title="{{ $formulario->tipo_servicio }}">
                                                <span class="badge bg-primary text-white text-large servicio-desktop">{{ $formulario->tipo_servicio }}</span>
                                            </span>
                                        </td>
                                        <!-- STATUS PARA MÓVILES (debajo del nombre) -->
                                        <td class="d-md-none">
                                            @php
                                                $statusClass = 'secondary';
                                                $statusText = $formulario->status;
                                                
                                                if($formulario->comprobante) {
                                                    $statusClass = 'success';
                                                    $statusText = 'Finalizada';
                                                } elseif($formulario->liga_de_pago) {
                                                    $statusClass = 'warning';
                                                    $statusText = 'Esperando comprobante';
                                                } else {
                                                    $statusClass = [
                                                        'pendiente' => 'warning',
                                                        'aprobado' => 'success',
                                                        'rechazado' => 'danger',
                                                        'en revisión' => 'info',
                                                        'completado' => 'primary'
                                                    ][$formulario->status] ?? 'secondary';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }} text-white text-xlarge">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <!-- STATUS PARA DESKTOP -->
                                        <td class="d-none d-md-table-cell">
                                            @php
                                                $statusClass = 'secondary';
                                                $statusText = $formulario->status;
                                                
                                                if($formulario->comprobante) {
                                                    $statusClass = 'success';
                                                    $statusText = 'Finalizada';
                                                } elseif($formulario->liga_de_pago) {
                                                    $statusClass = 'warning';
                                                    $statusText = 'Esperando comprobante';
                                                } else {
                                                    $statusClass = [
                                                        'pendiente' => 'warning',
                                                        'aprobado' => 'success',
                                                        'rechazado' => 'danger',
                                                        'en revisión' => 'info',
                                                        'completado' => 'primary'
                                                    ][$formulario->status] ?? 'secondary';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }} text-white text-xlarge">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <!-- ACCIONES -->
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('gestions.show', $formulario->id) }}" class="btn btn-primary btn-accion text-xlarge" title="Ver detalles de la solicitud">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="d-none d-md-inline ms-1">Ver</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-5">
                                            <div class="empty">
                                                <div class="empty-icon">
                                                    <i class="fas fa-inbox fa-3x text-muted"></i>
                                                </div>
                                                <p class="empty-title text-xxlarge">No hay solicitudes disponibles</p>
                                                <p class="empty-subtitle text-xlarge">
                                                    No se encontraron solicitudes que coincidan con los criterios de búsqueda.
                                                </p>
                                                @if(request()->hasAny(['buscar', 'especialidad', 'grupo', 'control', 'tipo_servicio']))
                                                    <div class="empty-action">
                                                        <a href="{{ route('Control_user.GestionS') }}" class="btn btn-primary text-xlarge">
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
                                <div class="text-xlarge">
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
                document.getElementById('mobile_control').value = document.querySelector('select[name="control"]').value;
                document.getElementById('mobile_tipo_servicio').value = document.querySelector('select[name="tipo_servicio"]').value;
                document.getElementById('mobile_buscar').value = document.querySelector('input[name="buscar"]').value;
            };

            // Sincronizar al cargar la página
            syncFormValues();

            // Sincronizar cuando se abre el offcanvas
            document.getElementById('filterOffcanvas').addEventListener('show.bs.offcanvas', syncFormValues);
        });
    </script>

    <style>
        /* SISTEMA DE TAMAÑOS DE TEXTO MEJORADO - MUCHO MÁS GRANDES */
        .text-large {
            font-size: 1.1rem !important;   /* 18px */
            line-height: 1.4 !important;
        }
        
        .text-xlarge {
            font-size: 1.25rem !important;  /* 20px */
            line-height: 1.4 !important;
        }
        
        .text-xxlarge {
            font-size: 1.5rem !important;   /* 24px */
            line-height: 1.4 !important;
        }

        /* HEADERS VISIBLES - SOLO STATUS OCULTO EN MÓVILES */
        @media (max-width: 768px) {
            .d-md-none {
                display: none !important;
            }
        }

        /* INFORMACIÓN MÓVIL MEJORADA */
        .informacion-movil {
            padding: 1rem 0;
            margin-top: 0.75rem;
        }
        
        .mobile-details-grid {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 100px;
        }
        
        .detail-value {
            font-weight: 500;
            color: #1a1a1a;
            text-align: right;
        }
        
        .servicio-mobile {
            font-weight: 600;
            color: #3b82f6;
        }

        /* BOTONES MÁS GRANDES */
        .btn-accion {
            min-width: 80px !important;
            min-height: 44px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 1.1rem !important;
            padding: 0.75rem 1rem !important;
        }

        /* TABLA CON TEXTO MÁS GRANDE */
        .table {
            font-size: 1.1rem !important;
        }
        
        .table th {
            font-size: 1rem !important;
            font-weight: 700;
            padding: 1.25rem 0.75rem;
        }
        
        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        /* BADGES MÁS GRANDES */
        .badge {
            font-size: 1rem !important;
            padding: 0.75rem 1rem;
            font-weight: 600;
        }

        /* SERVICIO DESKTOP */
        .servicio-desktop {
            max-width: 200px;
            white-space: normal;
            word-wrap: break-word;
            text-align: center;
            line-height: 1.3;
        }

        /* MEJORAS DE RESPONSIVIDAD */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 1.1rem !important;
            }
            
            .btn-accion {
                min-width: 70px !important;
                min-height: 48px !important;
                font-size: 1.2rem !important;
                padding: 0.85rem 1rem !important;
            }
            
            .badge {
                font-size: 1.1rem !important;
                padding: 0.85rem 1.1rem;
            }
            
            .text-large {
                font-size: 1.2rem !important;
            }
            
            .text-xlarge {
                font-size: 1.3rem !important;
            }
        }

        @media (max-width: 576px) {
            .text-large {
                font-size: 1.25rem !important;
            }
            
            .text-xlarge {
                font-size: 1.35rem !important;
            }
            
            .text-xxlarge {
                font-size: 1.6rem !important;
            }
            
            .btn-accion {
                min-width: 75px !important;
                min-height: 52px !important;
                font-size: 1.25rem !important;
            }
            
            .detail-item {
                padding: 0.75rem 0;
            }
        }

        /* COLORES DE BADGES */
        .bg-primary { background-color: #3b82f6 !important; }
        .bg-success { background-color: #10b981 !important; }
        .bg-warning { background-color: #f59e0b !important; }
        .bg-danger { background-color: #ef4444 !important; }
        .bg-info { background-color: #06b6d4 !important; }
        .bg-purple { background-color: #8b5cf6 !important; }
        .bg-secondary { background-color: #6b7280 !important; }

        /* MEJORAS GENERALES */
        .form-label {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .form-select, .form-control {
            font-size: 1.1rem;
        }
        
        .btn {
            font-size: 1.1rem;
        }
        
        .pagination .page-link {
            font-size: 1.1rem;
        }
        
        .alert {
            font-size: 1.1rem;
        }
    </style>
@endsection