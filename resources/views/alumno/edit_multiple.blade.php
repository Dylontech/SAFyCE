<!-- filepath: /c:/Users/dilan/Desktop/profa/SAAFCECEYT/resources/views/alumno/edit_multiple.blade.php -->
@extends('tablar::page')

@section('title', 'Editar Alumnos')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        Editar Alumnos
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('alumnos.updateMultiple') }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="ids" value="{{ implode(',', $ids) }}">
                            <div class="mb-3">
                                <label for="especialidad" class="form-label">Especialidad</label>
                                <input type="text" class="form-control" id="especialidad" name="especialidad">
                            </div>
                            <div class="mb-3">
                                <label for="semestre" class="form-label">Semestre</label>
                                <input type="number" class="form-control" id="semestre" name="semestre">
                            </div>
                            <div class="mb-3">
                                <label for="Grupo" class="form-label">Grupo</label>
                                <input type="text" class="form-control" id="Grupo" name="Grupo">
                            </div>
                            <div class="mb-3">
                                <label for="estatus" class="form-label">Estatus</label>
                                <input type="text" class="form-control" id="estatus" name="estatus">
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection