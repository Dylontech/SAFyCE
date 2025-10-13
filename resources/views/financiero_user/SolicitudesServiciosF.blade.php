@extends('tablar::page')

@section('title')
    Solicitudes de Servicios Financieros
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle"></div>
                    <h2 class="page-title">
                        {{ __('Solicitudes de Servicios Financieros') }}
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
                        <div class="card-header">
                            <h3 class="card-title">Solicitudes</h3>
                        </div>
                        
                        <!-- Filtros para desktop -->
                        <div class="card-body border-bottom py-3 d-none d-lg-block">
                            <form action="{{ route('finanzas.index') }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6 col-lg-3">
                                        <label class="form-label">No. control</label>
                                        <select class="form-select" id="control" name="control">
                                            <option value="">Todos</option>
                                            @foreach ($controles as $control)
                                                <option value="{{ $control }}" {{ request('control') == $control ? 'selected' : '' }}>{{ $control }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-3">
                                        <label class="form-label">Especialidad</label>
                                        <select class="form-select" id="especialidad" name="especialidad">
                                            <option value="">Todas</option>
                                            @foreach ($especialidades as $especialidad)
                                                <option value="{{ $especialidad }}" {{ request('especialidad') == $especialidad ? 'selected' : '' }}>{{ $especialidad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-3">
                                        <label class="form-label">Tipo de Servicio</label>
                                        <select class="form-select" id="tipo_servicio" name="tipo_servicio">
                                            <option value="">Todos</option>
                                            @foreach ($tipos_servicio as $tipo_servicio)
                                                <option value="{{ $tipo_servicio }}" {{ request('tipo_servicio') == $tipo_servicio ? 'selected' : '' }}>{{ $tipo_servicio }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-3">
                                        <label class="form-label">Buscar</label>
                                        <input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre del alumno..." aria-label="Search">
                                    </div>
                                    <div class="col-12">
                                        <input type="submit" class="btn btn-primary w-100 w-sm-auto" value="Filtrar">
                                        @if(request()->hasAny(['buscar', 'control', 'especialidad', 'tipo_servicio']))
                                            <a href="{{ route('finanzas.index') }}" class="btn btn-outline-secondary ms-2">
                                                Limpiar
                                            </a>
                                        @endif
                                    </div>
                                </div>
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
                                <form action="{{ route('finanzas.index') }}" method="GET" id="mobileFilterForm">
                                    <div class="mb-3">
                                        <label for="mobile_control" class="form-label">No. control</label>
                                        <select class="form-select" id="mobile_control" name="control">
                                            <option value="">Todos</option>
                                            @foreach ($controles as $control)
                                                <option value="{{ $control }}" {{ request('control') == $control ? 'selected' : '' }}>{{ $control }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_especialidad" class="form-label">Especialidad</label>
                                        <select class="form-select" id="mobile_especialidad" name="especialidad">
                                            <option value="">Todas</option>
                                            @foreach ($especialidades as $especialidad)
                                                <option value="{{ $especialidad }}" {{ request('especialidad') == $especialidad ? 'selected' : '' }}>{{ $especialidad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_tipo_servicio" class="form-label">Tipo de Servicio</label>
                                        <select class="form-select" id="mobile_tipo_servicio" name="tipo_servicio">
                                            <option value="">Todos</option>
                                            @foreach ($tipos_servicio as $tipo_servicio)
                                                <option value="{{ $tipo_servicio }}" {{ request('tipo_servicio') == $tipo_servicio ? 'selected' : '' }}>{{ $tipo_servicio }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_buscar" class="form-label">Buscar</label>
                                        <input type="text" class="form-control" id="mobile_buscar" name="buscar" 
                                               value="{{ request('buscar') }}" placeholder="Nombre del alumno...">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        Aplicar Filtros
                                    </button>
                                    @if(request()->hasAny(['buscar', 'control', 'especialidad', 'tipo_servicio']))
                                        <a href="{{ route('finanzas.index') }}" class="btn btn-outline-secondary w-100">
                                            Limpiar Filtros
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>
                        
                        <div class="table-responsive" style="min-height: 500px;">
                            <table class="table card-table table-vcenter text-nowrap datatable table-lg">
                                <thead>
                                    <tr>
                                        <th class="w-1">No.</th>
                                        <th class="d-none d-md-table-cell">Nombre del Alumno</th>
                                        <th>No. Control</th>
                                        <th class="d-none d-lg-table-cell">Especialidad</th>
                                        <th class="d-none d-sm-table-cell">Grupo</th>
                                        <th class="d-none d-lg-table-cell">Tipo de Servicio</th>
                                        <th class="d-none d-md-table-cell">Fecha</th>
                                        <th>Status</th>
                                        <th class="w-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($formularios as $formulario)
                                        <tr>
                                            <td class="text-theme">{{ $loop->iteration + ($formularios->currentPage() - 1) * $formularios->perPage() }}</td>
                                            <td class="d-none d-md-table-cell text-theme">{{ $formulario->nombre }}</td>
                                            <td>
                                                <div>
                                                    <strong class="text-theme">{{ $formulario->control }}</strong>
                                                    <!-- Información para móviles -->
                                                    <div class="d-md-none mt-2">
                                                        <div class="mobile-info">
                                                            <div class="text-theme"><strong>Nombre:</strong> {{ $formulario->nombre }}</div>
                                                            <div class="text-theme"><strong>Especialidad:</strong> {{ $formulario->especialidad }}</div>
                                                            <div class="text-theme"><strong>Grupo:</strong> {{ $formulario->grupo }}</div>
                                                            <div class="text-theme"><strong>Servicio:</strong> {{ $formulario->tipo_servicio }}</div>
                                                            <div class="text-theme"><strong>Fecha:</strong> {{ $formulario->fecha }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-theme">{{ $formulario->especialidad }}</td>
                                            <td class="d-none d-sm-table-cell text-theme">{{ $formulario->grupo }}</td>
                                            <td class="d-none d-lg-table-cell text-theme">{{ $formulario->tipo_servicio }}</td>
                                            <td class="d-none d-md-table-cell text-theme">{{ $formulario->fecha }}</td>
                                           <td>
    @php
        $statusClass = 'bg-warning text-white';
        $statusText = $formulario->status;
        $comentario = '';
        
        if($formulario->comprobante_oficial) {
            $statusClass = 'bg-success text-white';
            $statusText = 'Finalizada';
            $comentario = '✅ Solicitud completada y procesada exitosamente. Comprobante oficial generado.';
        } elseif($formulario->comprobante_alumno) {
            $statusClass = 'bg-info text-white';
            $statusText = 'Comprobante disponible';
            $comentario = '📄 Comprobante de alumno cargado. Esperando pago y comprobante oficial.';
        } elseif($formulario->liga_de_pago) {
            $statusClass = 'bg-primary text-white';
            $statusText = 'Pago en proceso';
            $comentario = '🔗 Liga de pago generada. Esperando que el alumno realice el pago.';
        } elseif($formulario->status == 'rechazado') {
            $statusClass = 'bg-danger text-white';
            $comentario = $formulario->comentario_financiero ?? '❌ Solicitud rechazada por el área de finanzas.';
        } elseif($formulario->status == 'aprobada') {
            $comentario = '✅ Solicitud aprobada. En proceso de generar documentación.';
        } elseif($formulario->status == 'generando_liga_pago') {
            $comentario = '⏳ Generando liga de pago. Proceso en curso.';
        } elseif($formulario->status == 'liga_de_pago_disponible') {
            $comentario = '🔗 Liga de pago disponible. Notificar al alumno.';
        } else {
            $comentario = '📝 Solicitud recibida. En revisión inicial.';
        }
    @endphp
    <span class="badge {{ $statusClass }} d-block d-md-inline-block status-badge" 
          data-bs-toggle="tooltip" title="{{ $comentario }}">
        {{ $statusText }}
    </span>
</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('finanzas.show', $formulario->id) }}" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Ver detalles de la solicitud">
                                                        <i class="fas fa-eye me-1"></i>
                                                        <span>Ver</span>
                                                    </a>
                                                    @if ($formulario->comentario_financiero)
                                                        <button class="btn btn-info btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#comentarioModal{{ $formulario->id }}"
                                                                title="Ver comentario">
                                                            <i class="fas fa-comment me-1"></i>
                                                            <span class="d-none d-sm-inline">Comentario</span>
                                                            <span class="d-sm-none">Coment.</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-theme">Sin información</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <div class="text-theme me-3">
                                Mostrando {{ $formularios->firstItem() ?? 0 }} a {{ $formularios->lastItem() ?? 0 }} de {{ $formularios->total() }} registros
                            </div>
                            {!! $formularios->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para mostrar el comentario -->
    @foreach ($formularios as $formulario)
        @if ($formulario->comentario_financiero)
            <div class="modal fade" id="comentarioModal{{ $formulario->id }}" tabindex="-1" aria-labelledby="comentarioModalLabel{{ $formulario->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-theme" id="comentarioModalLabel{{ $formulario->id }}">Comentario Financiero</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-theme">{{ $formulario->comentario_financiero }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Sincronizar valores entre formularios desktop y móvil
            const syncFormValues = () => {
                document.getElementById('mobile_control').value = document.getElementById('control').value;
                document.getElementById('mobile_especialidad').value = document.getElementById('especialidad').value;
                document.getElementById('mobile_tipo_servicio').value = document.getElementById('tipo_servicio').value;
                document.getElementById('mobile_buscar').value = document.querySelector('input[name="buscar"]').value;
            };

            // Sincronizar al cargar la página
            syncFormValues();

            // Sincronizar cuando se abre el offcanvas
            document.getElementById('filterOffcanvas').addEventListener('show.bs.offcanvas', syncFormValues);
        });
    </script>

    <style>
        /* CLASE PARA RESPONSIVIDAD DE TEMAS */
        .text-theme {
            color: var(--tblr-body-color) !important;
        }

        /* INFORMACIÓN MÓVIL MEJORADA */
        .mobile-info {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--tblr-border-color);
        }

        .mobile-info div {
            margin-bottom: 0.4rem;
            line-height: 1.4;
            font-size: 0.875rem;
        }

        .mobile-info strong {
            font-weight: 600;
        }

        /* MEJORAS DE RESPONSIVIDAD */
        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
                gap: 0.3rem;
            }
            
            .mobile-info div {
                font-size: 0.85rem;
            }
        }

        /* STATUS BADGES MEJORADOS */
        .status-badge {
            font-weight: 500;
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
        }
    </style>
@endsection