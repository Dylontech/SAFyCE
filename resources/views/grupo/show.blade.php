@extends('tablar::page')

@section('title', 'Detalles del Grupo')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Detalles
                    </div>
                    <h2 class="page-title">
                        {{ __('Grupo ') }} <strong>{{ $grupo->nombre_completo }}</strong>
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('grupos.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12,19 5,12 12,5"/>
                            </svg>
                            Volver a la lista
                        </a>
                        <a href="{{ route('grupos.edit', $grupo->id) }}" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Editar grupo
                        </a>
                        <a href="{{ route('grupos.index') }}" class="btn btn-secondary d-sm-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12,19 5,12 12,5"/>
                            </svg>
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
                <!-- Información del Grupo -->
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Información del Grupo</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <div class="form-control-plaintext">
                                    <strong class="text-primary fs-2">{{ $grupo->nombre_completo }}</strong>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Semestre</label>
                                        <div class="form-control-plaintext">
                                            <span class="badge bg-blue fs-3">{{ $grupo->semestre }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Letra</label>
                                        <div class="form-control-plaintext">
                                            <span class="badge bg-green fs-3">{{ strtoupper($grupo->letra) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <div class="form-control-plaintext">
                                    @if($grupo->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Creación</label>
                                        <div class="form-control-plaintext">
                                            {{ $grupo->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Última Actualización</label>
                                        <div class="form-control-plaintext">
                                            {{ $grupo->updated_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de Alumnos -->
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Alumnos del Grupo 
                                <span class="badge bg-blue ms-2">{{ $alumnos->total() }}</span>
                            </h3>
                        </div>
                        
                        @if($alumnos->count() > 0)
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No. Control</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Especialidad</th>
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($alumnos as $alumno)
                                            <tr>
                                                <td>
                                                    <strong>{{ $alumno->numero_control }}</strong>
                                                </td>
                                                <td>{{ $alumno->Nombre }}</td>
                                                <td>{{ $alumno->email }}</td>
                                                <td>{{ $alumno->especialidad }}</td>
                                                <td>
                                                    @if($alumno->estatus == 'Activo')
                                                        <span class="badge bg-success">{{ $alumno->estatus }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $alumno->estatus }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-list flex-nowrap">
                                                        <a class="btn btn-sm btn-primary" href="{{ route('alumnos.show', $alumno->id) }}" 
                                                           title="Ver alumno">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" 
                                                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                                 stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <circle cx="12" cy="12" r="2"/>
                                                                <path d="M12 1c5 0 9 4 9 11c0 1 -1 2 -2 3l-1 1l-1 1l-1 1c-1 1 -1 1 -2 1h-8c-1 0 -1 0 -2 -1l-1 -1l-1 -1l-1 -1c-1 -1 -2 -2 -2 -3c0 -7 4 -11 9 -11z"/>
                                                            </svg>
                                                        </a>
                                                        <a class="btn btn-sm btn-warning" href="{{ route('alumnos.edit', $alumno->id) }}" 
                                                           title="Editar alumno">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" 
                                                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                                 stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                                <path d="M16 5l3 3"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer d-flex align-items-center">
                                {!! $alumnos->links() !!}
                            </div>
                        @else
                            <div class="card-body">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="7" r="4"/>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                        </svg>
                                    </div>
                                    <p class="empty-title">No hay alumnos en este grupo</p>
                                    <p class="empty-subtitle text-muted">
                                        Aún no se han asignado alumnos a este grupo.
                                    </p>
                                    <div class="empty-action">
                                        <a href="{{ route('alumnos.create') }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
                                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="12" y1="5" x2="12" y2="19"/>
                                                <line x1="5" y1="12" x2="19" y2="12"/>
                                            </svg>
                                            Crear alumno
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
