@extends('tablar::page')

@section('title', 'Biblioteca Virtual - Administración')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Administración
                    </div>
                    <h2 class="page-title">
                        <i class="fas fa-book-open me-2"></i>
                        Biblioteca Virtual
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('biblioteca-virtual.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <i class="fas fa-plus me-1"></i>
                            Agregar Recurso
                        </a>
                        <a href="{{ route('biblioteca-virtual.create') }}" class="btn btn-primary d-sm-none btn-icon">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="fas fa-check-circle me-2"></i>
                        </div>
                        <div>
                            {{ session('success') }}
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list me-2"></i>
                                Recursos de Biblioteca Virtual
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($recursos->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Categoría</th>
                                                <th>URL</th>
                                                <th>Estado</th>
                                                <th>Orden</th>
                                                <th class="w-1">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recursos as $recurso)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="{{ $recurso->icono_display }} text-muted me-2"></i>
                                                            <div>
                                                                <div class="fw-bold">{{ $recurso->nombre }}</div>
                                                                @if($recurso->descripcion)
                                                                    <small class="text-muted">{{ Str::limit($recurso->descripcion, 60) }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-azure">
                                                            {{ $categorias[$recurso->categoria] ?? $recurso->categoria }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ $recurso->url }}" target="_blank" class="text-decoration-none">
                                                            <i class="fas fa-external-link-alt me-1"></i>
                                                            {{ Str::limit($recurso->url, 40) }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="{{ route('biblioteca-virtual.toggle-activo', $recurso) }}" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-{{ $recurso->activo ? 'success' : 'secondary' }} border-0">
                                                                <i class="fas fa-{{ $recurso->activo ? 'check' : 'times' }} me-1"></i>
                                                                {{ $recurso->activo ? 'Activo' : 'Inactivo' }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $recurso->orden }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-list flex-nowrap">
                                                            <a href="{{ route('biblioteca-virtual.edit', $recurso) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form method="POST" action="{{ route('biblioteca-virtual.destroy', $recurso) }}" class="d-inline" 
                                                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar este recurso?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-muted">
                                        Mostrando {{ $recursos->firstItem() }} a {{ $recursos->lastItem() }} de {{ $recursos->total() }} recursos
                                    </div>
                                    {{ $recursos->links() }}
                                </div>
                            @else
                                <div class="empty">
                                    <div class="empty-img">
                                        <i class="fas fa-book-open fa-3x text-muted"></i>
                                    </div>
                                    <p class="empty-title">No hay recursos registrados</p>
                                    <p class="empty-subtitle text-muted">
                                        Comienza agregando recursos de biblioteca virtual para tus estudiantes.
                                    </p>
                                    <div class="empty-action">
                                        <a href="{{ route('biblioteca-virtual.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>
                                            Agregar primer recurso
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    .table td {
        vertical-align: middle;
    }
    
    .btn-list {
        gap: 0.25rem;
    }
    
    .empty-img {
        margin-bottom: 1rem;
    }
</style>
@endsection
