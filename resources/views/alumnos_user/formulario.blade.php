@extends('tablar::page')

@section('title', 'Formulario Independiente')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <h2 class="text-center mb-4 fs-3 fs-md-2">Solicitud pago de examen</h2>
            <div class="alert alert-warning text-center">
                <strong>Nota:</strong> Por favor, verifique que todos sus datos sean correctos antes de enviar el formulario.
            </div>
            <form method="POST" action="{{ route('formulario.store') }}">
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
                                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', Auth::guard('alumno')->user()->Nombre ?? '') }}">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="curp" class="form-label">CURP</label>
                                <input type="text" id="curp" name="curp" class="form-control" value="{{ old('curp', Auth::guard('alumno')->user()->CURP ?? '') }}">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="numero_control" class="form-label">No. de Control</label>
                                <input type="text" id="numero_control" name="numero_control" class="form-control" value="{{ old('numero_control', Auth::guard('alumno')->user()->numero_control ?? '') }}">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="especialidad" class="form-label">Especialidad</label>
                                <input type="text" id="especialidad" name="especialidad" class="form-control" value="{{ old('especialidad', Auth::guard('alumno')->user()->especialidad ?? '') }}">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label for="grupo" class="form-label">Grupo</label>
                                <input type="text" id="grupo" name="grupo" class="form-control" value="{{ old('grupo', Auth::guard('alumno')->user()->Grupo ?? '') }}">
                            </div>
                        </div>
                        <input type="hidden" id="numero_lista" name="numero_lista" value="1">
                    </div>
                </div>

                <!-- Sección de tipo de pago -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-money-check-alt me-2"></i>Tipo de Pago</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="tipo_pago" class="form-label fw-bold">Seleccione el tipo de pago</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white"><i class="fas fa-money-check-alt"></i></span>
                                    <select id="tipo_pago" name="tipo_pago" class="form-select form-select-lg shadow-sm border-primary">
                                        <option value="">Seleccione</option>
                                        <option value="recuperacion">Recuperación (R2) Todas las Asignaturas</option>
                                        <option value="regularizacion">Regularización (3 Asignaturas)</option>
                                        <option value="curso_intensivo">Curso Intensivo (Submódulos)</option>
                                        <option value="titulo_suficiencia">Título Suficiencia</option>
                                        <option value="segundo_curso_intensivo">2º Curso Intensivo (Submódulos)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 col-md-6">
                                <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                                <input type="date" id="fecha_pago" name="fecha_pago" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de materias - ESTRUCTURA CORREGIDA -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-book me-2"></i>Materias</h5>
                    </div>
                    <div class="card-body">
                        <!-- Vista de tabla para pantallas medianas y grandes -->
                        <div class="d-none d-md-block">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 60px;">Nº</th>
                                            <th>Materia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 1; $i <= 7; $i++)
                                            <tr>
                                                <td class="fw-bold">{{ $i }}</td>
                                                <td>
                                                    <!-- ✅ CAMBIO IMPORTANTE: name="materias[]" -->
                                                    <select name="materias[]" class="form-control">
                                                        <option value="">Seleccione una materia</option>
                                                        @foreach ($materias as $materia)
                                                            <option value="{{ $materia->materia }}">{{ $materia->materia }}</option>
                                                        @endforeach
                                                    </select>
                                                    <!-- ❌ ELIMINADO: El hidden de tipo_examen ya no se necesita -->
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Vista de cards para móviles -->
                        <div class="d-block d-md-none">
                            @for ($i = 1; $i <= 7; $i++)
                                <div class="card mb-3 border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <strong>Materia {{ $i }}</strong>
                                    </div>
                                    <div class="card-body">
                                        <!-- ✅ CAMBIO IMPORTANTE: name="materias[]" -->
                                        <select name="materias[]" class="form-control form-select-lg">
                                            <option value="">Seleccione una materia</option>
                                            @foreach ($materias as $materia)
                                                <option value="{{ $materia->materia }}">{{ $materia->materia }}</option>
                                            @endforeach
                                        </select>
                                        <!-- ❌ ELIMINADO: El hidden de tipo_examen ya no se necesita -->
                                    </div>
                                </div>
                            @endfor
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <small><i class="fas fa-info-circle me-2"></i>Seleccione solo las materias que necesite. Las materias no seleccionadas no se guardarán.</small>
                        </div>
                    </div>
                </div>

                <!-- Botón de envío -->
                <div class="form-footer">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-paper-plane me-2"></i>Solicitar
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
    }
    
    /* Mejorar la apariencia de las cards */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.15s ease-in-out;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .table-responsive {
        border-radius: 0.375rem;
        overflow: hidden;
    }
    
    /* Estilos para los selects */
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endpush
@endsection
