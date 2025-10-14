@extends('tablar::page')

@section('title', 'Editar Grupo')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Editar
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
                        <a href="{{ route('grupos.show', $grupo->id) }}" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="2"/>
                                <path d="M12 1c5 0 9 4 9 11c0 1 -1 2 -2 3l-1 1l-1 1l-1 1c-1 1 -1 1 -2 1h-8c-1 0 -1 0 -2 -1l-1 -1l-1 -1l-1 -1c-1 -1 -2 -2 -2 -3c0 -7 4 -11 9 -11z"/>
                            </svg>
                            Ver detalles
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Editar Datos del Grupo</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('grupos.update', $grupo->id) }}" role="form" enctype="multipart/form-data">
                                {{ method_field('PATCH') }}
                                @csrf
                                @include('grupo.form')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
