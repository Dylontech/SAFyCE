<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <label class="form-label required">Nombre</label>
            <input type="text" name="name" 
                   class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $user->name ?? '') }}" 
                   placeholder="Introduce tu nombre"
                   required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Introduce el <b>nombre completo</b> del usuario.</small>
        </div>
    </div>
    
    <div class="col-12">
        <div class="mb-3">
            <label class="form-label required">Correo Electrónico</label>
            <input type="email" name="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email', $user->email ?? '') }}" 
                   placeholder="Introduce tu correo electrónico"
                   required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Introduce un <b>correo electrónico válido</b> para el usuario.</small>
        </div>
    </div>

    @if (auth()->user()->hasRole('admin'))
    <div class="col-12 col-md-6">
        <div class="mb-3">
            <label class="form-label {{ !isset($user) ? 'required' : '' }}">
                @if(isset($user))
                    Nueva Contraseña
                @else
                    Contraseña
                @endif
            </label>
            <div class="input-group">
                <input type="password" name="password" id="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       placeholder="@if(isset($user))Deja en blanco si no deseas cambiarla@else Introduce tu contraseña @endif"
                       @if(!isset($user)) required @endif>
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
            <small class="form-hint">
                @if(isset($user))
                    Deja este campo vacío si no deseas cambiar la contraseña.
                @else
                    Debe tener al menos 8 caracteres.
                @endif
            </small>
        </div>
    </div>
    
    <div class="col-12 col-md-6">
        <div class="mb-3">
            <label class="form-label {{ !isset($user) ? 'required' : '' }}">
                Confirmar @if(isset($user))Nueva @endif Contraseña
            </label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                       placeholder="Confirma tu @if(isset($user))nueva @endif contraseña"
                       @if(!isset($user)) required @endif>
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
            <small class="form-hint">Repite la contraseña para confirmarla.</small>
        </div>
    </div>
    @else
    <div class="col-12">
        <div class="alert alert-info" role="alert">
            <h4 class="alert-title">Información</h4>
            <div class="text-muted">Solo los administradores pueden configurar contraseñas.</div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>

