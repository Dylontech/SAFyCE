@extends('tablar::page')

@section('title', 'Revisión de Documentos')

@section('content')
<div class="container">
    <h2>Documentos - Revisión</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Alumno</th>
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
                    <td>{{ optional($doc->alumno)->Nombre ?? '—' }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $doc->document_type)) }}</td>
                    <td>{{ $doc->original_name ?: basename($doc->file_path) }}</td>
                    <td>{{ $doc->status }}</td>
                    <td>{{ $doc->reviewer_comment }}</td>
                    <td>
                        <a href="{{ route('control_documentos.download', $doc) }}" class="btn btn-sm btn-primary">Descargar</a>

                        <form action="{{ route('control_documentos.review', $doc) }}" method="post" style="display:inline-block; margin-left:6px;">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <input type="hidden" name="comment" value="">
                            <button class="btn btn-sm btn-success">Aprobar</button>
                        </form>

                        <button class="btn btn-sm btn-danger" onclick="showRejectForm({{ $doc->id }})">Rechazar</button>

                        <form id="reject-form-{{ $doc->id }}" action="{{ route('control_documentos.review', $doc) }}" method="post" style="display:none; margin-top:6px;">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <div class="mb-2">
                                <textarea name="comment" class="form-control" placeholder="Comentario al alumno"></textarea>
                            </div>
                            <button class="btn btn-sm btn-danger">Enviar rechazo</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
function showRejectForm(id) {
    var f = document.getElementById('reject-form-' + id);
    if (f.style.display === 'none') f.style.display = 'block'; else f.style.display = 'none';
}
</script>

@endsection
