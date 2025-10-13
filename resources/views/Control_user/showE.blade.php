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
                        <a href="{{ route('control_user.index') }}" class="btn btn-primary">
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
                                        <span class="d-block">{{ $formulario->numero_control }}</span>
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
                                        <strong class="d-block mb-1">Tipo de Pago:</strong>
                                        <span class="d-block">{{ $formulario->tipo_pago }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Fecha de Pago:</strong>
                                        <span class="d-block">{{ $formulario->fecha_pago }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Materias:</strong>
                                        <div>
                                            @if($formulario->materias)
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
                            </div>

                            <!-- Formulario de Status - Responsivo -->
                            <div class="form-group border-top pt-4">
                                <strong class="mb-3 d-block">Estado de la Solicitud:</strong>
                                <form action="{{ route('control_user.updateStatus', $formulario->id) }}" method="POST" id="status-form">
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
                                            <input type="hidden" name="comentario" value="1">
                                            <input type="hidden" name="comentario_financiero" value="1">
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
                            
                            <!-- Gestión de Comprobantes -->
                            <div class="form-group border-top pt-4">
                                <strong class="mb-3 d-block">Gestión de Comprobantes:</strong>
                                <div class="row g-3">
                                    @if($formulario->comprobante_oficial)
                                        <div class="col-12">
                                            <div class="card border-success">
                                                <div class="card-body">
                                                    <h6 class="card-title text-success">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <circle cx="12" cy="12" r="9"/>
                                                            <path d="M9 12l2 2l4 -4"/>
                                                        </svg>
                                                        Comprobante Oficial Disponible
                                                    </h6>
                                                    <a href="{{ route('formularios.downloadComprobanteOficial', $formulario->id) }}" 
                                                       target="_blank" class="btn btn-outline-success">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                            <path d="M7 11l5 5l5 -5"/>
                                                            <path d="M12 4l0 12"/>
                                                        </svg>
                                                        Descargar Comprobante Oficial
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Formulario para subir comprobante del estudiante -->
                                    <div class="col-12">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-title">Subir Comprobante del Estudiante</h6>
                                                <form action="{{ route('formularios.uploadStudentReceipt', $formulario->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <label for="comprobante" class="form-label">Seleccionar archivo:</label>
                                                            <input type="file" class="form-control" name="comprobante" id="comprobante" accept=".pdf,.jpg,.jpeg,.png" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="alert alert-info mb-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <circle cx="12" cy="12" r="9"/>
                                                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                                                    <polyline points="11 12 12 12 12 16 13 16"/>
                                                                </svg>
                                                                <strong>Formatos aceptados:</strong> PDF, JPG, JPEG, PNG. 
                                                                <strong>Tamaño máximo:</strong> 10 MB.
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
                                    
                                    @if($formulario->comprobante)
                                        <div class="col-12">
                                            <div class="card border-info">
                                                <div class="card-body">
                                                    <h6 class="card-title text-info">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                                            <line x1="9" y1="9" x2="10" y2="9"/>
                                                            <line x1="9" y1="13" x2="15" y2="13"/>
                                                            <line x1="9" y1="17" x2="15" y2="17"/>
                                                        </svg>
                                                        Comprobante del Estudiante
                                                    </h6>
                                                    <a href="{{ route('formularios.downloadStudentReceipt', $formulario->id) }}" 
                                                       target="_blank" class="btn btn-outline-info">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                                            <path d="M7 11l5 5l5 -5"/>
                                                            <path d="M12 4l0 12"/>
                                                        </svg>
                                                        Descargar Comprobante
                                                    </a>
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
            toggleCommentRequired();
            statusSelect.addEventListener('change', toggleCommentRequired);
        });
    </script>
@endsection