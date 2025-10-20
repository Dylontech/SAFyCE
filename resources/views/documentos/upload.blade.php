@extends('tablar::page')

@section('title', 'Subir Documentos')

@section('content')
<div class="container">
    <h2>Subir Documentos</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('documentos.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        @foreach($types as $type)
            <div class="mb-3">
                <label class="form-label">{{ ucwords(str_replace('_', ' ', $type)) }}</label>
                <input type="file" name="{{ $type }}" class="form-control">
                @error($type)
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <button class="btn btn-primary">Subir</button>
    </form>
</div>
@endsection
