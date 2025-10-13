@extends('tablar::page')

@section('title', 'View Alumno')

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
                        {{ __('Alumno ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <!-- Botón Editar -->
                        <a href="{{ route('alumnos.edit', $alumno->id) }}" class="btn btn-warning" title="Editar alumno">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            <span class="d-none d-sm-inline">Editar</span>
                        </a>
                        
                        <!-- Botón Inicio - Mejorado para responsividad -->
                        <a href="{{ route('alumnos.index') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0"/>
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                            </svg>
                            <span class="d-none d-sm-inline">Inicio</span>
                            <span class="d-sm-none">Lista</span>
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
                    @if(config('tablar','display_alert'))
                        @include('tablar::common.alert')
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Información del Alumno</h3>
                        </div>
                        <div class="card-body">
                            <!-- Diseño responsivo para los campos -->
                            <div class="row">
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Número de Control:</strong></label>
                                        <div class="form-control-plaintext">{{ $alumno->numero_control }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label"><strong>CURP:</strong></label>
                                        <div class="form-control-plaintext">{{ $alumno->CURP }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Especialidad:</strong></label>
                                        <div class="form-control-plaintext">{{ $alumno->especialidad }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Semestre:</strong></label>
                                        <div class="form-control-plaintext">{{ $alumno->semestre }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Grupo:</strong></label>
                                        <div class="form-control-plaintext">{{ $alumno->Grupo }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Nombre:</strong></label>
                                        <div class="form-control-plaintext">{{ $alumno->Nombre }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Email:</strong></label>
                                        <div class="form-control-plaintext">{{ $alumno->email }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Estatus:</strong></label>
                                        <div class="form-control-plaintext">
                                            <span class="badge 
                                                @if($alumno->estatus == 'Activo') bg-success text-white
                                                @elseif($alumno->estatus == 'Inactivo') bg-danger text-white
                                                @elseif($alumno->estatus == 'Egresado') bg-info text-white
                                                @elseif($alumno->estatus == 'Baja') bg-warning text-dark
                                                @else bg-secondary text-white
                                                @endif">
                                                {{ $alumno->estatus }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection