@extends('tablar::page')

@section('title')
    Carrusel
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        {{ __('Imágenes ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <!-- Botón completo para escritorio -->
                        <a href="{{ route('carrusels.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Nueva imagen
                        </a>
                        <!-- Botón compacto para móviles -->
                        <a href="{{ route('carrusels.create') }}" class="btn btn-primary d-sm-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span class="d-none d-xs-inline-block">Agregar</span>
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
            
            <!-- Advertencia sobre tamaño de imágenes - Responsiva -->
            <div class="alert alert-info mt-3">
                <div class="d-flex flex-column flex-sm-row">
                    <div class="align-self-start me-sm-3 mb-2 mb-sm-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            <polyline points="11 12 12 12 12 16 13 16"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h4 class="alert-title mb-1">Recomendación de formato</h4>
                        <div class="text-muted">
                            Para una visualización óptima, se recomienda utilizar imágenes con dimensiones de <strong>1024×1024 píxeles</strong> y formato <strong>PNG</strong>.
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card" style="min-height: 600px;">
                        <div class="card-header">
                            <h3 class="card-title">Nuevas novedades</h3>
                        </div>
                        
                        <!-- Tabla responsive con altura mínima aumentada -->
                        <div class="table-responsive" style="min-height: 500px;">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="text-nowrap">Descripción</th>
                                    <th class="text-nowrap text-center">Acciones</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($carrusels as $carrusel)
                                    <tr>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $carrusel->Description }}">
                                                {{ $carrusel->Description }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-list justify-content-center">
                                                <!-- Dropdown único para todas las pantallas -->
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" 
                                                            data-bs-toggle="dropdown" 
                                                            aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/>
                                                            <circle cx="12" cy="12" r="3"/>
                                                        </svg>
                                                        Acciones
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{ route('carrusels.show',$carrusel->id) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <circle cx="10" cy="10" r="7"/>
                                                                <line x1="21" y1="21" x2="15" y2="15"/>
                                                            </svg>
                                                            Ver detalles
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('carrusels.edit',$carrusel->id) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                                <path d="M16 5l3 3"/>
                                                            </svg>
                                                            Editar
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('carrusels.destroy',$carrusel->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    onclick="return confirm('¿Está seguro de eliminar esta imagen?')"
                                                                    class="dropdown-item text-danger">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <polyline points="3,6 5,6 21,6"/>
                                                                    <path d="M19 6v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-14m5 0v-2a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v2"/>
                                                                </svg>
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5">
                                            <div class="empty">
                                                <div class="empty-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <rect x="4" y="4" width="16" height="12" rx="2"/>
                                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                                        <polyline points="21,15 16,10 5,21"/>
                                                    </svg>
                                                </div>
                                                <p class="empty-title">Sin novedades</p>
                                                <p class="empty-subtitle text-muted">
                                                    Agrega tu primera imagen para comenzar
                                                </p>
                                                <div class="empty-action">
                                                    <a href="{{ route('carrusels.create') }}" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                                        </svg>
                                                        Subir primera imagen
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                       <div class="card-footer d-flex align-items-center">
                            {!! $carrusels->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection