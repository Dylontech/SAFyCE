@extends('tablar::page')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Historial de asistencias - {{ $alumno->nombre_completo ?? $alumno->nombre }}</h3>
        <a class="btn btn-sm btn-primary" onclick="window.print()">Imprimir</a>
    </div>
    <div class="card-body">
        <h4>Asistencias</h4>
        <table class="table">
            <thead>
                <tr><th>Fecha</th><th>Nivel</th><th>Hora</th><th>Estado</th><th>Motivo</th></tr>
            </thead>
            <tbody>
                @foreach($asistencias as $a)
                <tr>
                    <td>{{ $a->fecha->format('Y-m-d') }}</td>
                    <td>{{ $a->nivel }}</td>
                    <td>{{ $a->hora_entrada }}</td>
                    <td>{{ $a->estado }}</td>
                    <td>{{ $a->motivo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Comprobantes</h4>
        <table class="table">
            <thead><tr><th>Archivo</th><th>Estado</th><th>Comentario</th><th>Acción</th></tr></thead>
            <tbody>
                @foreach($comprobantes as $c)
                <tr>
                    <td><a href="{{ asset('storage/' . $c->archivo) }}" target="_blank">Ver</a></td>
                    <td>{{ $c->estado }}</td>
                    <td>{{ $c->comentario }}</td>
                    <td>
                        @if(auth()->check() && auth()->user()->hasRole('maestro'))
                            <form action="{{ route('maestros.asistencias.comprobantes.revisar', $c->id) }}" method="POST">
                                @csrf
                                <select name="estado">
                                    <option value="aprobado">Aprobar</option>
                                    <option value="rechazado">Rechazar</option>
                                </select>
                                <button class="btn btn-sm btn-primary">Enviar</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Subir comprobante</h4>
        <form action="{{ route('asistencias.comprobantes.subir') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="alumno_id" value="{{ $alumno->id }}">
            <div class="mb-3">
                <input type="file" name="archivo" accept="image/*,.pdf" required>
            </div>
            <button class="btn btn-success">Subir comprobante</button>
        </form>
    </div>
</div>
@endsection
