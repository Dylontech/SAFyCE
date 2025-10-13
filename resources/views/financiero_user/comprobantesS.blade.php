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
                        <a href="{{ route('finanzas.index') }}" class="btn btn-primary">
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
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
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
                                        <span class="d-block">{{ $formulario->curp }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Tipo de Servicio:</strong>
                                        <span class="badge bg-primary text-white px-3 py-2">{{ $formulario->tipo_servicio }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Estado Actual:</strong>
                                        @php
                                            // Lógica corregida para determinar el estado automáticamente
                                            // Estados manuales tienen prioridad
                                            if ($formulario->status == 'declinada') {
                                                $estado = 'Rechazada';
                                                $colorClase = 'bg-danger text-white';
                                            } 
                                            elseif ($formulario->status == 'generando_liga_pago') {
                                                $estado = 'Generar liga de pago ';
                                                $colorClase = 'bg-orange text-white';
                                            }
                                            elseif ($formulario->status == 'liga_de_pago_disponible') {
                                                $estado = 'Liga de Pago Disponible';
                                                $colorClase = 'bg-warning text-dark';
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
                                        <span class="badge {{ $colorClase }} px-3 py-2">{{ $estado }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Gestión -->
                            <div class="border-top pt-4 mt-4">
                                <strong class="mb-3 d-block">Gestión de la Solicitud:</strong>
                                <form action="{{ route('finanzas.store') }}" method="POST" id="status-form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="formulario_id" value="{{ $formulario->id }}">
                                    <div class="row g-3">
                                        
                                        
                                        <!-- Campo de comentario para estado declinada -->
                                        <div class="col-12 col-md-6" id="comentario-div" style="{{ $formulario->status == 'declinada' ? '' : 'display: none;' }}">
                                            <div class="form-group">
                                                <label for="comentario" class="form-label">Comentario (Obligatorio para solicitudes declinadas):</label>
                                                <textarea name="comentario" id="comentario" class="form-control" rows="3" placeholder="Ingrese el motivo de la declinación...">{{ old('comentario') }}</textarea>
                                            </div>
                                        </div>
                                        
                                        <!-- Gestión de Documentos -->
                                        <div class="col-12">
                                            <div class="row g-3">
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="liga_de_pago" class="form-label">Subir Liga de Pago:</label>
                                                        <input type="file" name="liga_de_pago" id="liga_de_pago" class="form-control">
                                                        @if($formulario->liga_de_pago)
                                                            <a href="{{ route('finanzas.downloadLigaDePago', $formulario->id) }}" target="_blank" class="btn btn-outline-primary mt-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                                    <path d="M7 11l5 5l5 -5"/>
                                                                    <path d="M12 4l0 12"/>
                                                                </svg>
                                                                Descargar Liga de Pago
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Comprobante de Alumno:</label>
                                                        @if($formulario->comprobante_alumno)
                                                            <a href="{{ route('finanzas.downloadComprobanteAlumno', ['id' => $formulario->id]) }}" target="_blank" class="btn btn-outline-primary d-block">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                                    <path d="M7 11l5 5l5 -5"/>
                                                                    <path d="M12 4l0 12"/>
                                                                </svg>
                                                                Descargar Comprobante de Alumno
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Sin comprobante de alumno</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12" id="comprobante-oficial-div" @if(!$formulario->liga_de_pago || !$formulario->comprobante_alumno) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label for="comprobante_oficial" class="form-label">Subir Comprobante Oficial:</label>
                                                <input type="file" name="comprobante_oficial" id="comprobante_oficial" class="form-control">
                                                @if($formulario->comprobante_oficial)
                                                    <a href="{{ route('finanzas.downloadComprobanteOficial', ['id' => $formulario->id]) }}" target="_blank" class="btn btn-outline-success mt-2">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Mostrar u ocultar el campo de "Comprobante Oficial" según el estado de los otros campos
        document.addEventListener('DOMContentLoaded', function () {
            const ligaDePago = '{{ $formulario->liga_de_pago }}';
            const comprobanteAlumno = '{{ $formulario->comprobante_alumno }}';
            const comprobanteOficialDiv = document.getElementById('comprobante-oficial-div');

            if (ligaDePago && comprobanteAlumno) {
                comprobanteOficialDiv.style.display = 'block';
            } else {
                comprobanteOficialDiv.style.display = 'none';
            }

            // Mostrar/ocultar campo de comentario según estado seleccionado
            const statusSelect = document.getElementById('status-select');
            const comentarioDiv = document.getElementById('comentario-div');
            const comentarioField = document.getElementById('comentario');

            function toggleComentarioField() {
                if (statusSelect.value === 'declinada') {
                    comentarioDiv.style.display = 'block';
                    comentarioField.setAttribute('required', 'required');
                } else {
                    comentarioDiv.style.display = 'none';
                    comentarioField.removeAttribute('required');
                }
            }

            // Ejecutar al cargar la página
            toggleComentarioField();

            // Ejecutar cuando cambie el select
            statusSelect.addEventListener('change', toggleComentarioField);
        });
    </script>
@endsection