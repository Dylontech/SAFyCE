@extends('tablar::page')

@section('title', 'Gestión de Reportes')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-warning">
                    <i class="ti ti-flag me-2"></i>
                    Gestión de Reportes
                </h2>
                <div class="text-muted mt-1">Administrar reportes de contenido estudiantil</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('moderacion.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Filtros</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('moderacion.reportes.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="en_revision" {{ request('estado') === 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                                <option value="resuelto" {{ request('estado') === 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                                <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Reporte</label>
                            <select name="tipo_reporte" class="form-select">
                                <option value="">Todos los tipos</option>
                                <option value="contenido_inapropiado" {{ request('tipo_reporte') === 'contenido_inapropiado' ? 'selected' : '' }}>Contenido Inapropiado</option>
                                <option value="acoso_bullying" {{ request('tipo_reporte') === 'acoso_bullying' ? 'selected' : '' }}>Acoso/Bullying</option>
                                <option value="spam" {{ request('tipo_reporte') === 'spam' ? 'selected' : '' }}>Spam</option>
                                <option value="informacion_falsa" {{ request('tipo_reporte') === 'informacion_falsa' ? 'selected' : '' }}>Información Falsa</option>
                                <option value="violencia" {{ request('tipo_reporte') === 'violencia' ? 'selected' : '' }}>Violencia</option>
                                <option value="contenido_sexual" {{ request('tipo_reporte') === 'contenido_sexual' ? 'selected' : '' }}>Contenido Sexual</option>
                                <option value="drogas_alcohol" {{ request('tipo_reporte') === 'drogas_alcohol' ? 'selected' : '' }}>Drogas/Alcohol</option>
                                <option value="otros" {{ request('tipo_reporte') === 'otros' ? 'selected' : '' }}>Otros</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Asignado a</label>
                            <select name="asignado_a" class="form-select">
                                <option value="">Sin filtro</option>
                                <option value="yo" {{ request('asignado_a') === 'yo' ? 'selected' : '' }}>Asignado a mí</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i>
                                    Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Reportes -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Reportes ({{ $reportes->total() }} total)
                </h3>
            </div>
            <div class="card-body p-0">
                @if($reportes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th>Tipo</th>
                                    <th>Contenido Reportado</th>
                                    <th>Reportado Por</th>
                                    <th>Fecha</th>
                                    <th>Asignado A</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportes as $reporte)
                                    <tr>
                                        <td>
                                            <span class="badge text-white
                                                @if($reporte->estado === 'pendiente') bg-warning
                                                @elseif($reporte->estado === 'en_revision') bg-info
                                                @elseif($reporte->estado === 'resuelto') bg-success
                                                @else bg-danger
                                                @endif">
                                                {{ ucfirst($reporte->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ ucfirst(str_replace('_', ' ', $reporte->tipo_reporte)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-{{ $reporte->reportable_type === 'App\\Models\\Publicacion' ? 'file-text' : 'message' }} me-2 text-muted"></i>
                                                <div>
                                                    <div class="text-truncate" style="max-width: 200px;">
                                                        @if($reporte->reportable)
                                                            {{ $reporte->reportable_type === 'App\\Models\\Publicacion' ? 'Publicación' : 'Comentario' }}
                                                        @else
                                                            <span class="text-danger">Contenido eliminado</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-muted small">
                                                        {{ Str::limit($reporte->descripcion, 50) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($reporte->reportadoPor)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-initials">{{ strtoupper(substr($reporte->reportadoPor->nombres, 0, 1) . substr($reporte->reportadoPor->apellidos, 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="text-truncate">{{ $reporte->reportadoPor->nombres }} {{ $reporte->reportadoPor->apellidos }}</div>
                                                        <div class="text-muted small">{{ $reporte->reportadoPor->email }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Usuario eliminado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                {{ $reporte->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $reporte->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($reporte->asignadoA)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-initials bg-primary">{{ strtoupper(substr($reporte->asignadoA->name, 0, 1)) }}</span>
                                                    </div>
                                                    <span class="text-truncate">{{ $reporte->asignadoA->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Sin asignar</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('moderacion.reportes.show', $reporte) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                
                                                @if($reporte->estado === 'pendiente' && !$reporte->asignado_a)
                                                    <form method="POST" action="{{ route('moderacion.reportes.asignar', $reporte) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-info" title="Asignar a mí">
                                                            <i class="ti ti-user-plus"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-flag-off fs-1 text-muted mb-3"></i>
                        <h3 class="text-muted">No se encontraron reportes</h3>
                        <p class="text-muted">No hay reportes que coincidan con los filtros seleccionados.</p>
                    </div>
                @endif
            </div>
            
            @if($reportes->hasPages())
                <div class="card-footer">
                    {{ $reportes->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection