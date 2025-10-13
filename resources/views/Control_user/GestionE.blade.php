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
                        {{ __('Solicitud ') }}
                    </h2>
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
                                        <span class="text-muted">{{ $formulario->nombre }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">CURP:</strong>
                                        <span class="text-muted text-break">{{ $formulario->curp }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Número de Control:</strong>
                                        <span class="text-muted">{{ $formulario->numero_control }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Especialidad:</strong>
                                        <span class="text-muted">{{ $formulario->especialidad }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Grupo:</strong>
                                        <span class="text-muted">{{ $formulario->grupo }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Tipo de Pago:</strong>
                                        <span class="text-muted">{{ $formulario->tipo_pago }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Fecha de Pago:</strong>
                                        <span class="text-muted">{{ $formulario->fecha_pago }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <strong class="d-block mb-1">Materias:</strong>
                                        <div class="text-muted">
                                            @if($formulario->materias)
                                                <?php $materias = json_decode($formulario->materias, true); ?>
                                                @if(is_array($materias))
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach(array_column($materias, 'nombre') as $materia)
                                                            <span class="border rounded px-2 py-1 small">{{ $materia }}</span>
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
                            <div class="form-group">
                                <strong class="mb-3 d-block">Status:</strong>
                                <form action="{{ route('control_user.updateStatus', $formulario->id) }}" method="POST" id="status-form">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label for="status-select" class="form-label">Estado de la Solicitud</label>
                                            <select name="status" id="status-select" class="form-select">
                                                <option value="aprobada" {{ $formulario->status == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                                <option value="generando_liga_pago" {{ $formulario->status == 'generando_liga_pago' ? 'selected' : '' }}>Generando Liga de Pago</option>
                                                <option value="declinada" {{ $formulario->status == 'declinada' ? 'selected' : '' }}>Declinada</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div id="comentario-div">
                                                <label for="comentario" class="form-label"><strong>Comentario:</strong></label>
                                                <textarea name="comentario" id="comentario" class="form-control" rows="3" placeholder="Ingrese comentarios sobre la solicitud...">{{ $formulario->comentario }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="comentario_financiero" class="form-label"><strong>Comentario a Servicio Financiero:</strong></label>
                                            <textarea name="comentario_financiero" id="comentario_financiero" class="form-control" rows="3" placeholder="Comentarios dirigidos al servicio financiero...">{{ $formulario->comentario_financiero }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Guardar Cambios
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Sección de Comprobantes - Responsiva -->
                            <div class="form-group mt-4">
                                <strong class="mb-3 d-block">Gestión de Comprobantes:</strong>
                                <div class="row g-3">
                                    <!-- Botones de descarga -->
                                    <div class="col-12">
                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                            @if($formulario->comprobante_oficial)
                                                <a href="{{ route('control_user.downloadComprobante', [$formulario->id, 'type' => 'comprobante_oficial']) }}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-download me-2"></i>Descargar Comprobante Oficial
                                                </a>
                                            @endif
                                            @if($formulario->comprobante)
                                                <a href="{{ route('control_user.downloadComprobante', [$formulario->id, 'type' => 'comprobante']) }}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-download me-2"></i>Descargar Comprobante
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Formulario de subida -->
                                    <div class="col-12">
                                        <div class="card border">
                                            <div class="card-body">
                                                <form action="{{ route('control_user.uploadComprobante', $formulario->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <label for="comprobante" class="form-label"><strong>Subir Comprobante:</strong></label>
                                                            <input type="file" name="comprobante" id="comprobante" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="alert alert-warning mb-0" role="alert">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                <strong>Importante:</strong> El tamaño máximo permitido para los archivos es de 10 MB. 
                                                                Formatos aceptados: PDF, JPG, JPEG, PNG.
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-upload me-2"></i>Subir Comprobante
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('status-select').addEventListener('change', function () {
            if (this.value === 'declinada') {
                document.getElementById('comentario').setAttribute('required', 'required');
            } else {
                document.getElementById('comentario').removeAttribute('required');
            }
        });
    </script>
@endsection
