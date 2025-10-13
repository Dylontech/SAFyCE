{{-- 

    Esta plantilla Blade se utiliza para mostrar la página "Expediente de Solicitudes" para los estudiantes.
    Incluye las siguientes secciones:
    
    - title: Establece el título de la página como "Expediente de Solicitudes".
    - content: Contenido principal de la página, que incluye:
        - Un encabezado con el título de la página.
        - Una tabla que muestra una lista de solicitudes de estudiantes con varios detalles como nombre del estudiante, número de control, especialidad, grupo, tipo de servicio, fecha de solicitud y estado.
        - Paginación para la tabla.
        - Modales para mostrar comentarios sobre las solicitudes.
    - scripts: JavaScript para inicializar los tooltips de Bootstrap.
    
    La tabla incluye acciones para cada solicitud, como ver comentarios y descargar varios documentos relacionados con la solicitud.
    La página solo es accesible para usuarios autenticados con el guard 'alumno'.
--}}
@extends('tablar::page')

@section('title', 'Expediente de Solicitudes')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Expediente de Solicitudes</h2>
    @if(Auth::guard('alumno')->check())
    <!-- Encabezado de la página -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        
                    </div>
                    <h2 class="page-title">
                        {{ __('Expediente de Solicitudes') }}
                    </h2>
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
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="text-muted">
                                    Mostrar
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" value="10" size="3"
                                               aria-label="Invoices count">
                                    </div>
                                    Registros
                                </div>
                                <div class="ms-auto text-muted">
                                    Buscar:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm"
                                               aria-label="Search invoice">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox"
                                                           aria-label="Select all invoices"></th>
                                    <th class="w-1">No.
                                        <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="icon icon-sm text-dark icon-thick" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="6 15 12 9 18 15"/>
                                        </svg>
                                    </th>
                                    <th>Nombre del Alumno</th>
                                    <th>Número de Control</th>
                                    <th>Especialidad</th>
                                    <th>Grupo</th>
                                    <th>Tipo de Servicio</th>
                                    <th>Fecha de Solicitud</th>
                                    <th>Status</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($formularios as $formulario)
                                    @if ($formulario->comprobante && $formulario->liga_de_pago && $formulario->comprobante_alumno)
                                        <tr>
                                            <td><input class="form-check-input m-0 align-middle" type="checkbox"
                                                       aria-label="Select solicitud"></td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $formulario->nombre }}</td>
                                            <td>{{ $formulario->control }}</td>
                                            <td>{{ $formulario->especialidad }}</td>
                                            <td>{{ $formulario->grupo }}</td>
                                            <td>{{ $formulario->tipo_servicio }}</td>
                                            <td>{{ $formulario->fecha }}</td>
                                            <td data-bs-toggle="tooltip" title="{{ $formulario->comentario ?? 'Sin comentario' }}">{{ $formulario->comprobante ? 'comprobante disponible' : $formulario->status }}</td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <div class="dropdown">
                                                        <button class="btn dropdown-toggle align-text-top"
                                                                data-bs-toggle="dropdown">
                                                            Acciones
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @if ($formulario->comentario)
                                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#comentarioModal{{ $formulario->id }}">
                                                                    <i class="fa fa-fw fa-eye"></i> Ver Comentario
                                                                </button>
                                                            @endif
                                                            @if ($formulario->liga_de_pago)
                                                                <a href="{{ route('formularios.downloadLigaDePago', $formulario->id) }}" class="dropdown-item">
                                                                    <i class="fa fa-fw fa-download"></i> Descargar Liga de Pago
                                                                </a>
                                                            @endif
                                                            @if ($formulario->comprobante_alumno)
                                                                <a href="{{ route('formularios.downloadComprobanteAlumno', $formulario->id) }}" class="dropdown-item">
                                                                    <i class="fa fa-fw fa-download"></i> Descargar Comprobante del Alumno
                                                                </a>
                                                            @endif
                                                            @if ($formulario->comprobante)
                                                                <a href="{{ route('formularios.downloadComprobante', $formulario->id) }}" class="dropdown-item">
                                                                    <i class="fa fa-fw fa-download"></i> Descargar Comprobante
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
                                        <td colspan="10">Sin información</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {!! $formularios->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal para mostrar el comentario -->
@foreach ($formularios as $formulario)
    @if ($formulario->comentario)
        <div class="modal fade" id="comentarioModal{{ $formulario->id }}" tabindex="-1" aria-labelledby="comentarioModalLabel{{ $formulario->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="comentarioModalLabel{{ $formulario->id }}">Comentario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $formulario->comentario }}</p>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endsection
