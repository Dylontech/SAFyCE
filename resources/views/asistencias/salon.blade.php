@extends('tablar::page')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Lista de alumnos - Grupo {{ $grupoId }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('maestros.asistencias.salon.registrar') }}">
            @csrf
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Presente</th>
                        <th>Falta</th>
                        <th>Justificada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumnos as $alumno)
                    <tr>
                        <td>{{ $alumno->nombre_completo ?? $alumno->nombre }}</td>
                        <td><input type="radio" name="estado[{{ $alumno->id }}]" value="presente" checked></td>
                        <td><input type="radio" name="estado[{{ $alumno->id }}]" value="falta"></td>
                        <td><input type="radio" name="estado[{{ $alumno->id }}]" value="justificada"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary">Guardar asistencias</button>
        </form>
    </div>
    <div class="card-footer">
        <a href="{{ route('maestros.dashboard') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection
