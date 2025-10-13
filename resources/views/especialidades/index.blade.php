@extends('tablar::page')

@section('title')
    Especialidades
@endsection

@push('css')
    <link href="{{ asset('css/pagination-responsive.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        List
                    </div>
                    <h2 class="page-title">
                        {{ __('Especialidades') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('especialidades.create') }}" class="btn btn-primary">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span class="d-none d-sm-inline">Create Especialidades</span>
                            <span class="d-sm-none">Crear</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if(config('tablar','display_alert'))
                @include('tablar::common.alert')
            @endif
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Especialidades</h3>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <!-- Filtro de búsqueda funcional -->
                            <form method="GET" action="{{ route('especialidades.index') }}" class="d-flex justify-content-end">
                                <div class="d-flex align-items-center">
                                    <label class="form-label me-2 mb-0 text-muted">Buscar:</label>
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" 
                                               name="search" 
                                               class="form-control form-control-sm" 
                                               value="{{ request('search') }}"
                                               placeholder="Buscar especialidades..."
                                               aria-label="Search especialidades">
                                        <button class="btn btn-outline-secondary btn-sm" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" 
                                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="11" cy="11" r="8"/>
                                                <path d="M21 21l-4.35-4.35"/>
                                            </svg>
                                        </button>
                                        @if(request('search'))
                                            <a href="{{ route('especialidades.index') }}" class="btn btn-outline-danger btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" 
                                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                     stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Vista de tabla para escritorio -->
                        <div class="table-responsive min-vh-100 d-none d-md-block">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox"
                                                           aria-label="Select all invoices"></th>
                                    <th class="w-1">No.
                                        <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="icon icon-sm text-dark icon-thick" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="6 15 12 9 18 15"/>
                                        </svg>
                                    </th>
                                    <th>Nombre</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($especialidades as $especialidade)
                                    <tr>
                                        <td><input class="form-check-input m-0 align-middle" type="checkbox"
                                                   aria-label="Select especialidade"></td>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $especialidade->nombre }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('especialidades.show',$especialidade->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('especialidades.edit',$especialidade->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('especialidades.destroy',$especialidade->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
                                                                    class="dropdown-item text-red"><i
                                                                    class="fa fa-fw fa-trash"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No Data Found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Vista de tarjetas para móviles -->
                        <div class="d-md-none">
                            @forelse ($especialidades as $especialidade)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <input class="form-check-input" type="checkbox"
                                                       aria-label="Select especialidade">
                                            </div>
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <strong>{{ $especialidade->nombre }}</strong>
                                                        <small class="text-muted d-block">#{{ ++$i }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" 
                                                             width="24" height="24" viewBox="0 0 24 24" stroke-width="2" 
                                                             stroke="currentColor" fill="none" stroke-linecap="round" 
                                                             stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <circle cx="12" cy="12" r="1"/>
                                                            <circle cx="12" cy="19" r="1"/>
                                                            <circle cx="12" cy="5" r="1"/>
                                                        </svg>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="{{ route('especialidades.show',$especialidade->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" 
                                                                     width="24" height="24" viewBox="0 0 24 24" stroke-width="2" 
                                                                     stroke="currentColor" fill="none" stroke-linecap="round" 
                                                                     stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <circle cx="12" cy="12" r="2"/>
                                                                    <path d="M12 1l3 6l6 3l-6 3l-3 6l-3 -6l-6 -3l6 -3z"/>
                                                                </svg>
                                                                Ver
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="{{ route('especialidades.edit',$especialidade->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" 
                                                                     width="24" height="24" viewBox="0 0 24 24" stroke-width="2" 
                                                                     stroke="currentColor" fill="none" stroke-linecap="round" 
                                                                     stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"/>
                                                                    <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"/>
                                                                    <line x1="16" y1="5" x2="19" y2="8"/>
                                                                </svg>
                                                                Editar
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('especialidades.destroy',$especialidade->id) }}" 
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        onclick="if(!confirm('¿Está seguro de eliminar esta especialidad?')){return false;}"
                                                                        class="dropdown-item text-danger">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" 
                                                                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2" 
                                                                         stroke="currentColor" fill="none" stroke-linecap="round" 
                                                                         stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <line x1="4" y1="7" x2="20" y2="7"/>
                                                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                                    </svg>
                                                                    Eliminar
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty">
                                    <div class="empty-img">
                                        <img src="{{ asset('vendor/tablar/img/undraw_quitting_time_dm8t.svg') }}" 
                                             height="128" alt="">
                                    </div>
                                    <p class="empty-title">No se encontraron especialidades</p>
                                    <p class="empty-subtitle text-muted">
                                        Intente ajustar su búsqueda o filtro para encontrar lo que está buscando.
                                    </p>
                                    <div class="empty-action">
                                        <a href="{{ route('especialidades.create') }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <line x1="12" y1="5" x2="12" y2="19"/>
                                                <line x1="5" y1="12" x2="19" y2="12"/>
                                            </svg>
                                            Agregar Especialidad
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                       <div class="card-footer d-flex align-items-center">
                            <!-- Información de paginación -->
                            <div class="pagination-info me-auto">
                                <small class="text-muted">
                                    Mostrando {{ $especialidades->firstItem() ?? 0 }} a {{ $especialidades->lastItem() ?? 0 }} 
                                    de {{ $especialidades->total() ?? 0 }} especialidades
                                    @if(request('search'))
                                        <span class="badge bg-secondary ms-1">Filtrado por: "{{ request('search') }}"</span>
                                    @endif
                                </small>
                            </div>
                            
                            <!-- Paginación responsiva -->
                            <div class="pagination-container">
                                <!-- Paginación normal (oculta en móviles muy pequeños) -->
                                <div class="d-none d-sm-block">
                                    {{ $especialidades->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                                
                                <!-- Paginación móvil simplificada -->
                                <div class="d-sm-none pagination-mobile-simple">
                                    @if($especialidades->hasPages())
                                        <div class="pagination-mobile-controls d-flex align-items-center gap-2">
                                            @if($especialidades->onFirstPage())
                                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" 
                                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                         stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <polyline points="15 18 9 12 15 6"/>
                                                    </svg>
                                                </button>
                                            @else
                                                <a href="{{ $especialidades->appends(request()->query())->previousPageUrl() }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" 
                                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                         stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <polyline points="15 18 9 12 15 6"/>
                                                    </svg>
                                                </a>
                                            @endif
                                            
                                            <div class="pagination-page-info px-3">
                                                {{ $especialidades->currentPage() }} / {{ $especialidades->lastPage() }}
                                            </div>
                                            
                                            @if($especialidades->hasMorePages())
                                                <a href="{{ $especialidades->appends(request()->query())->nextPageUrl() }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" 
                                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                         stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <polyline points="9 18 15 12 9 6"/>
                                                    </svg>
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" 
                                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                         stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <polyline points="9 18 15 12 9 6"/>
                                                    </svg>
                                                </a>
                                            @endif
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
