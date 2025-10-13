@extends('tablar::page')

@section('title', 'Ver Imagen del Carrusel')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Ver
                    </div>
                    <h2 class="page-title">
                        {{ __('Imagen del carrusel') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <!-- Botón de Editar -->
                        <a href="{{ route('carrusels.edit', $carrusel->id) }}" class="btn btn-success d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Editar
                        </a>
                        <!-- Botón de Lista -->
                        <a href="{{ route('carrusels.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Icono de flecha hacia la izquierda -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12,19 5,12 12,5"/>
                            </svg>
                            Ver carrusel
                        </a>
                        <!-- Botones compactos para móviles -->
                        <div class="d-sm-none">
                            <a href="{{ route('carrusels.edit', $carrusel->id) }}" class="btn btn-success btn-sm me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                    <path d="M16 5l3 3"/>
                                </svg>
                            </a>
                            <a href="{{ route('carrusels.index') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="19" y1="12" x2="5" y2="12"/>
                                    <polyline points="12,19 5,12 12,5"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12 col-lg-10 col-xl-8 mx-auto">
                    @if(config('tablar','display_alert'))
                        @include('tablar::common.alert')
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalles de la imagen</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Descripción -->
                                    <div class="mb-4">
                                        <label class="form-label text-muted">Descripción</label>
                                        <div class="h5 mb-0">{{ $carrusel->Description }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Imagen responsiva -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label text-muted">Imagen</label>
                                        <div class="card mt-2">
                                            <div class="card-body text-center p-2">
                                                <img src="{{ route('carrusel.image', ['id' => $carrusel->id]) }}" 
                                                     alt="Imagen para {{ $carrusel->Description }}" 
                                                     class="img-fluid rounded shadow-sm"
                                                     style="max-width: 100%; height: auto; max-height: 500px; cursor: pointer;"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#imageModal">
                                                <div class="mt-2">
                                                    <small class="text-muted">Haz clic para ver en tamaño completo</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Botones de acción -->
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <a href="{{ route('carrusels.edit', $carrusel->id) }}" class="btn btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                        <path d="M16 5l3 3"/>
                                    </svg>
                                    Editar imagen
                                </a>
                                <a href="{{ route('carrusels.index') }}" class="btn btn-outline-primary flex-fill">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="19" y1="12" x2="5" y2="12"/>
                                        <polyline points="12,19 5,12 12,5"/>
                                    </svg>
                                    Volver al carrusel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para vista completa de la imagen -->
    <div class="modal modal-blur fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $carrusel->Description }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ route('carrusel.image', ['id' => $carrusel->id]) }}" 
                         alt="Imagen para {{ $carrusel->Description }}" 
                         class="img-fluid rounded">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="{{ route('carrusels.edit', $carrusel->id) }}" class="btn btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
