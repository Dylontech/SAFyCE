@extends('tablar::page')

@section('title')
    Solicitudes de Servicios de Exámenes
@endsection

@section('content')
    <!-- Encabezado de página -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle"></div>
                    <h2 class="page-title">
                        {{ __('Solicitudes de Servicios de Exámenes') }}
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
    <!-- Cuerpo de la página -->
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
                            <h2 class="card-title">Solicitudes</h2>
                        </div>
                        
                        <!-- Filtros para desktop -->
                        <div class="card-body border-bottom py-3 d-none d-lg-block">
                            <form action="{{ route('solicitudes-servicios-s.index') }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6 col-lg-3">
    <label class="form-label">No. control</label>
    <input type="text" class="form-control" name="control" value="{{ request('control') }}" placeholder="" aria-label="Número de control">
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
                                        <label class="form-label">Grupo</label>
                                        <select class="form-select" id="grupo" name="grupo">
                                            <option value="">Todos</option>
                                            @foreach ($grupos as $grupo)
                                                <option value="{{ $grupo }}" {{ request('grupo') == $grupo ? 'selected' : '' }}>{{ $grupo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-3">
                                        <label class="form-label">Buscar</label>
                                        <input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar..." aria-label="Buscar">
                                    </div>
                                    <div class="col-12">
                                        <input type="submit" class="btn btn-primary w-100 w-sm-auto" value="Filtrar">
                                        @if(request()->hasAny(['buscar', 'control', 'especialidad', 'grupo']))
                                            <a href="{{ route('solicitudes-servicios-s.index') }}" class="btn btn-outline-secondary ms-2">
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
                                <form action="{{ route('solicitudes-servicios-s.index') }}" method="GET" id="mobileFilterForm">
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
                                        <label for="mobile_grupo" class="form-label">Grupo</label>
                                        <select class="form-select" id="mobile_grupo" name="grupo">
                                            <option value="">Todos</option>
                                            @foreach ($grupos as $grupo)
                                                <option value="{{ $grupo }}" {{ request('grupo') == $grupo ? 'selected' : '' }}>{{ $grupo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile_buscar" class="form-label">Buscar</label>
                                        <input type="text" class="form-control" id="mobile_buscar" name="buscar" 
                                               value="{{ request('buscar') }}" placeholder="Buscar...">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        Aplicar Filtros
                                    </button>
                                    @if(request()->hasAny(['buscar', 'control', 'especialidad', 'grupo']))
                                        <a href="{{ route('solicitudes-servicios-s.index') }}" class="btn btn-outline-secondary w-100">
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
                                        <th class="d-none d-lg-table-cell">Tipo de Pago</th>
                                        <th class="d-none d-md-table-cell">Fecha</th>
                                        <th>Status</th>
                                        <th class="w-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($formularios as $formulario)
                                        @php
                                            // Lógica corregida para determinar el estado automáticamente
                                            // Estados manuales tienen prioridad
                                            if ($formulario->status == 'declinada') {
                                                $estado = 'Rechazada';
                                                $colorClase = 'bg-danger';
                                                $tooltipText = 'Solicitud rechazada, comunicarse con el departamento de servicios escolares';
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
                                            <td>{{ $loop->iteration + ($formularios->currentPage() - 1) * $formularios->perPage() }}</td>
                                            <td class="d-none d-md-table-cell">{{ $formulario->nombre }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $formulario->numero_control }}</strong>
                                                    <!-- Información para móviles -->
                                                    <div class="d-md-none mt-2">
                                                        <div class="mobile-info">
                                                            <div><strong>Nombre:</strong> {{ $formulario->nombre }}</div>
                                                            <div><strong>Especialidad:</strong> {{ $formulario->especialidad }}</div>
                                                            <div><strong>Grupo:</strong> {{ $formulario->grupo }}</div>
                                                            <div><strong>Pago:</strong> {{ $formulario->tipo_pago }}</div>
                                                            <div><strong>Fecha:</strong> {{ $formulario->fecha_pago }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="d-none d-lg-table-cell">{{ $formulario->especialidad }}</td>
                                            <td class="d-none d-sm-table-cell">{{ $formulario->grupo }}</td>
                                            <td class="d-none d-lg-table-cell">{{ $formulario->tipo_pago }}</td>
                                            <td class="d-none d-md-table-cell">{{ $formulario->fecha_pago }}</td>
                                            <td>
                                                <span class="badge {{ $colorClase }} text-white d-block d-md-inline-block" 
                                                      data-bs-toggle="tooltip" title="{{ $tooltipText }}">
                                                    {{ $estado }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('finanzas.comprobantes', $formulario->id) }}" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Ver detalles de la solicitud">
                                                        <i class="fas fa-eye me-1"></i>
                                                        <span>Ver</span>
                                                    </a>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">Sin información</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <div class="me-3">
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
                            <h5 class="modal-title" id="comentarioModalLabel{{ $formulario->id }}">Comentario Financiero</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>{{ $formulario->comentario_financiero }}</p>
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
                document.getElementById('mobile_grupo').value = document.getElementById('grupo').value;
                document.getElementById('mobile_buscar').value = document.querySelector('input[name="buscar"]').value;
            };

            // Sincronizar al cargar la página
            syncFormValues();

            // Sincronizar cuando se abre el offcanvas
            document.getElementById('filterOffcanvas').addEventListener('show.bs.offcanvas', syncFormValues);
        });
    </script>

    <style>
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

        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
                gap: 0.3rem;
            }
        }
        .text-theme-responsive {
    color: var(--tblr-body-color) !important;
}
    </style>
@endsection