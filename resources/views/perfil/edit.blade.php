@extends('tablar::page')

@section('title', 'Editar Perfil')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-edit me-2"></i>
                    Editar Perfil
                </h2>
                <div class="text-muted mt-1">Personaliza tu perfil y configuración</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('perfil.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver al Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Personal -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-user me-1"></i>
                                Información Personal
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Datos del alumno (solo lectura) -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" value="{{ $perfil->alumno->Nombre }}" readonly>
                                    <small class="text-muted">Este campo no se puede modificar</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Número de Control</label>
                                    <input type="text" class="form-control" value="{{ $perfil->alumno->numero_control }}" readonly>
                                    <small class="text-muted">Este campo no se puede modificar</small>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Especialidad</label>
                                    <input type="text" class="form-control" value="{{ $perfil->alumno->especialidad }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Semestre</label>
                                    <input type="text" class="form-control" value="{{ $perfil->alumno->semestre }}°" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Grupo</label>
                                    <input type="text" class="form-control" value="{{ $perfil->alumno->Grupo }}" readonly>
                                </div>
                            </div>

                            <!-- Icono personalizado -->
                            <div class="mb-3">
                                <label class="form-label">Icono de Perfil</label>
                                <div class="row" id="iconos-grid">
                                    @foreach($iconosDisponibles as $key => $nombre)
                                        <div class="col-md-3 col-sm-4 col-6 mb-3">
                                            <input type="radio" name="icono_personalizado" value="{{ $key }}" 
                                                   id="icono_{{ $key }}" class="form-check-input d-none"
                                                   {{ $perfil->icono_personalizado === $key ? 'checked' : '' }}>
                                            <label for="icono_{{ $key }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center p-3 icono-option">
                                                <div class="avatar avatar-lg mb-2" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);">
                                                    <i class="ti ti-{{ $key }} text-white" style="font-size: 1.5rem;"></i>
                                                </div>
                                                <small>{{ $nombre }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Biografía -->
                            <div class="mb-3">
                                <label class="form-label">Biografía</label>
                                <textarea name="biografia" class="form-control" rows="3" maxlength="500" placeholder="Cuéntanos algo sobre ti...">{{ old('biografia', $perfil->biografia) }}</textarea>
                                <small class="text-muted">Máximo 500 caracteres</small>
                            </div>

                            <!-- Estado -->
                            <div class="mb-3">
                                <label class="form-label">Estado actual</label>
                                <input type="text" name="estado" class="form-control" maxlength="100" 
                                       value="{{ old('estado', $perfil->estado) }}" 
                                       placeholder="Ej: Estudiando para exámenes finales">
                                <small class="text-muted">Comparte lo que estás haciendo ahora</small>
                            </div>

                            <!-- Materias favoritas -->
                            <div class="mb-3">
                                <label class="form-label">Materias Favoritas</label>
                                <div class="row">
                                    @foreach($materiasDisponibles as $key => $nombre)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <label class="form-check">
                                                <input type="checkbox" name="materias_favoritas[]" value="{{ $key }}" class="form-check-input"
                                                       {{ in_array($key, $perfil->materias_favoritas ?? []) ? 'checked' : '' }}>
                                                <span class="form-check-label">{{ $nombre }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración de Privacidad -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-shield me-1"></i>
                                Configuración de Privacidad
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Visibilidad del Perfil</label>
                                    <select name="configuracion_privacidad[visibilidad_perfil]" class="form-select">
                                        <option value="publico" {{ ($perfil->configuracion_privacidad['visibilidad_perfil'] ?? 'compañeros') === 'publico' ? 'selected' : '' }}>
                                            Público (todos pueden ver)
                                        </option>
                                        <option value="compañeros" {{ ($perfil->configuracion_privacidad['visibilidad_perfil'] ?? 'compañeros') === 'compañeros' ? 'selected' : '' }}>
                                            Solo compañeros de grupo
                                        </option>
                                        <option value="privado" {{ ($perfil->configuracion_privacidad['visibilidad_perfil'] ?? 'compañeros') === 'privado' ? 'selected' : '' }}>
                                            Privado (solo yo)
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Visibilidad de Publicaciones</label>
                                    <select name="configuracion_privacidad[visibilidad_publicaciones]" class="form-select">
                                        <option value="publico" {{ ($perfil->configuracion_privacidad['visibilidad_publicaciones'] ?? 'compañeros') === 'publico' ? 'selected' : '' }}>
                                            Público (todos pueden ver)
                                        </option>
                                        <option value="compañeros" {{ ($perfil->configuracion_privacidad['visibilidad_publicaciones'] ?? 'compañeros') === 'compañeros' ? 'selected' : '' }}>
                                            Solo compañeros de grupo
                                        </option>
                                        <option value="privado" {{ ($perfil->configuracion_privacidad['visibilidad_publicaciones'] ?? 'compañeros') === 'privado' ? 'selected' : '' }}>
                                            Privado (solo yo)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-check form-switch">
                                        <input type="hidden" name="configuracion_privacidad[permitir_comentarios]" value="0">
                                        <input type="checkbox" name="configuracion_privacidad[permitir_comentarios]" value="1" class="form-check-input"
                                               {{ ($perfil->configuracion_privacidad['permitir_comentarios'] ?? true) ? 'checked' : '' }}>
                                        <span class="form-check-label">Permitir comentarios en mis publicaciones</span>
                                    </label>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-check form-switch">
                                        <input type="hidden" name="configuracion_privacidad[notificaciones_activas]" value="0">
                                        <input type="checkbox" name="configuracion_privacidad[notificaciones_activas]" value="1" class="form-check-input"
                                               {{ ($perfil->configuracion_privacidad['notificaciones_activas'] ?? true) ? 'checked' : '' }}>
                                        <span class="form-check-label">Recibir notificaciones de actividad</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('perfil.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-x me-1"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>

                <!-- Formulario separado para redes sociales -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-share me-1"></i>
                            Redes Sociales
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('perfil.redes-sociales.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div id="redes-sociales-container">
                                @php
                                    $plataformasDisponibles = \App\Models\RedSocial::PLATAFORMAS;
                                @endphp
                                
                                @foreach($plataformasDisponibles as $key => $info)
                                    @php
                                        $redExistente = $redesSociales->get($key);
                                    @endphp
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                <i class="{{ $info['icono'] }} me-1" style="color: {{ $info['color'] }};"></i>
                                                {{ $info['nombre'] }}
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="hidden" name="redes_sociales[{{ $loop->index }}][plataforma]" value="{{ $key }}">
                                            <input type="text" name="redes_sociales[{{ $loop->index }}][usuario]" 
                                                   class="form-control" placeholder="Usuario/Handle"
                                                   value="{{ $redExistente ? $redExistente->usuario : '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="url" name="redes_sociales[{{ $loop->index }}][url]" 
                                                   class="form-control" placeholder="URL completa (opcional)"
                                                   value="{{ $redExistente ? $redExistente->url : '' }}">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-check form-switch">
                                                <input type="hidden" name="redes_sociales[{{ $loop->index }}][visible]" value="0">
                                                <input type="checkbox" name="redes_sociales[{{ $loop->index }}][visible]" value="1" class="form-check-input"
                                                       {{ $redExistente && $redExistente->visible ? 'checked' : ($redExistente ? '' : 'checked') }}>
                                                <span class="form-check-label" title="Visible en el perfil"></span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-device-floppy me-1"></i>
                                Actualizar Redes Sociales
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.icono-option {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.icono-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

input[type="radio"]:checked + .icono-option {
    border-color: var(--tblr-primary);
    background-color: var(--tblr-primary);
    color: white;
}

input[type="radio"]:checked + .icono-option small {
    color: white;
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar selección de iconos
    const iconoOptions = document.querySelectorAll('.icono-option');
    iconoOptions.forEach(option => {
        option.addEventListener('click', function() {
            iconoOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Contador de caracteres para biografía
    const biografiaTextarea = document.querySelector('textarea[name="biografia"]');
    if (biografiaTextarea) {
        const contador = document.createElement('small');
        contador.className = 'text-muted float-end';
        biografiaTextarea.parentNode.appendChild(contador);
        
        function actualizarContador() {
            const restantes = 500 - biografiaTextarea.value.length;
            contador.textContent = `${restantes} caracteres restantes`;
            contador.className = restantes < 50 ? 'text-warning float-end' : 'text-muted float-end';
        }
        
        biografiaTextarea.addEventListener('input', actualizarContador);
        actualizarContador();
    }
});
</script>
@endpush
@endsection