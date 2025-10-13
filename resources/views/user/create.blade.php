@extends('tablar::page')

@section('title', 'Crear Usuario')

@section('content')
    <!-- Encabezado de la página -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Pre-título de la página -->
                    <div class="page-pretitle">
                        Crear
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
                            <h3 class="card-title">Detalles del Usuario</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.store') }}" id="ajaxForm" role="form"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label required">Nombre</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   placeholder="Introduce tu nombre" value="{{ old('name') }}">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label required">Correo Electrónico</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                   placeholder="Introduce tu correo electrónico" value="{{ old('email') }}">
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if (auth()->user()->hasRole('admin'))
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="password" 
                                                       class="form-control password-field @error('password') is-invalid @enderror" 
                                                       placeholder="Introduce tu contraseña">
                                                <button type="button" class="input-group-text toggle-password" data-target="password">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Confirmar Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                                       class="form-control password-field @error('password_confirmation') is-invalid @enderror" 
                                                       placeholder="Confirma tu contraseña">
                                                <button type="button" class="input-group-text toggle-password" data-target="password_confirmation">
                                                    <i class="ti ti-eye"></i>
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
                                                <line x1="12" y1="5" x2="12" y2="19"/>
                                                <line x1="5" y1="12" x2="19" y2="12"/>
                                            </svg>
                                            Crear Usuario
                                        </button>
                                    @else
                                        <div class="alert alert-warning w-100 mt-3" role="alert">
                                            <h4 class="alert-title">Sin permisos</h4>
                                            <div class="text-muted">No tienes permiso para crear usuarios.</div>
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
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad para mostrar/ocultar contraseña
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.innerHTML = '<i class="ti ti-eye-off"></i>';
                } else {
                    passwordInput.type = 'password';
                    this.innerHTML = '<i class="ti ti-eye"></i>';
                }
            });
        });

        // Función para comparar contraseñas (como en el registro)
        function checkPasswords() {
            const passwordFields = document.querySelectorAll('.password-field');
            const password1 = passwordFields[0];
            const password2 = passwordFields[1];
            
            if (password1 && password2 && password1.value && password2.value) {
                if (password1.value === password2.value) {
                    // Verde - coinciden
                    password1.style.border = '2px solid green';
                    password2.style.border = '2px solid green';
                } else {
                    // Rojo - no coinciden
                    password1.style.border = '2px solid red';
                    password2.style.border = '2px solid red';
                }
            } else {
                // Reset
                if (password1) password1.style.border = '';
                if (password2) password2.style.border = '';
            }
        }

        // Agregar event listeners para comparación de contraseñas
        document.querySelectorAll('.password-field').forEach(field => {
            field.addEventListener('input', checkPasswords);
        });

        // Inicializar comparación
        checkPasswords();
    });
</script>
@endsection