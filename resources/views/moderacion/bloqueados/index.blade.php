@extends('tablar::page')

@section('title', 'Usuarios Bloqueados')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-danger">
                    <i class="ti ti-ban me-2"></i>
                    Usuarios Bloqueados
                </h2>
                <div class="text-muted mt-1">Gestión de estudiantes con restricciones en el sistema social</div>
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
        <!-- Estadísticas rápidas -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h3 mb-0 text-danger">{{ $bloqueados->total() }}</div>
                                <div class="text-muted">Total Bloqueados</div>
                            </div>
                            <i class="ti ti-ban fs-1 text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h3 mb-0 text-warning">{{ $bloqueados->where('tipo_bloqueo', 'temporal')->count() }}</div>
                                <div class="text-muted">Bloqueos Temporales</div>
                            </div>
                            <i class="ti ti-clock fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h3 mb-0 text-danger">{{ $bloqueados->where('tipo_bloqueo', 'permanente')->count() }}</div>
                                <div class="text-muted">Bloqueos Permanentes</div>
                            </div>
                            <i class="ti ti-ban fs-1 text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="h3 mb-0 text-info">{{ $bloqueados->where('tipo_bloqueo', 'temporal')->filter(function($b) { return $b->diasRestantes() !== null && $b->diasRestantes() <= 7; })->count() }}</div>
                                <div class="text-muted">Expiran Pronto</div>
                            </div>
                            <i class="ti ti-calendar-time fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de usuarios bloqueados -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Usuarios Bloqueados Activos ({{ $bloqueados->total() }} total)
                </h3>
            </div>
            <div class="card-body p-0">
                @if($bloqueados->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Tipo de Bloqueo</th>
                                    <th>Motivo</th>
                                    <th>Bloqueado Por</th>
                                    <th>Fecha de Inicio</th>
                                    <th>Fecha de Fin</th>
                                    <th>Estado</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bloqueados as $bloqueo)
                                    <tr>
                                        <td>
                                            @if($bloqueo->alumno)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initials">{{ strtoupper(substr($bloqueo->alumno->nombres, 0, 1) . substr($bloqueo->alumno->apellidos, 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="text-truncate">
                                                            <strong>{{ $bloqueo->alumno->nombres }} {{ $bloqueo->alumno->apellidos }}</strong>
                                                        </div>
                                                        <div class="text-muted small">{{ $bloqueo->alumno->email }}</div>
                                                        @if($bloqueo->alumno->especialidad)
                                                            <div class="text-muted small">{{ $bloqueo->alumno->especialidad->nombre }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Estudiante eliminado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge text-white
                                                @if($bloqueo->tipo_bloqueo === 'temporal') bg-warning
                                                @else bg-danger
                                                @endif">
                                                {{ ucfirst($bloqueo->tipo_bloqueo) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $bloqueo->motivo }}">
                                                {{ $bloqueo->motivo }}
                                            </div>
                                            @if($bloqueo->detalles)
                                                <div class="text-muted small text-truncate" style="max-width: 200px;" title="{{ $bloqueo->detalles }}">
                                                    {{ $bloqueo->detalles }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bloqueo->bloqueadoPor)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xs me-2">
                                                        <span class="avatar-initials bg-primary">{{ strtoupper(substr($bloqueo->bloqueadoPor->name, 0, 1)) }}</span>
                                                    </div>
                                                    <span class="text-truncate">{{ $bloqueo->bloqueadoPor->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Moderador eliminado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                {{ $bloqueo->fecha_inicio->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $bloqueo->fecha_inicio->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($bloqueo->fecha_fin)
                                                <div class="text-muted">
                                                    {{ $bloqueo->fecha_fin->format('d/m/Y H:i') }}
                                                </div>
                                                @php $diasRestantes = $bloqueo->diasRestantes() @endphp
                                                @if($diasRestantes !== null)
                                                    <div class="small
                                                        @if($diasRestantes <= 1) text-danger
                                                        @elseif($diasRestantes <= 7) text-warning
                                                        @else text-info
                                                        @endif">
                                                        @if($diasRestantes > 0)
                                                            {{ $diasRestantes }} día{{ $diasRestantes !== 1 ? 's' : '' }} restante{{ $diasRestantes !== 1 ? 's' : '' }}
                                                        @else
                                                            Expirado
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">Permanente</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bloqueo->estaActivo())
                                                <span class="badge bg-danger">Activo</span>
                                            @else
                                                <span class="badge bg-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bloqueo->estaActivo())
                                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#desbloquearModal{{ $bloqueo->id }}">
                                                    <i class="ti ti-lock-open"></i>
                                                    Desbloquear
                                                </button>
                                            @else
                                                <span class="text-muted small">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-ban-off fs-1 text-muted mb-3"></i>
                        <h3 class="text-muted">No hay usuarios bloqueados</h3>
                        <p class="text-muted">Actualmente no hay estudiantes con restricciones activas.</p>
                    </div>
                @endif
            </div>
            
            @if($bloqueados->hasPages())
                <div class="card-footer">
                    {{ $bloqueados->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modales para desbloquear usuarios -->
@foreach($bloqueados as $bloqueo)
    @if($bloqueo->estaActivo())
        <div class="modal fade" id="desbloquearModal{{ $bloqueo->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('moderacion.bloqueados.desbloquear', $bloqueo) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Desbloquear Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                ¿Estás seguro de que deseas desbloquear a <strong>{{ $bloqueo->alumno ? $bloqueo->alumno->nombres . ' ' . $bloqueo->alumno->apellidos : 'este usuario' }}</strong>?
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Información del Bloqueo:</label>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4 text-muted">Tipo:</div>
                                            <div class="col-sm-8">{{ ucfirst($bloqueo->tipo_bloqueo) }}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4 text-muted">Motivo:</div>
                                            <div class="col-sm-8">{{ $bloqueo->motivo }}</div>
                                        </div>
                                        @if($bloqueo->fecha_fin)
                                            <div class="row">
                                                <div class="col-sm-4 text-muted">Fecha Fin:</div>
                                                <div class="col-sm-8">{{ $bloqueo->fecha_fin->format('d/m/Y H:i') }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Razón del Levantamiento</label>
                                <textarea name="razon_levantamiento" class="form-control" rows="3" required placeholder="Explique por qué se levanta este bloqueo..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-lock-open me-1"></i>
                                Desbloquear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection