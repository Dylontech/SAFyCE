@extends('tablar::auth.layout')
@section('title', 'Registrar')
@section('content')
    <div class="container container-tight py-4">
        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="text-center mb-1 mt-5">
            <a href="" class="navbar-brand navbar-brand-autodark">
                <img src="{{asset(config('tablar.auth_logo.img.path','assets/logo.svg'))}}" height="36" alt="">
            </a>
        </div>
        
        <form class="card card-md" action="{{route('register')}}" method="post" autocomplete="off" novalidate>
            @csrf
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Crear nueva cuenta</h2>
                
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" placeholder="Ingresa tu nombre" value="{{ old('name') }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" placeholder="Ingresa tu correo electrónico" value="{{ old('email') }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control password-field" placeholder="Contraseña" autocomplete="off">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control password-field" placeholder="Confirmar Contraseña" autocomplete="off">
                </div>

                <!-- Validación de términos -->
                @error('acepto_terminos')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Componente de términos y condiciones -->
                <x-avisos-privacidad />

                <div class="mb-3">
                    <div class="form-check">
                        <span class="form-check-label">Al registrarte, aceptas los <a href="#" data-bs-toggle="modal" data-bs-target="#terminosModal">términos y condiciones</a>.</span>
                    </div>
                </div>
                
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100" id="submitButton" disabled>Crear nueva cuenta</button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted mt-3">
            ¿Ya tienes una cuenta? <a href="{{route('login')}}" tabindex="-1">Iniciar sesión</a>
        </div>
    </div>

    <script>
        // Función simple y directa
        function checkPasswords() {
            const passwordFields = document.querySelectorAll('.password-field');
            const password1 = passwordFields[0];
            const password2 = passwordFields[1];
            
            if (password1.value && password2.value) {
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
                password1.style.border = '';
                password2.style.border = '';
            }
        }

        // Función para verificar términos y habilitar/deshabilitar botón
        function checkTerms() {
            const termsCheckbox = document.getElementById('acepto_terminos');
            const submitButton = document.getElementById('submitButton');
            
            if (termsCheckbox && submitButton) {
                submitButton.disabled = !termsCheckbox.checked;
                
                // Cambiar estilo visual cuando está deshabilitado
                if (submitButton.disabled) {
                    submitButton.style.opacity = '0.6';
                    submitButton.style.cursor = 'not-allowed';
                } else {
                    submitButton.style.opacity = '1';
                    submitButton.style.cursor = 'pointer';
                }
            }
        }

        // Agregar event listeners directamente
        document.querySelectorAll('.password-field').forEach(field => {
            field.addEventListener('input', checkPasswords);
        });

        // Event listener para el checkbox de términos
        document.addEventListener('DOMContentLoaded', function() {
            const termsCheckbox = document.getElementById('acepto_terminos');
            if (termsCheckbox) {
                termsCheckbox.addEventListener('change', checkTerms);
            }
            
            // Inicializar estados
            checkPasswords();
            checkTerms();
        });
    </script>
@endsection