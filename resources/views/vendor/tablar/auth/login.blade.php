@extends('tablar::auth.layout')
@section('title', 'Inicio de Sesión')
@section('content')
    <div class="container container-tight py-4">
        <div class="d-flex justify-content-end mb-3">
            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Modo Oscuro"
               data-bs-toggle="tooltip"
               data-bs-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"/>
                </svg>
            </a>
            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Modo claro"
               data-bs-toggle="tooltip"
               data-bs-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <circle cx="12" cy="12" r="4"/>
                    <path
                        d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"/>
                </svg>
            </a>
        </div>

        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif
        <div class="text-center mb-1 mt-5">
            <a href="" class="navbar-brand navbar-brand-autodark">
                <img src="{{ asset(config('tablar.auth_logo.img.path','assets/logo.svg')) }}" height="36" alt="">
            </a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Bienvenido</h2>
                <form action="{{ route('login') }}" method="post" autocomplete="off" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico o CURP</label>
                        <input type="text" class="form-control @error('user_identifier') is-invalid @enderror" name="user_identifier" placeholder="Correo Electrónico o CURP" value="{{ old('user_identifier', Cookie::get('user_identifier') ?? '') }}" autocomplete="off">
                        @error('user_identifier')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Contraseña o Número de Control
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña o Número de Control" value="{{ old('password', Cookie::get('password') ?? '') }}" autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" title="Mostrar contraseña o número de control" data-bs-toggle="tooltip" id="toggle-password">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="2"/>
                                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/>
                                    </svg>
                                </a>
                            </span>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <script>
                        document.getElementById('toggle-password').addEventListener('click', function (e) {
                            e.preventDefault();
                            const passwordField = document.getElementById('password');
                            const passwordFieldType = passwordField.getAttribute('type');
                            const togglePassword = document.getElementById('toggle-password');
                            if (passwordFieldType === 'password') {
                                passwordField.setAttribute('type', 'text');
                                togglePassword.setAttribute('title', 'Ocultar contraseña o número de control');
                            } else {
                                passwordField.setAttribute('type', 'password');
                                togglePassword.setAttribute('title', 'Mostrar contraseña o número de control');
                            }
                        });
                    </script>

                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ Cookie::get('remember') ? 'checked' : '' }} />
                            <span class="form-check-label">Recuérdame</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Inicio</button>
                    </div>
                </form>
            </div>
            <div class="hr-text">Ayuda</div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <a href="https://wa.me/{{ $whatsappSettings['phone_number'] }}?text={{ urlencode($whatsappSettings['message']) }}" class="btn btn-white w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-whatsapp" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                                <path d="M3 21l1.65 -4.65a8 8 0 1 1 3.6 3.6l-4.65 1.65m4.65 -1.65a8 8 0 1 1 -3.6 -3.6l4.65 1.65"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection