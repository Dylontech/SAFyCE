@extends('tablar::page')

@section('title', 'Ver Solicitud')

@section('content')
    <!-- Encabezado de página -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Pre-título de la página -->
                    <div class="page-pretitle">
                        Ver
                    </div>
                    <h2 class="page-title">
                        {{ __('Solicitud') }}
                    </h2>
                </div>
                <!-- Acciones del título de la página - Botón SIEMPRE visible -->
                <div class="col-auto ms-auto">
                    <div class="btn-list">
                        <a href="{{ route('gestions.index') }}" class="btn btn-primary">
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
    <!-- Cuerpo de la página -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @if(config('tablar','display_alert'))
                        @include('tablar::common.alert')
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalles de la Solicitud</h3>
                        </div>
                        <div class="card-body">
                            <!-- Información del Alumno - Grid Responsivo -->
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Nombre del Alumno:</strong>
                                        <span class="d-block">{{ $formulario->nombre }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Número de Control:</strong>
                                        <span class="d-block">{{ $formulario->control }}</span>
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
                                        <strong class="d-block mb-1">Grupo:</strong>
                                        <span class="d-block">{{ $formulario->grupo }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Semestre:</strong>
                                        <span class="d-block">{{ $formulario->semestre }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Fecha:</strong>
                                        <span class="d-block">{{ $formulario->fecha }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">CURP:</strong>
                                        <span class="d-block text-break">{{ $formulario->curp }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Tipo de Servicio:</strong>
                                        <span class="badge bg-primary text-white border rounded px-3 py-2">{{ $formulario->tipo_servicio }}</span>
                                    </div>
                                </div>
                            </div>

                            @if(!$formulario->comprobante_oficial)
                                <!-- Formulario de Status - Responsivo -->
                                <div class="form-group border-top pt-4">
                                    <strong class="mb-3 d-block">Estado de la Solicitud:</strong>
                                    <form action="{{ route('gestions.updateStatus', $formulario->id) }}" method="POST" id="status-form">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label for="status-select" class="form-label">Estado de la Solicitud</label>
                                                <select name="status" id="status-select" class="form-select">
                                                    <option value="generando_liga_pago" {{ $formulario->status == 'generando_liga_pago' ? 'selected' : '' }}>Generando Liga de Pago</option>
                                                    <option value="declinada" {{ $formulario->status == 'declinada' ? 'selected' : '' }}>Declinada</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>
                                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                        <path d="M14 4l0 4l-6 0l0 -4"/>
                                                    </svg>
                                                    Guardar Cambios
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            <!-- Sección de Comprobantes - Responsiva -->
                            <div class="form-group border-top pt-4">
                                <strong class="mb-3 d-block">Gestión de Comprobantes:</strong>
                                <div class="row g-3">
                                    <!-- Botón de descarga de comprobante oficial -->
                                    <div class="col-12">
                                        @if($formulario->comprobante_oficial)
                                            <a href="{{ route('finanzas.downloadComprobanteOficial', $formulario->id) }}" 
                                               class="btn btn-outline-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                    <path d="M7 11l5 5l5 -5"/>
                                                    <path d="M12 4l0 12"/>
                                                </svg>
                                                Descargar Comprobante Oficial
                                            </a>
                                        @endif
                                    </div>
                                    
                                    @if($formulario->comprobante_oficial)
                                        <!-- Formulario para subir comprobante -->
                                        <div class="col-12">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <form action="{{ route('gestions.uploadComprobante', $formulario->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <label for="comprobante" class="form-label">Subir Comprobante</label>
                                                                <input type="file" name="comprobante" id="comprobante" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="alert alert-warning mb-0">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M12 9v4"/>
                                                                        <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/>
                                                                        <path d="M12 16h.01"/>
                                                                    </svg>
                                                                    <strong>Importante:</strong> El tamaño máximo permitido es de 10 MB. Formatos: PDF, JPG, JPEG, PNG.
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <button type="submit" class="btn btn-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                                        <path d="M7 9l5 -5l5 5"/>
                                                                        <path d="M12 4l0 12"/>
                                                                    </svg>
                                                                    Subir Comprobante
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status-select');
            
            function toggleCommentRequired() {
                const comentario = document.getElementById('comentario');
                if (statusSelect.value === 'declinada') {
                    comentario.setAttribute('required', 'required');
                } else {
                    comentario.removeAttribute('required');
                }
            }
            
            // Aplicar al cargar y cuando cambie
            if (statusSelect) {
                toggleCommentRequired();
                statusSelect.addEventListener('change', toggleCommentRequired);
            }
        });
    </script>
@endsection
