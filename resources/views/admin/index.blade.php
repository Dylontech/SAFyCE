@extends('tablar::page')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12">
            <!-- Título responsivo -->
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
                <h2 class="h3 mb-3 mb-md-0 text-primary">
                    <i class="fas fa-database me-2"></i>
                    Administración de la Base de Datos
                </h2>
            </div>

            <!-- Alertas responsivas -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Cards responsivos para los formularios -->
            <div class="row g-3 g-lg-4">
                <!-- Card para respaldo completo -->
                <div class="col-12 col-lg-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-download me-2"></i>
                                Respaldo Completo
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted small mb-3">
                                Descarga un respaldo completo de toda la base de datos
                            </p>
                            
                            <form action="{{ route('admin.download-backup-database') }}" method="POST" class="h-100 d-flex flex-column">
                                @csrf
                                <div class="mb-3 flex-grow-1">
                                    <label for="database" class="form-label fw-bold">Base de Datos:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-database"></i>
                                        </span>
                                        <input type="text" 
                                               name="database" 
                                               id="database" 
                                               class="form-control" 
                                               value="{{ env('DB_DATABASE') }}" 
                                               readonly>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-download me-2"></i>
                                        <span class="d-none d-sm-inline">Descargar </span>Respaldo Completo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Card para respaldo de tabla específica -->
                <div class="col-12 col-lg-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-table me-2"></i>
                                Respaldo por Tabla
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted small mb-3">
                                Descarga el respaldo de una tabla específica
                            </p>
                            
                            <form action="{{ route('admin.download-backup-table') }}" method="POST" class="h-100 d-flex flex-column">
                                @csrf
                                <div class="mb-3 flex-grow-1">
                                    <label for="table" class="form-label fw-bold">Nombre de la Tabla:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-list"></i>
                                        </span>
                                        <select name="table" 
                                                id="table" 
                                                class="form-select" 
                                                required>
                                            <option value="">Seleccionar tabla...</option>
                                            @foreach($tables as $table)
                                                <option value="{{ $table }}">{{ $table }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-info btn-lg">
                                        <i class="fas fa-download me-2"></i>
                                        <span class="d-none d-sm-inline">Descargar </span>Respaldo de Tabla
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información adicional responsiva -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                                <div class="text-warning me-3 mb-2 mb-sm-0">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="card-title text-warning mb-1">Información importante</h6>
                                    <p class="card-text small mb-0">
                                        Los respaldos se generan en formato SQL. Asegúrate de almacenar los archivos en un lugar seguro.
                                        <span class="d-block d-sm-inline"> El proceso puede tomar algunos minutos dependiendo del tamaño de los datos.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    @media (max-width: 576px) {
        .card-title {
            font-size: 1rem;
        }
        .btn-lg {
            font-size: 0.9rem;
        }
    }
    
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .input-group-text {
        background-color: var(--bs-light);
    }
</style>
@endpush
@endsection

