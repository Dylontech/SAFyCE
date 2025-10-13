@extends('tablar::page')

@section('title', 'Ver Usuario')

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
                        {{ __('Usuario ') }}
                    </h2>
                </div>
                <!-- Acciones del título de la página -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('users.index') }}" class="btn btn-primary">
                            <!-- Descargar icono SVG de http://tabler-icons.io/i/list -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                <line x1="9" y1="6" x2="20" y2="6"/>
                                <line x1="9" y1="12" x2="20" y2="12"/>
                                <line x1="9" y1="18" x2="20" y2="18"/>
                                <line x1="5" y1="6" x2="5" y2="6.01"/>
                                <line x1="5" y1="12" x2="5" y2="12.01"/>
                                <line x1="5" y1="18" x2="5" y2="18.01"/>
                            </svg>
                            <span class="d-none d-sm-inline-block">Lista de Usuarios</span>
                            <span class="d-sm-none">Lista</span>
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                    <path d="M16 5l3 3"/>
                                </svg>
                                <span class="d-none d-sm-inline-block">Editar</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cuerpo de la página -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12 col-lg-8 col-xl-6 mx-auto">
                    @if(config('tablar','display_alert'))
                        @include('tablar::common.alert')
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalles del Usuario</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12 col-sm-4">
                                    <div class="text-muted mb-1">Nombre:</div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                </div>
                                <div class="col-12 col-sm-8 mt-3 mt-sm-0">
                                    <div class="text-muted mb-1">Email:</div>
                                    <div class="fw-bold">{{ $user->email }}</div>
                                </div>
                            </div>
                            
                            @if($user->roles->count() > 0)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="text-muted mb-1">Roles:</div>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary-lt">{{ $role->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="text-muted mb-1">Fecha de registro:</div>
                                    <div class="fw-bold">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                    <div class="text-muted mb-1">Última actualización:</div>
                                    <div class="fw-bold">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex flex-column flex-sm-row justify-content-between align-items-center">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary mb-2 mb-sm-0 w-100 w-sm-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                    <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/>
                                </svg>
                                Volver a la lista
                            </a>
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary w-100 w-sm-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                        <path d="M16 5l3 3"/>
                                    </svg>
                                    Editar Usuario
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
