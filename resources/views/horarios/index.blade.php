@extends('tablar::page')

@section('title', 'Horarios de Clases')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Control Escolar
                    </div>
                    <h2 class="page-title">
                        Horarios de Clases
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    @can('crear horarios')
                    <div class="btn-list">
                        <a href="{{ route('horarios.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Nuevo Horario
                        </a>
                        <a href="{{ route('horarios.mi-horario') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <polyline points="12,7 12,12 15,15"/>
                            </svg>
                            Mi Horario
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('horarios.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Día</label>
                                            <select name="dia_semana" class="form-select">
                                                <option value="">Todos los días</option>
                                                @foreach($dias as $dia)
                                                <option value="{{ $dia }}" {{ request('dia_semana') == $dia ? 'selected' : '' }}>{{ ucfirst($dia) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Maestro</label>
                                            <select name="user_id" class="form-select">
                                                <option value="">Todos los maestros</option>
                                                @foreach($maestros as $maestro)
                                                <option value="{{ $maestro->id }}" {{ request('user_id') == $maestro->id ? 'selected' : '' }}>{{ $maestro->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="d-grid">
                                                <a href="{{ route('horarios.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vista de horarios -->
            <div class="row row-deck row-cards">
                @if(false) <!-- Calendario deshabilitado temporalmente -->
                <!-- Vista de calendario semanal -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Horario del Grupo {{ request('grupo') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 120px;">Hora</th>
                                            <th>Lunes</th>
                                            <th>Martes</th>
                                            <th>Miércoles</th>
                                            <th>Jueves</th>
                                            <th>Viernes</th>
                                            <th>Sábado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $horasClase = ['07:00-08:00', '08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00', '15:00-16:00', '16:00-17:00'];
                                            $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                                            $horariosPorDiaHora = $horarios->groupBy(function($horario) {
                                                return $horario->dia_semana . '_' . date('H:i', strtotime($horario->hora_inicio));
                                            });
                                        @endphp
                                        @foreach($horasClase as $hora)
                                        <tr>
                                            <td class="text-center bg-light"><strong>{{ $hora }}</strong></td>
                                            @foreach($dias as $dia)
                                            @php
                                                $horaInicio = explode('-', $hora)[0];
                                                $clave = $dia . '_' . $horaInicio;
                                                $horarioEncontrado = $horariosPorDiaHora->get($clave)?->first();
                                            @endphp
                                            <td class="text-center" style="height: 60px;">
                                                @if($horarioEncontrado)
                                                <div class="bg-primary text-white p-2 rounded">
                                                    <small><strong>{{ $horarioEncontrado->materia->materia }}</strong></small><br>
                                                    <small>{{ $horarioEncontrado->maestro->name }}</small><br>
                                                    <small>{{ $horarioEncontrado->sala->nombre }}</small>
                                                </div>
                                                @endif
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Vista de lista de horarios -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Horarios</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Horario</th>
                                        <th>Materia</th>
                                        <th>Maestro</th>
                                        <th>Horario</th>
                                        <th>Sala</th>
                                        <th>Estado</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($horarios as $horario)
                                    <tr>
                                        <td>
                                            <span class="badge bg-blue">{{ ucfirst($horario->dia_semana) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <span class="avatar avatar-sm me-2 bg-secondary text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <circle cx="12" cy="12" r="9"/>
                                                        <polyline points="12,7 12,12 15,15"/>
                                                    </svg>
                                                </span>
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium">{{ date('H:i', strtotime($horario->hora_inicio)) }} - {{ date('H:i', strtotime($horario->hora_fin)) }}</div>
                                                    <div class="text-muted">{{ \Carbon\Carbon::parse($horario->hora_inicio)->diffInHours(\Carbon\Carbon::parse($horario->hora_fin)) }}h</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($horario->materia)
                                                <div>{{ $horario->materia->materia }}</div>
                                                <div class="text-muted">{{ $horario->materia->codigo ?? 'Sin código' }}</div>
                                            @else
                                                <div class="text-muted">Materia no asignada</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($horario->maestro)
                                                <div class="d-flex py-1 align-items-center">
                                                    <span class="avatar avatar-sm me-2">{{ substr($horario->maestro->name, 0, 2) }}</span>
                                                    <div class="flex-fill">
                                                        <div class="font-weight-medium">{{ $horario->maestro->name }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-muted">Maestro no asignado</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-info me-2">{{ ucfirst($horario->dia_semana) }}</span>
                                                <div>
                                                    <div>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($horario->sala)
                                                <div>{{ $horario->sala->nombre }}</div>
                                                <div class="text-muted">{{ $horario->sala->codigo ?? 'N/A' }}</div>
                                            @else
                                                <div class="text-muted">Sala no asignada</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $horario->estaActivo() ? 'success' : 'secondary' }}">
                                                {{ $horario->estaActivo() ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                                                        Acciones
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{ route('horarios.show', $horario) }}">
                                                            Ver detalles
                                                        </a>
                                                        @can('editar horarios')
                                                        @if(Auth::user()->esAdmin() || $horario->maestro_id === Auth::id())
                                                        <a class="dropdown-item" href="{{ route('horarios.edit', $horario) }}">
                                                            Editar
                                                        </a>
                                                        @endif
                                                        @endcan
                                                        @can('eliminar horarios')
                                                        @if(Auth::user()->esAdmin() || $horario->maestro_id === Auth::id())
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('horarios.destroy', $horario) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Estás seguro de eliminar este horario?')">
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                        @endif
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="empty">
                                                <div class="empty-img"><img src="{{ asset('dist/img/undraw_calendar.svg') }}" height="128" alt=""></div>
                                                <p class="empty-title">No se encontraron horarios</p>
                                                <p class="empty-subtitle text-muted">
                                                    Intenta ajustar tus filtros o crear un nuevo horario.
                                                </p>
                                                @can('crear horarios')
                                                <div class="empty-action">
                                                    <a href="{{ route('horarios.create') }}" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                                        </svg>
                                                        Crear horario
                                                    </a>
                                                </div>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($horarios->hasPages())
                        <div class="card-footer d-flex align-items-center">
                            {{ $horarios->links() }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
