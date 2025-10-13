@extends('tablar::page')

@section('title', 'Ver Solicitud')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Financieros
                    </div>
                    <h2 class="page-title">
                        Detalles de la Solicitud
                    </h2>
                </div>
                <!-- Botón SIEMPRE visible -->
                <div class="col-auto ms-auto">
                    <div class="btn-list">
                        <a href="{{ route('formularios.index') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/>
                            </svg>
                            Lista de Solicitudes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalles de la Solicitud</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Nombre del Alumno:</strong>
                                        <span class="d-block">{{ $formulario->nombre }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Número de Control:</strong>
                                        <span class="d-block">{{ $formulario->numero_control }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">CURP:</strong>
                                        <span class="d-block">{{ $formulario->curp }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Grupo:</strong>
                                        <span class="d-block">{{ $formulario->grupo }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Especialidad:</strong>
                                        <span class="d-block">{{ $formulario->especialidad }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Fecha:</strong>
                                        <span class="d-block">{{ $formulario->fecha_pago }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Tipo de Servicio:</strong>
                                        <span class="d-block">{{ $formulario->tipo_pago }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Status:</strong>
                                        @php
                                            // Lógica corregida para determinar el estado automáticamente
                                            // Estados manuales tienen prioridad
                                            if ($formulario->status == 'declinada') {
                                                $estado = 'Rechazada';
                                                $colorClase = 'bg-danger text-white';
                                            } 
                                            elseif ($formulario->status == 'Generando Liga de Pago') {
                                                $estado = 'Generando Liga de Pago';
                                                $colorClase = 'bg-orange text-white';
                                            }
                                            // Estados automáticos basados en documentos
                                            elseif ($formulario->comprobante_oficial) {
                                                $estado = 'Finalizada';
                                                $colorClase = 'bg-success text-white';
                                            } 
                                            elseif ($formulario->comprobante) {
                                                $estado = 'Comprobante Generado';
                                                $colorClase = 'bg-info text-white';
                                            } 
                                            elseif ($formulario->comprobante_alumno) {
                                                $estado = 'Comprobante Cargado';
                                                $colorClase = 'bg-primary text-white';
                                            } 
                                            elseif ($formulario->liga_de_pago) {
                                                $estado = 'Liga Generada';
                                                $colorClase = 'bg-warning text-dark';
                                            } 
                                            else {
                                                $estado = 'Pendiente';
                                                $colorClase = 'bg-secondary text-white';
                                            }
                                        @endphp
                                        <span class="badge {{ $colorClase }}">
                                            {{ $estado }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Materias:</strong>
                                        <div>
                                            @if($formulario->materias && trim($formulario->materias) !== '')
                                                @php
                                                    $materiasArray = explode(', ', $formulario->materias);
                                                    $materiasFiltradas = array_filter($materiasArray);
                                                @endphp
                                                @if(count($materiasFiltradas) > 0)
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($materiasFiltradas as $materia)
                                                            <span class="badge bg-primary text-white border rounded px-2 py-1">{{ $materia }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">No hay materias disponibles.</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No hay materias disponibles.</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                               

                                <!-- Gestión de Documentos -->
                                <div class="col-12 border-top pt-4">
                                    <strong class="d-block mb-3">Gestión de Documentos:</strong>
                                    <div class="row g-3">
                                        <!-- Liga de Pago -->
                                        <div class="col-12 col-lg-6">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-title">Liga de Pago</h6>
                                                    <form action="{{ route('formularios.uploadLigaDePago', $formulario->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="liga_de_pago" class="form-label">Subir Liga de Pago:</label>
                                                            <input type="file" class="form-control" name="liga_de_pago" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary mt-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                                <path d="M7 9l5 -5l5 5"/>
                                                                <path d="M12 4l0 12"/>
                                                            </svg>
                                                            Subir
                                                        </button>
                                                    </form>
                                                
                                                    <div class="mt-3">
                                                        @if($formulario->liga_de_pago)
                                                            <a href="{{ route('formularios.downloadLigaDePago', $formulario->id) }}" target="_blank" class="btn btn-outline-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                                    <path d="M7 11l5 5l5 -5"/>
                                                                    <path d="M12 4l0 12"/>
                                                                </svg>
                                                                Descargar Liga de Pago
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Sin Liga de Pago</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Comprobante de Alumno -->
                                        <div class="col-12 col-lg-6">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-title">Comprobante de Alumno</h6>
                                                    @if($formulario->comprobante_alumno)
                                                        <a href="{{ route('formularios.downloadComprobanteAlumno', $formulario->id) }}" target="_blank" class="btn btn-outline-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                                <path d="M7 11l5 5l5 -5"/>
                                                                <path d="M12 4l0 12"/>
                                                            </svg>
                                                            Descargar Comprobante de Alumno
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Sin Comprobante de Alumno</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Comprobante Oficial -->
                                        @if($formulario->comprobante_alumno)
                                            <div class="col-12">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <h6 class="card-title">Comprobante Oficial</h6>
                                                        <form action="{{ route('formularios.uploadComprobanteOficial', $formulario->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="row g-3">
                                                                <div class="col-12 col-md-8">
                                                                    <label for="comprobante_oficial" class="form-label">Subir Comprobante Oficial:</label>
                                                                    <input type="file" class="form-control" name="comprobante_oficial" required>
                                                                </div>
                                                                <div class="col-12 col-md-4 d-flex align-items-end">
                                                                    <button type="submit" class="btn btn-primary w-100">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                                            <path d="M7 9l5 -5l5 5"/>
                                                                            <path d="M12 4l0 12"/>
                                                                        </svg>
                                                                        Subir
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    
                                                        <div class="mt-3">
                                                            @if($formulario->comprobante_oficial)
                                                                <a href="{{ route('formularios.downloadComprobanteOficial', $formulario->id) }}" target="_blank" class="btn btn-outline-success">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                                        <path d="M7 11l5 5l5 -5"/>
                                                                        <path d="M12 4l0 12"/>
                                                                    </svg>
                                                                    Descargar Comprobante Oficial
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Sin Comprobante Oficial</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .bg-orange {
            background-color: #f97316 !important;
        }
    </style>
@endsection