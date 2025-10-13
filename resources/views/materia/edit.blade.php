@extends('tablar::page')

@section('title', 'Actualizar Materia')

@section('content')
    <!-- Encabezado de página -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Pre-título de página -->
                    <div class="page-pretitle">
                        Actualizar
                    </div>
                    <h2 class="page-title">
                        {{ __('Materia ') }}
                    </h2>
                </div>
                <!-- Acciones del título de la página -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <!-- Botón completo para escritorio -->
                        <a href="{{ route('materias.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Icono de flecha hacia la izquierda -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12,19 5,12 12,5"/>
                            </svg>
                            Lista de Materias
                        </a>
                        <!-- Botón compacto para móviles -->
                        <a href="{{ route('materias.index') }}" class="btn btn-primary d-sm-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12,19 5,12 12,5"/>
                            </svg>
                            <span class="d-none d-xs-inline-block">Volver</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cuerpo de la página -->
    <div class="page-body">
        <div class="container-xl">
            @if(config('tablar','display_alert'))
                @include('tablar::common.alert')
            @endif
            <div class="row row-deck row-cards">
                <div class="col-12 col-lg-8 col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalles de la Materia</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST"
                                  action="{{ route('materias.update', $materia->id) }}" id="ajaxForm" role="form"
                                  enctype="multipart/form-data">
                                {{ method_field('PATCH') }}
                                @csrf
                                @include('materia.form')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



