@extends('tablar::page')

@section('title')
    Expedientes de Solicitudes de servicios
@endsection

@section('content')
    <!-- Encabezado de página -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Pre-título de la página -->
                    <div class="page-pretitle">
                        
                    </div>
                    <h2 class="page-title">
                        {{ __('Expedientes de Solicitudes de Servicios') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuerpo de la página -->
    <div class="page-body">
        <div class="container-xl">
            @if(config('tablar','display_alert'))
                @include('tablar::common.alert')
            @endif
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Solicitudes con Comprobante</h3>
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
                                    <th>Nombre</th>
                                    <th>No. Control</th>
                                    <th>Especialidad</th>
                                    <th>Grupo</th>
                                    <th>Generación</th>
                                    <th>Semestre</th>
                                    <th>Fecha</th>
                                    <th>CURP</th>
                                    <th>Tipo Servicio</th>
                                    <th>Status</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($solicitudes as $solicitud)
                                    <tr>
                                        <td><input class="form-check-input m-0 align-middle" type="checkbox"
                                                   aria-label="Select solicitud"></td>
                                        <td>{{ $solicitud->nombre }}</td>
                                        <td>{{ $solicitud->numero_control }}</td>
                                        <td>{{ $solicitud->especialidad }}</td>
                                        <td>{{ $solicitud->grupo }}</td>
                                        <td>{{ $solicitud->generacion }}</td>
                                        <td>{{ $solicitud->semestre }}</td>
                                        <td>{{ $solicitud->fecha }}</td>
                                        <td>
                                            <span title="{{ $solicitud->curp }}">{{ Str::limit($solicitud->curp, 10) }}</span>
                                        </td>
                                        <td>
                                            <span title="{{ $solicitud->tipo_servicio }}">{{ Str::limit($solicitud->tipo_servicio, 10) }}</span>
                                        </td>
                                        <td>Documentos disponible</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Acciones
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="{{ route('control_user.show', $solicitud->id) }}" class="dropdown-item">
                                                            Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12">Sin información</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {!! $solicitudes->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
