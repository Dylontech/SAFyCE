@extends('tablar::page')

@section('title', 'Detalle del Reporte #' . $reporte->id)

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-warning">
                    <i class="ti ti-flag me-2"></i>
                    Reporte #{{ $reporte->id }}
                </h2>
                <div class="text-muted mt-1">
                    {{ ucfirst(str_replace('_', ' ', $reporte->tipo_reporte)) }} - 
                    <span class="badge text-white
                        @if($reporte->estado === 'pendiente') bg-warning
                        @elseif($reporte->estado === 'en_revision') bg-info
                        @elseif($reporte->estado === 'resuelto') bg-success
                        @else bg-danger
                        @endif">
                        {{ ucfirst($reporte->estado) }}
                    </span>
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('moderacion.reportes.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver a Reportes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <!-- Información del Reporte -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información del Reporte</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Tipo de Reporte:</div>
                            <div class="col-sm-9">
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $reporte->tipo_reporte)) }}</span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Descripción:</div>
                            <div class="col-sm-9">{{ $reporte->descripcion }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Fecha del Reporte:</div>
                            <div class="col-sm-9">{{ $reporte->created_at->format('d/m/Y H:i:s') }} ({{ $reporte->created_at->diffForHumans() }})</div>
                        </div>
                        
                        @if($reporte->evidencias)
                            <div class="row mb-3">
                                <div class="col-sm-3 text-muted">Evidencias:</div>
                                <div class="col-sm-9">
                                    @foreach($reporte->evidencias as $evidencia)
                                        <div class="mb-2">
                                            @if(is_string($evidencia))
                                                <a href="{{ $evidencia }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-external-link me-1"></i>
                                                    Ver Enlace
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contenido Reportado -->
                @if($reporte->reportable)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Contenido Reportado</h3>
                        </div>
                        <div class="card-body">
                            @if($reporte->reportable_type === 'App\\Models\\Publicacion')
                                <div class="d-flex">
                                    <div class="avatar avatar-md me-3">
                                        <span class="avatar-initials">
                                            {{ strtoupper(substr($reporte->reportable->perfil->alumno->nombres, 0, 1) . substr($reporte->reportable->perfil->alumno->apellidos, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="d-flex justify-content-between">
                                            <h4>{{ $reporte->reportable->perfil->alumno->nombres }} {{ $reporte->reportable->perfil->alumno->apellidos }}</h4>
                                            <small class="text-muted">{{ $reporte->reportable->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <div class="mt-2">
                                            <p>{{ $reporte->reportable->contenido }}</p>
                                            @if($reporte->reportable->archivos)
                                                <div class="mt-2">
                                                    @foreach($reporte->reportable->archivos as $archivo)
                                                        <span class="badge bg-info me-1">
                                                            <i class="ti ti-paperclip me-1"></i>
                                                            {{ basename($archivo) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif($reporte->reportable_type === 'App\\Models\\Comentario')
                                <div class="d-flex">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initials">
                                            {{ strtoupper(substr($reporte->reportable->perfil->alumno->nombres, 0, 1) . substr($reporte->reportable->perfil->alumno->apellidos, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $reporte->reportable->perfil->alumno->nombres }} {{ $reporte->reportable->perfil->alumno->apellidos }}</strong>
                                            <small class="text-muted">{{ $reporte->reportable->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <p class="mt-1 mb-0">{{ $reporte->reportable->contenido }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Acciones sobre el contenido -->
                            <div class="mt-3 pt-3 border-top">
                                <div class="btn-group">
                                    @if($reporte->reportable_type === 'App\\Models\\Publicacion')
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarPublicacionModal">
                                            <i class="ti ti-trash me-1"></i>
                                            Eliminar Publicación
                                        </button>
                                    @elseif($reporte->reportable_type === 'App\\Models\\Comentario')
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarComentarioModal">
                                            <i class="ti ti-trash me-1"></i>
                                            Eliminar Comentario
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#bloquearUsuarioModal">
                                        <i class="ti ti-ban me-1"></i>
                                        Bloquear Usuario
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card mt-4">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-trash fs-1 text-muted mb-3"></i>
                            <h3 class="text-muted">Contenido Eliminado</h3>
                            <p class="text-muted">El contenido reportado ya ha sido eliminado.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Panel de Acciones -->
            <div class="col-md-4">
                <!-- Información del Usuario que Reporta -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Reportado Por</h3>
                    </div>
                    <div class="card-body">
                        @if($reporte->reportadoPor)
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg me-3">
                                    <span class="avatar-initials">{{ strtoupper(substr($reporte->reportadoPor->nombres, 0, 1) . substr($reporte->reportadoPor->apellidos, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $reporte->reportadoPor->nombres }} {{ $reporte->reportadoPor->apellidos }}</h4>
                                    <div class="text-muted">{{ $reporte->reportadoPor->email }}</div>
                                    <div class="text-muted small">{{ $reporte->reportadoPor->especialidad->nombre ?? 'Sin especialidad' }}</div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="ti ti-user-off fs-1 mb-2"></i>
                                <p>Usuario eliminado</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Estado del Reporte -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Estado del Reporte</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Estado Actual:</label>
                            <div>
                                <span class="badge text-white
                                    @if($reporte->estado === 'pendiente') bg-warning
                                    @elseif($reporte->estado === 'en_revision') bg-info
                                    @elseif($reporte->estado === 'resuelto') bg-success
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst($reporte->estado) }}
                                </span>
                            </div>
                        </div>

                        @if($reporte->asignadoA)
                            <div class="mb-3">
                                <label class="form-label">Asignado a:</label>
                                <div>{{ $reporte->asignadoA->name }}</div>
                            </div>
                        @endif

                        @if($reporte->fecha_revision)
                            <div class="mb-3">
                                <label class="form-label">Fecha de Revisión:</label>
                                <div>{{ Carbon\Carbon::parse($reporte->fecha_revision)->format('d/m/Y H:i') }}</div>
                            </div>
                        @endif

                        @if($reporte->respuesta_moderador)
                            <div class="mb-3">
                                <label class="form-label">Respuesta del Moderador:</label>
                                <div class="text-muted">{{ $reporte->respuesta_moderador }}</div>
                            </div>
                        @endif

                        @if($reporte->accion_tomada)
                            <div class="mb-3">
                                <label class="form-label">Acción Tomada:</label>
                                <div>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $reporte->accion_tomada)) }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Acciones disponibles -->
                        @if($reporte->estado === 'pendiente' && !$reporte->asignado_a)
                            <form method="POST" action="{{ route('moderacion.reportes.asignar', $reporte) }}" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="ti ti-user-plus me-1"></i>
                                    Asignar a Mí
                                </button>
                            </form>
                        @endif

                        @if(in_array($reporte->estado, ['pendiente', 'en_revision']) && ($reporte->asignado_a === Auth::id() || !$reporte->asignado_a))
                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#resolverReporteModal">
                                <i class="ti ti-check me-1"></i>
                                Resolver Reporte
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Resolver Reporte -->
<div class="modal fade" id="resolverReporteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('moderacion.reportes.resolver', $reporte) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Resolver Reporte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Acción a Tomar</label>
                        <select name="accion_tomada" class="form-select" required>
                            <option value="">Seleccionar acción...</option>
                            <option value="sin_accion">Sin Acción Necesaria</option>
                            <option value="advertencia">Enviar Advertencia</option>
                            <option value="eliminacion_contenido">Eliminar Contenido</option>
                            <option value="bloqueo_temporal">Bloqueo Temporal</option>
                            <option value="bloqueo_permanente">Bloqueo Permanente</option>
                        </select>
                    </div>

                    <div class="mb-3" id="diasBloqueoDiv" style="display: none;">
                        <label class="form-label">Días de Bloqueo</label>
                        <input type="number" name="dias_bloqueo" class="form-control" min="1" max="365">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Respuesta del Moderador</label>
                        <textarea name="respuesta_moderador" class="form-control" rows="4" required placeholder="Explique la decisión tomada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Resolver Reporte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Eliminar Publicación -->
@if($reporte->reportable && $reporte->reportable_type === 'App\\Models\\Publicacion')
<div class="modal fade" id="eliminarPublicacionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('moderacion.publicaciones.eliminar', $reporte->reportable) }}">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Publicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Esta acción eliminará permanentemente la publicación. ¿Estás seguro?
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo de Eliminación</label>
                        <textarea name="motivo" class="form-control" rows="3" required placeholder="Explique por qué se elimina esta publicación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar Publicación</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Modal para Eliminar Comentario -->
@if($reporte->reportable && $reporte->reportable_type === 'App\\Models\\Comentario')
<div class="modal fade" id="eliminarComentarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('moderacion.comentarios.eliminar', $reporte->reportable) }}">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Comentario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Esta acción eliminará permanentemente el comentario. ¿Estás seguro?
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo de Eliminación</label>
                        <textarea name="motivo" class="form-control" rows="3" required placeholder="Explique por qué se elimina este comentario..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar Comentario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Modal para Bloquear Usuario -->
@if($reporte->reportable && method_exists($reporte->reportable, 'perfil'))
<div class="modal fade" id="bloquearUsuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('moderacion.usuarios.bloquear', $reporte->reportable->perfil->alumno_id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bloquear Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Esta acción bloqueará al usuario del sistema social. ¿Estás seguro?
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipo de Bloqueo</label>
                        <select name="tipo_bloqueo" class="form-select" required>
                            <option value="temporal">Temporal</option>
                            <option value="permanente">Permanente</option>
                        </select>
                    </div>

                    <div class="mb-3" id="diasBloqueoUsuarioDiv">
                        <label class="form-label">Días de Bloqueo</label>
                        <input type="number" name="dias" class="form-control" min="1" max="365" value="7">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motivo del Bloqueo</label>
                        <textarea name="motivo" class="form-control" rows="3" required placeholder="Explique el motivo del bloqueo..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detalles Adicionales</label>
                        <textarea name="detalles" class="form-control" rows="2" placeholder="Información adicional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Bloquear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const accionSelect = document.querySelector('select[name="accion_tomada"]');
    const diasBloqueoDiv = document.getElementById('diasBloqueoDiv');
    
    if (accionSelect) {
        accionSelect.addEventListener('change', function() {
            if (this.value === 'bloqueo_temporal') {
                diasBloqueoDiv.style.display = 'block';
                diasBloqueoDiv.querySelector('input').required = true;
            } else {
                diasBloqueoDiv.style.display = 'none';
                diasBloqueoDiv.querySelector('input').required = false;
            }
        });
    }

    const tipoBloqueoSelect = document.querySelector('select[name="tipo_bloqueo"]');
    const diasBloqueoUsuarioDiv = document.getElementById('diasBloqueoUsuarioDiv');
    
    if (tipoBloqueoSelect) {
        tipoBloqueoSelect.addEventListener('change', function() {
            if (this.value === 'temporal') {
                diasBloqueoUsuarioDiv.style.display = 'block';
                diasBloqueoUsuarioDiv.querySelector('input').required = true;
            } else {
                diasBloqueoUsuarioDiv.style.display = 'none';
                diasBloqueoUsuarioDiv.querySelector('input').required = false;
            }
        });
    }
});
</script>
@endpush
@endsection