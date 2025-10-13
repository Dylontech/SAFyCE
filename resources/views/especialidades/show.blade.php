@extends('tablar::page')

@section('title', 'View Especialidade')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        View
                    </div>
                    <h2 class="page-title">
                        {{ __('Especialidade ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('especialidades.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Especialidade List
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
                            <h3 class="card-title">Especialidade Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>ID:</strong></label>
                                        <p class="form-control-plaintext">{{ $especialidade->id }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Nombre:</strong></label>
                                        <p class="form-control-plaintext">{{ $especialidade->nombre }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Descripción:</strong></label>
                                        <p class="form-control-plaintext">
                                            {{ $especialidade->descripcion ?? 'Sin descripción' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Fecha de Creación:</strong></label>
                                        <p class="form-control-plaintext">{{ $especialidade->created_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Última Actualización:</strong></label>
                                        <p class="form-control-plaintext">{{ $especialidade->updated_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-footer">
                                <div class="text-end">
                                    <div class="d-flex">
                                        <a href="{{ route('especialidades.index') }}" class="btn btn-secondary">
                                            <i class="fa fa-fw fa-arrow-left"></i> Volver a la Lista
                                        </a>
                                        <a href="{{ route('especialidades.edit', $especialidade->id) }}" class="btn btn-primary ms-2">
                                            <i class="fa fa-fw fa-edit"></i> Editar
                                        </a>
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


