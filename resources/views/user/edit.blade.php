@extends('tablar::page')

@section('title', 'Editar Usuario')

@section('content')
    <!-- Encabezado de la página -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Pre-título de la página -->
                    <div class="page-pretitle">
                        Editar
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cuerpo de la página -->
    <div class="page-body">
        <div class="container-xl">
            @if(config('tablar','display_alert'))
                @include('tablar::common.alert')
            @endif
            <div class="row row-deck row-cards">
                <div class="col-12 col-lg-8 col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Editar Usuario</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label required">Nombre</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name', $user->name) }}" placeholder="Introduce tu nombre">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label required">Correo Electrónico</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                   value="{{ old('email', $user->email) }}" placeholder="Introduce tu correo electrónico">
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if (auth()->user()->hasRole('admin'))
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nueva Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       placeholder="Deja en blanco si no deseas cambiarla">
                                                <button type="button" class="input-group-text toggle-password" tabindex="-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                         stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                                        <circle cx="12" cy="12" r="2"/>
                                                        <path d="M22 12c0 4.97-8.03 9-10 9s-10-4.03-10-9 8.03-9 10-9 10 4.03 10 9z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Confirmar Nueva Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                                       placeholder="Confirma tu nueva contraseña">
                                                <button type="button" class="input-group-text toggle-password" tabindex="-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                         stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                                        <circle cx="12" cy="12" r="2"/>
                                                        <path d="M22 12c0 4.97-8.03 9-10 9s-10-4.03-10-9 8.03-9 10-9 10 4.03 10 9z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="card-footer d-flex flex-column flex-sm-row justify-content-between align-items-center">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary mb-2 mb-sm-0 w-100 w-sm-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                            <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/>
                                        </svg>
                                        Volver
                                    </a>
                                    @if (auth()->user()->hasRole('admin'))
                                        <button type="submit" class="btn btn-primary w-100 w-sm-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                <path d="M16 5l3 3"/>
                                            </svg>
                                            Actualizar Usuario
                                        </button>
                                    @else
                                        <div class="alert alert-warning w-100 mt-3" role="alert">
                                            <h4 class="alert-title">Sin permisos</h4>
                                            <div class="text-muted">No tienes permiso para editar usuarios.</div>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.querySelectorAll('.toggle-password').forEach(function(element) {
        element.addEventListener('click', function () {
            var passwordInput = this.parentElement.querySelector('input[type="password"], input[type="text"]');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24V24H0z" fill="none"/><line x1="3" y1="3" x2="21" y2="21"/><path d="M10.584 10.587a2 2 0 0 0 2.828 2.83"/><path d="M9.363 5.365a9.466 9.466 0 0 1 2.637 -.365c8 0 10 7 10 7a13.914 13.914 0 0 1 -1.297 2.312"/><path d="M6.368 6.368a13.914 13.914 0 0 0 -3.368 5.632s2 7 10 7a9.466 9.466 0 0 0 5.632 -1.297"/></svg>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24V24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M22 12c0 4.97-8.03 9-10 9s-10-4.03-10-9 8.03-9 10-9 10 4.03 10 9z"/></svg>';
            }
        });
    });
</script>
@endsection



