@extends('tablar::page')

@section('title', 'Editar Sala - ' . $sala->nombre)

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('salas.index') }}">Salas</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('salas.show', $sala) }}">{{ $sala->nombre }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                    <h2 class="page-title">
                        Editar Sala: {{ $sala->nombre }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('salas.show', $sala) }}" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12l14 0"/>
                                <path d="M5 12l6 6"/>
                                <path d="M5 12l6 -6"/>
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <form action="{{ route('salas.update', $sala) }}" method="POST" class="card">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h3 class="card-title">Información de la Sala</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Nombre de la Sala</label>
                                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                               placeholder="Ej: Aula 101, Laboratorio de Computación" 
                                               value="{{ old('nombre', $sala->nombre) }}" required>
                                        @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Nombre descriptivo de la sala</small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Código</label>
                                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" 
                                               placeholder="Ej: A101, LAB-COMP" 
                                               value="{{ old('codigo', $sala->codigo) }}" required>
                                        @error('codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Código único identificador</small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Tipo de Sala</label>
                                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                            <option value="">Seleccionar tipo...</option>
                                            <option value="aula" {{ old('tipo', $sala->tipo) == 'aula' ? 'selected' : '' }}>Aula</option>
                                            <option value="laboratorio" {{ old('tipo', $sala->tipo) == 'laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                                            <option value="taller" {{ old('tipo', $sala->tipo) == 'taller' ? 'selected' : '' }}>Taller</option>
                                            <option value="auditorio" {{ old('tipo', $sala->tipo) == 'auditorio' ? 'selected' : '' }}>Auditorio</option>
                                            <option value="sala_de_juntas" {{ old('tipo', $sala->tipo) == 'sala_de_juntas' ? 'selected' : '' }}>Sala de Juntas</option>
                                        </select>
                                        @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Capacidad</label>
                                        <input type="number" name="capacidad" class="form-control @error('capacidad') is-invalid @enderror" 
                                               placeholder="Número de personas" 
                                               value="{{ old('capacidad', $sala->capacidad) }}" min="1" max="500" required>
                                        @error('capacidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Número máximo de estudiantes</small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Estado</label>
                                        <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                            <option value="disponible" {{ old('estado', $sala->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                            <option value="ocupada" {{ old('estado', $sala->estado) == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                                            <option value="mantenimiento" {{ old('estado', $sala->estado) == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                                            <option value="fuera_de_servicio" {{ old('estado', $sala->estado) == 'fuera_de_servicio' ? 'selected' : '' }}>Fuera de Servicio</option>
                                        </select>
                                        @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ubicación</label>
                                        <input type="text" name="ubicacion" class="form-control @error('ubicacion') is-invalid @enderror" 
                                               placeholder="Ej: Edificio A, Primer Piso" 
                                               value="{{ old('ubicacion', $sala->ubicacion) }}">
                                        @error('ubicacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Ubicación física de la sala</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Descripción</label>
                                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                                                  rows="4" placeholder="Describe el equipamiento, características especiales, etc.">{{ old('descripcion', $sala->descripcion) }}</textarea>
                                        @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Equipamiento y características especiales de la sala</small>
                                    </div>
                                </div>
                            </div>
                            
                            @if($sala->horarios()->whereDate('fecha_fin', '>=', now())->count() > 0)
                            <div class="alert alert-info" role="alert">
                                <div class="d-flex">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="9"/>
                                            <line x1="12" y1="8" x2="12" y2="12"/>
                                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="alert-title">Sala en uso</h4>
                                        <div class="text-muted">Esta sala tiene {{ $sala->horarios()->whereDate('fecha_fin', '>=', now())->count() }} horarios activos asignados. Ten cuidado al cambiar la capacidad o el estado.</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="{{ route('salas.show', $sala) }}" class="btn btn-link">Cancelar</a>
                                <button type="submit" class="btn btn-primary ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10"/>
                                    </svg>
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
