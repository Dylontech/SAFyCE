@extends('tablar::page')

@section('title', 'Gestión de Tareas')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-clipboard me-2"></i>
                    Gestión de Tareas
                </h2>
                <div class="text-muted mt-1">Administra las tareas asignadas a tus estudiantes</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('maestros.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver al Dashboard
                    </a>
                    <a href="{{ route('maestros.tareas.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        Nueva Tarea
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filtros -->
        <div class="card mb-4 bg-secondary">
            <div class="card-body">
                <form method="GET" action="{{ route('maestros.tareas.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-light">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="activa" {{ request('estado') === 'activa' ? 'selected' : '' }}>Activa</option>
                            <option value="vencida" {{ request('estado') === 'vencida' ? 'selected' : '' }}>Vencida</option>
                            <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-light">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="">Todos los tipos</option>
                            <option value="tarea" {{ request('tipo') === 'tarea' ? 'selected' : '' }}>Tarea</option>
                            <option value="proyecto" {{ request('tipo') === 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                            <option value="examen" {{ request('tipo') === 'examen' ? 'selected' : '' }}>Examen</option>
                            <option value="practica" {{ request('tipo') === 'practica' ? 'selected' : '' }}>Práctica</option>
                            <option value="ensayo" {{ request('tipo') === 'ensayo' ? 'selected' : '' }}>Ensayo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-light">Búsqueda</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Buscar por título..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-light">&nbsp;</label>
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search me-1"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('maestros.tareas.index') }}" class="btn btn-outline-light">
                                <i class="ti ti-refresh me-1"></i>
                                Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de tareas -->
        <div class="card bg-dark text-light">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title text-white">
                    <i class="ti ti-list me-2"></i>
                    Lista de Tareas ({{ $tareas->total() }})
                </h3>
            </div>
            <div class="card-body p-0">
                @if($tareas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead class="bg-secondary">
                                <tr>
                                    <th>Título</th>
                                    <th>Materia</th>
                                    <th>Tipo</th>
                                    <th>Grupo/Semestre</th>
                                    <th>Fecha Entrega</th>
                                    <th>Puntos</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tareas as $tarea)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $tarea->titulo }}</strong>
                                                <div class="text-muted small">
                                                    {{ Str::limit($tarea->descripcion, 50) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $tarea->materia->nombre }}</span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($tarea->tipo === 'examen') bg-danger
                                                @elseif($tarea->tipo === 'proyecto') bg-warning
                                                @elseif($tarea->tipo === 'practica') bg-success
                                                @else bg-primary
                                                @endif">
                                                {{ ucfirst($tarea->tipo) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $tarea->grupo }}</strong>
                                                <div class="text-muted small">{{ $tarea->semestre }}° Semestre</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $tarea->fecha_entrega->format('d/m/Y') }}
                                                <div class="text-muted small">{{ $tarea->fecha_entrega->format('H:i') }}</div>
                                                @if($tarea->estaVencida())
                                                    <span class="badge bg-danger">Vencida</span>
                                                @elseif($tarea->diasRestantes() <= 3)
                                                    <span class="badge bg-warning">{{ $tarea->diasRestantes() }}d restantes</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $tarea->puntos_totales }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($tarea->estado === 'activa') bg-success
                                                @elseif($tarea->estado === 'vencida') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($tarea->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('maestros.tareas.show', $tarea) }}" 
                                                   class="btn btn-sm btn-outline-info" title="Ver">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('maestros.tareas.edit', $tarea) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('maestros.tareas.toggle-estado', $tarea) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-{{ $tarea->estado === 'activa' ? 'danger' : 'success' }}" 
                                                            title="{{ $tarea->estado === 'activa' ? 'Cancelar' : 'Activar' }}">
                                                        <i class="ti ti-{{ $tarea->estado === 'activa' ? 'x' : 'check' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-clipboard-off fs-1 text-muted mb-3"></i>
                        <h3 class="text-muted">No hay tareas registradas</h3>
                        <p class="text-muted">Comienza creando tu primera tarea</p>
                        <a href="{{ route('maestros.tareas.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>
                            Crear Primera Tarea
                        </a>
                    </div>
                @endif
            </div>
            
            @if($tareas->hasPages())
                <div class="card-footer bg-transparent">
                    {{ $tareas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
