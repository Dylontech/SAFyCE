@extends('tablar::page')

@section('title', 'Crear Grupo')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Crear
                    </div>
                    <h2 class="page-title">
                        {{ __('Grupo ') }}
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
                            <h3 class="card-title">Datos del Grupo</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('grupos.store') }}" role="form" enctype="multipart/form-data">
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
