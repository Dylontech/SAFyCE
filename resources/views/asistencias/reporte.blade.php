@extends('tablar::page')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Reporte de asistencias</h3>
        <button class="btn btn-sm btn-primary" onclick="window.print()">Imprimir</button>
    </div>
    <div class="card-body">
        <table class="table table-sm">
            <thead><tr><th>Alumno</th><th>Fecha</th><th>Nivel</th><th>Estado</th></tr></thead>
            <tbody>
                @foreach($asistencias as $a)
                <tr>
                    <td>{{ $a->alumno->nombre_completo ?? $a->alumno->nombre }}</td>
                    <td>{{ $a->fecha }}</td>
                    <td>{{ $a->nivel }}</td>
                    <td>{{ $a->estado }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
