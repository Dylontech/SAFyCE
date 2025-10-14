@extends('tablar::page')

@section('title', 'Solicitud de Servicios')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <h1 class="text-center mb-4 fs-2 fs-md-1">Solicitud de Servicios</h1>
            <div class="alert alert-warning text-center">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Nota:</strong> Por favor, verifique que todos sus datos sean correctos antes de enviar el formulario.
            </div>
            <form action="/ruta-de-envio" method="post">
                @csrf
                
                <!-- Sección de datos del alumno -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i>Datos del Alumno</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="nombre" class="form-label">Nombre del Alumno</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', Auth::guard('alumno')->user()->Nombre ?? '') }}" placeholder="Ingrese el nombre completo">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="control" class="form-label">No. de Control</label>
                                <input type="text" id="control" name="control" class="form-control" value="{{ old('control', Auth::guard('alumno')->user()->numero_control ?? '') }}" placeholder="Ej: 12345678">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="curp" class="form-label">CURP</label>
                                <input type="text" id="curp" name="curp" class="form-control" value="{{ old('curp', Auth::guard('alumno')->user()->CURP ?? '') }}" placeholder="18 caracteres" maxlength="18">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="especialidad" class="form-label">Especialidad</label>
                                <input type="text" id="especialidad" name="especialidad" class="form-control" value="{{ old('especialidad', Auth::guard('alumno')->user()->especialidad ?? '') }}" placeholder="Especialidad académica">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="grupo" class="form-label">Grupo</label>
                                <select id="grupo" name="grupo" class="form-select">
                                    <option value="">Primero selecciona un semestre</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="semestre" class="form-label">Semestre que Cursa</label>
                                <select id="semestre" name="semestre" class="form-select">
                                    <option value="">Seleccione semestre</option>
                                    <option value="1" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '1' ? 'selected' : '' }}>Primer Semestre</option>
                                    <option value="2" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '2' ? 'selected' : '' }}>Segundo Semestre</option>
                                    <option value="3" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '3' ? 'selected' : '' }}>Tercer Semestre</option>
                                    <option value="4" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '4' ? 'selected' : '' }}>Cuarto Semestre</option>
                                    <option value="5" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '5' ? 'selected' : '' }}>Quinto Semestre</option>
                                    <option value="6" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '6' ? 'selected' : '' }}>Sexto Semestre</option>
                                    <option value="7" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '7' ? 'selected' : '' }}>Séptimo Semestre</option>
                                    <option value="8" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '8' ? 'selected' : '' }}>Octavo Semestre</option>
                                    <option value="9" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '9' ? 'selected' : '' }}>Noveno Semestre</option>
                                    <option value="10" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '10' ? 'selected' : '' }}>Décimo Semestre</option>
                                    <option value="11" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '11' ? 'selected' : '' }}>Onceavo Semestre</option>
                                    <option value="12" {{ old('semestre', Auth::guard('alumno')->user()->semestre ?? '') == '12' ? 'selected' : '' }}>Doceavo Semestre</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="fecha" class="form-label">Fecha de Solicitud</label>
                                <input type="date" id="fecha" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sección de tipo de servicio -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-clipboard-list me-2"></i>Tipo de Servicio Solicitado</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="tipo_servicio" class="form-label fw-bold">Seleccione el servicio que necesita</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="fas fa-tasks"></i></span>
                                    <select id="tipo_servicio" name="tipo_servicio" class="form-select form-select-lg shadow-sm border-success" required>
                                        <option value="">Selecciona un tipo de servicio</option>
                                        <option value="Constancia de Inscripción y/o Estudios" {{ old('tipo_servicio') == 'Constancia de Inscripción y/o Estudios' ? 'selected' : '' }}>
                                            📄 Constancia de Inscripción y/o Estudios
                                        </option>
                                        <option value="Duplicado de Credencial" {{ old('tipo_servicio') == 'Duplicado de Credencial' ? 'selected' : '' }}>
                                            🆔 Duplicado de Credencial
                                        </option>
                                        <option value="Certificado Incompleto (Parcial)" {{ old('tipo_servicio') == 'Certificado Incompleto (Parcial)' ? 'selected' : '' }}>
                                            📋 Certificado Incompleto (Parcial)
                                        </option>
                                        <option value="Duplicado de Certificado de Estudios" {{ old('tipo_servicio') == 'Duplicado de Certificado de Estudios' ? 'selected' : '' }}>
                                            📜 Duplicado de Certificado de Estudios
                                        </option>
                                        <option value="Examen de Titulación (Protocolo)" {{ old('tipo_servicio') == 'Examen de Titulación (Protocolo)' ? 'selected' : '' }}>
                                            🎓 Examen de Titulación (Protocolo)
                                        </option>
                                        <option value="Titulación (Tit. y Exp. de Ced. Prof.)" {{ old('tipo_servicio') == 'Titulación (Tit. y Exp. de Ced. Prof.)' ? 'selected' : '' }}>
                                            🏆 Titulación (Tit. y Exp. de Ced. Prof.)
                                        </option>
                                        <option value="reinscripcion" {{ old('tipo_servicio') == 'reinscripcion' ? 'selected' : '' }}>
                                            🔄 Reinscripción
                                        </option>
                                    </select>
                                </div>
                                <div class="form-text">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Seleccione cuidadosamente el tipo de servicio que requiere. Algunos servicios pueden tener costos adicionales.
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información adicional del servicio -->
                        <div class="mt-4" id="servicio-info" style="display: none;">
                            <div class="alert alert-info">
                                <h6 class="alert-heading mb-2"><i class="fas fa-lightbulb me-2"></i>Información del Servicio</h6>
                                <p class="mb-0" id="servicio-descripcion"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de observaciones 
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-comment-alt me-2"></i>Observaciones (Opcional)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="observaciones" class="form-label">Comentarios adicionales</label>
                                <textarea id="observaciones" name="observaciones" class="form-control" rows="4" placeholder="Si tiene alguna observación o comentario adicional sobre su solicitud, puede escribirlo aquí...">{{ old('observaciones') }}</textarea>
                                <div class="form-text">
                                    <small class="text-muted">Este campo es opcional. Máximo 500 caracteres.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
                <!-- Botón de envío -->
                <div class="form-footer">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                        </button>
                        <button type="reset" class="btn btn-outline-secondary btn-lg px-4 py-3">
                            <i class="fas fa-undo me-2"></i>Limpiar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('css')
