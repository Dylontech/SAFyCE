@extends('tablar::page')

@section('title', 'Crear Recurso - Biblioteca Virtual')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('biblioteca-virtual.index') }}">Biblioteca Virtual</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear Recurso</li>
                        </ol>
                    </nav>
                    <h2 class="page-title">
                        <i class="fas fa-plus me-2"></i>
                        Crear Nuevo Recurso
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('biblioteca-virtual.store') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Información del Recurso
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label required">Nombre del Recurso</label>
                                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                               value="{{ old('nombre') }}" placeholder="Ej: eLibro, EBSCOhost, SciELO">
                                        @error('nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label required">URL del Recurso</label>
                                        <input type="url" name="url" class="form-control @error('url') is-invalid @enderror" 
                                               value="{{ old('url') }}" placeholder="https://ejemplo.com">
                                        @error('url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">URL completa del recurso web (debe incluir http:// o https://)</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Categoría</label>
                                        <select name="categoria" class="form-select @error('categoria') is-invalid @enderror">
                                            <option value="">Seleccionar categoría</option>
                                            @foreach($categorias as $key => $label)
                                                <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categoria')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Orden de Visualización</label>
                                        <input type="number" name="orden" class="form-control @error('orden') is-invalid @enderror" 
                                               value="{{ old('orden', 0) }}" min="0">
                                        @error('orden')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Número menor aparece primero (0 = primero)</small>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Descripción</label>
                                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                                                  rows="3" placeholder="Descripción breve del recurso...">{{ old('descripcion') }}</textarea>
                                        @error('descripcion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Icono (Font Awesome)</label>
                                        <input type="text" name="icono" class="form-control @error('icono') is-invalid @enderror" 
                                               value="{{ old('icono') }}" placeholder="Ej: fas fa-book, fas fa-database">
                                        @error('icono')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">
                                            Clases de iconos de Font Awesome. Si se deja vacío, se usará un icono predeterminado.
                                            <a href="https://fontawesome.com/icons" target="_blank">Ver iconos disponibles</a>
                                        </small>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="activo" class="form-check-input" id="activo" 
                                                   value="1" {{ old('activo', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="activo">
                                                Recurso activo
                                            </label>
                                        </div>
                                        <small class="form-hint">Solo los recursos activos serán visibles para los estudiantes</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col">
                                        <a href="{{ route('biblioteca-virtual.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Cancelar
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            Crear Recurso
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    .form-label.required::after {
        content: " *";
        color: red;
    }
    
    .form-hint {
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .form-hint a {
        color: #0066cc;
        text-decoration: none;
    }
    
    .form-hint a:hover {
        text-decoration: underline;
    }
</style>
@endsection
