@extends('tablar::page')

@section('title', 'Mis Documentos')

@section('content')
<div class="container">
    <h2>Mis Documentos</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('documentos.create') }}" class="btn btn-secondary mb-3">Subir nuevos documentos</a>

    <table class="table">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Archivo</th>
                <th>Estado</th>
                <th>Comentario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $doc)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $doc->document_type)) }}</td>
                    <td>{{ $doc->original_name ?: basename($doc->file_path) }}</td>
                    <td>{{ $doc->status }}</td>
                    <td>{{ $doc->reviewer_comment }}</td>
                    <td>
                        <a href="{{ route('documentos.download', $doc) }}" class="btn btn-sm btn-primary">Descargar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