<style>
    /* Estilos adicionales para mejorar la responsividad */
    @media (max-width: 575.98px) {
        .container-fluid {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        
        .card-header h5 {
            font-size: 1.1rem;
        }
        
        .btn-lg {
            width: 100%;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        
        .d-grid .btn + .btn {
            margin-top: 10px;
        }
    }
    
    @media (max-width: 767.98px) {
        .form-select-lg {
            font-size: 1rem;
            padding: 0.75rem;
        }
        
        .alert {
            font-size: 0.9rem;
        }
        
        .input-group-text {
            font-size: 0.9rem;
        }
    }
    
    /* Mejorar la apariencia de las cards */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.15s ease-in-out;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    
    /* Estilos para los selects y inputs */
    .form-select:focus,
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-select option {
        padding: 0.5rem;
    }
    
    /* Animación para la información del servicio */
    #servicio-info {
        transition: all 0.3s ease-in-out;
    }
    
    /* Estilos para el textarea */
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    /* Mejoras en los botones */
    .btn-lg {
        transition: all 0.2s ease-in-out;
    }
    
    .btn-lg:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoServicioSelect = document.getElementById('tipo_servicio');
        const servicioInfo = document.getElementById('servicio-info');
        const servicioDescripcion = document.getElementById('servicio-descripcion');
        
        const serviciosInfo = {
            'Constancia de Inscripción y/o Estudios': 'Documento oficial que certifica que el estudiante está inscrito o ha cursado estudios en la institución.',
            'Duplicado de Credencial': 'Reposición de la credencial estudiantil en caso de pérdida, robo o deterioro.',
            'Certificado Incompleto (Parcial)': 'Documento que certifica las materias cursadas hasta el momento, sin haber completado todos los estudios.',
            'Duplicado de Certificado de Estudios': 'Reposición del certificado oficial de estudios completos.',
            'Examen de Titulación (Protocolo)': 'Proceso de evaluación final para obtener el título profesional.',
            'Titulación (Tit. y Exp. de Ced. Prof.)': 'Trámite completo para obtener título y expedición de cédula profesional.',
            'reinscripcion': 'Proceso para continuar los estudios en el siguiente período académico.'
        };
        
        tipoServicioSelect.addEventListener('change', function() {
            const selectedService = this.value;
            if (selectedService && serviciosInfo[selectedService]) {
                servicioDescripcion.textContent = serviciosInfo[selectedService];
                servicioInfo.style.display = 'block';
            } else {
                servicioInfo.style.display = 'none';
            }
        });
        
        // Validación del CURP
        const curpInput = document.getElementById('curp');
        curpInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            if (this.value.length === 18) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (this.value.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
        
        // Cargar grupos dinámicamente según semestre seleccionado
        const semestreSelect = document.getElementById('semestre');
        const grupoSelect = document.getElementById('grupo');
        const grupoActual = '{{ old("grupo", Auth::guard("alumno")->user()->Grupo ?? "") }}';
        
        semestreSelect.addEventListener('change', function() {
            const semestre = this.value;
            
            // Limpiar opciones
            grupoSelect.innerHTML = '<option value="" disabled selected>Cargando grupos...</option>';
            grupoSelect.disabled = true;

            if (semestre) {
                // Hacer petición AJAX para obtener grupos del semestre
                fetch(`/api/grupos/semestre/${semestre}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar los grupos');
                    }
                    return response.json();
                })
                .then(grupos => {
                    // Limpiar opciones
                    grupoSelect.innerHTML = '<option value="" disabled selected>Selecciona un grupo</option>';
                    
                    if (grupos.length > 0) {
                        // Agregar grupos obtenidos de la base de datos
                        grupos.forEach(function(grupo) {
                            const option = document.createElement('option');
                            option.value = grupo.nombre_completo;
                            option.textContent = grupo.nombre_completo.toUpperCase();
                            
                            // Mantener selección si coincide con valor actual
                            if (grupo.nombre_completo === grupoActual) {
                                option.selected = true;
                            }
                            
                            grupoSelect.appendChild(option);
                        });
                        grupoSelect.disabled = false;
                    } else {
                        grupoSelect.innerHTML = '<option value="" disabled selected>No hay grupos disponibles</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    grupoSelect.innerHTML = '<option value="" disabled selected>Error al cargar grupos</option>';
                });
            } else {
                grupoSelect.innerHTML = '<option value="" disabled selected>Primero selecciona un semestre</option>';
            }
        });
        
        // Cargar grupos al inicializar la página si ya hay un semestre seleccionado
        if (semestreSelect.value) {
            semestreSelect.dispatchEvent(new Event('change'));
        }
    });
</script>

<!-- Agregar meta tag para CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@endpush
@endsection


