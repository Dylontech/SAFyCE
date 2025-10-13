@extends('tablar::page')

@section('title')
    Usuarios
@endsection

@section('content')
<div class="container">
    <h2 class="my-4">Lista de Roles y Usuarios Asignados</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Crear Usuario</a>
    <table class="table">
        <thead>
            <tr>
                <th>Rol</th>
                <th>Usuarios Asignados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roleData as $role => $users)
                @if(in_array($role, ['admin', 'control escolar', 'servicio financiero']))
                    <tr>
                        <td>{{ $role }}</td>
                        <td>{{ $users }}</td>
                    </tr>
                    @if($role === 'servicio financiero' && isset($roleData['alumno']))
                        <tr>
                            <td>alumno</td>
                            <td>{{ $roleData['alumno'] }}</td>
                        </tr>
                    @endif
                @endif
            @endforeach
        </tbody>
    </table>
    {!! $users->links() !!}
</div>
@endsection
