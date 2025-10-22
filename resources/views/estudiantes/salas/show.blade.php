@extends('tablar::page')

@section('title', 'Detalle del Aula - ' . $sala->nombre)

@section('content')
{{-- ============================================ --}}
{{-- ENCABEZADO DE LA PÁGINA --}}
{{-- ============================================ --}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Portal Estudiantil
                </div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 21h18"/>
                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                        <path d="M9 9h6"/>
                        <path d="M9 12h6"/>
                        <path d="M9 15h6"/>
                    </svg>
                    {{ $sala->nombre }}
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('estudiantes.salas') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <line x1="12" y1="5" x2="19" y2="12"/>
                            <line x1="12" y1="19" x2="19" y2="12"/>
                        </svg>
                        Volver a Salas
                    </a>
                    <a href="{{ route('estudiantes.horarios') }}" class="btn btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                            <line x1="16" y1="3" x2="16" y2="7"/>
                            <line x1="8" y1="3" x2="8" y2="7"/>
                            <line x1="4" y1="11" x2="20" y2="11"/>
                        </svg>
                        Ver Horarios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- CONTENIDO PRINCIPAL --}}
{{-- ============================================ --}}
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            
            {{-- ============================================ --}}
            {{-- INFORMACIÓN PRINCIPAL DEL AULA --}}
            {{-- ============================================ --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 21h18"/>
                                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                        <path d="M9 9h6"/>
                                        <path d="M9 12h6"/>
                                        <path d="M9 15h6"/>
                                    </svg>
                                    Información del Aula
                                </h3>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-{{ $sala->estado == 'disponible' ? 'success' : ($sala->estado == 'mantenimiento' ? 'warning' : 'danger') }} fs-6 px-3 py-2">
                                    {{ ucfirst($sala->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        {{-- Información Básica del Aula --}}
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">NOMBRE DEL AULA</label>
                                    <div class="h2 mb-1 text-primary">{{ $sala->nombre }}</div>
                                    @if($sala->codigo)
                                        <div class="text-muted">
                                            <span class="badge bg-info me-2">Código: {{ $sala->codigo }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">TIPO DE SALA</label>
                                    <div class="h4">
                                        <span class="badge bg-primary fs-5 px-3 py-2">{{ ucfirst($sala->tipo) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Capacidad y Ubicación --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">CAPACIDAD</label>
                                    @if($sala->capacidad)
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center py-3">
                                                <div class="h1 mb-0">{{ $sala->capacidad }}</div>
                                                <div class="text-white-50">personas</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card bg-light border-0">
                                            <div class="card-body text-center py-3 text-muted">
                                                <div class="h4 mb-0">Capacidad no especificada</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">UBICACIÓN</label>
                                    @if($sala->ubicacion)
                                        <div class="card bg-info text-white">
                                            <div class="card-body py-3">
                                                <div class="d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <circle cx="12" cy="11" r="3"/>
                                                        <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/>
                                                    </svg>
                                                    <div class="h5 mb-0">{{ $sala->ubicacion }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card bg-light border-0">
                                            <div class="card-body text-center py-3 text-muted">
                                                <div class="h5 mb-0">Ubicación no especificada</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Descripción del Aula --}}
                        @if($sala->descripcion)
                        <hr class="my-4">
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold">DESCRIPCIÓN</label>
                                <div class="card border-0 shadow-sm descripcion-gradient">
                                    <div class="card-body">
                                        <p class="mb-0 text-white fw-medium">{{ $sala->descripcion }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Equipamiento Disponible --}}
                        @if($sala->equipamiento)
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold">EQUIPAMIENTO DISPONIBLE</label>
                                <div class="card border-primary">
                                    <div class="card-body">
                                        @php
                                            $equipos = explode(',', $sala->equipamiento);
                                        @endphp
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($equipos as $equipo)
                                                <span class="badge bg-primary fs-6 px-3 py-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <circle cx="12" cy="12" r="9"/>
                                                        <path d="M9 12l2 2l4 -4"/>
                                                    </svg>
                                                    {{ trim($equipo) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- PANEL DE INFORMACIÓN ADICIONAL --}}
            {{-- ============================================ --}}
            <div class="col-12 mt-4">
                <div class="row">
                    
                    {{-- ============================================ --}}
                    {{-- REUNIONES VIRTUALES PROGRAMADAS --}}
                    {{-- ============================================ --}}
                    @if($reuniones && $reuniones->count() > 0)
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="4" width="16" height="12" rx="1"/>
                                        <path d="m16 8l-8 5l8 5v-10z"/>
                                    </svg>
                                    Reuniones Virtuales
                                </h3>
                                <small class="text-white-50">{{ $reuniones->count() }} reunion{{ $reuniones->count() != 1 ? 'es' : '' }} programada{{ $reuniones->count() != 1 ? 's' : '' }}</small>
                            </div>
                            <div class="card-body p-3">
                                @foreach($reuniones as $reunion)
                                <div class="reunion-card card mb-3 border-primary">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0 text-primary">{{ $reunion->titulo }}</h6>
                                            <span class="badge bg-info text-white">{{ ucfirst($reunion->plataforma) }}</span>
                                        </div>
                                        
                                        <div class="small text-muted mb-2">
                                            <div><strong>📅 Fecha:</strong> {{ $reunion->fecha_hora_formatted }}</div>
                                            <div><strong>⏱️ Duración:</strong> {{ $reunion->duracion_minutos }} min</div>
                                        </div>
                                        
                                        @if($reunion->descripcion)
                                        <p class="card-text small text-muted mb-2">{{ Str::limit($reunion->descripcion, 80) }}</p>
                                        @endif
                                        
                                        @if($reunion->puedeUnirse())
                                        <a href="{{ $reunion->enlace_reunion }}" target="_blank" class="btn btn-success btn-sm w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-video me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z"/>
                                                <rect x="3" y="6" width="12" height="12" rx="2"/>
                                            </svg>
                                            🎥 Unirse a Reunión
                                        </a>
                                        @else
                                        <button class="btn btn-secondary btn-sm w-100" disabled>
                                            🔒 Reunión no disponible
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ============================================ --}}
                    {{-- ESTADO ACTUAL DEL AULA --}}
                    {{-- ============================================ --}}
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-{{ $sala->estado == 'disponible' ? 'success' : ($sala->estado == 'mantenimiento' ? 'warning' : 'danger') }} text-white">
                                <h3 class="card-title mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Estado Actual
                                </h3>
                                <small class="text-white-50">Disponibilidad en tiempo real</small>
                            </div>
                            <div class="card-body py-4 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    @if($sala->estado == 'disponible')
                                        <div class="mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-success" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="9"/>
                                                <path d="M9 12l2 2l4 -4"/>
                                            </svg>
                                        </div>
                                        <div class="h2 text-success mb-2">✅ Disponible</div>
                                        <p class="text-muted mb-0">El aula está lista para uso académico</p>
                                    @elseif($sala->estado == 'ocupada')
                                        <div class="mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-danger" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="9"/>
                                                <path d="M15 9l-6 6"/>
                                                <path d="M9 9l6 6"/>
                                            </svg>
                                        </div>
                                        <div class="h2 text-danger mb-2">🔴 Ocupada</div>
                                        <p class="text-muted mb-0">El aula está actualmente en uso</p>
                                    @else
                                        <div class="mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-warning" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="9"/>
                                                <path d="M12 8l0 4"/>
                                                <path d="M12 16l.01 0"/>
                                            </svg>
                                        </div>
                                        <div class="h2 text-warning mb-2">🔧 En Mantenimiento</div>
                                        <p class="text-muted mb-0">El aula no está disponible temporalmente</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- INFORMACIÓN DEL SISTEMA --}}
                    {{-- ============================================ --}}
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-info text-white">
                                <h3 class="card-title mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 6l0 6l4 2"/>
                                        <circle cx="12" cy="12" r="9"/>
                                    </svg>
                                    Información del Sistema
                                </h3>
                                <small class="text-white-50">Datos técnicos y estadísticas</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mb-4">
                                        <div class="text-center">
                                            <div class="text-muted small mb-1">ID del Aula</div>
                                            <div class="h3 text-primary">#{{ str_pad($sala->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-4">
                                        <div class="text-center">
                                            <div class="text-muted small mb-1">Horarios Activos</div>
                                            <div class="h3 text-success">{{ $horariosActuales->count() }}</div>
                                            <small class="text-muted">clase{{ $horariosActuales->count() != 1 ? 's' : '' }}</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-3">
                                
                                <div class="mb-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="text-muted small">Última Actualización</div>
                                            <div class="fw-medium">{{ $sala->updated_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <small class="badge bg-secondary">{{ $sala->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- TABLA DE HORARIOS DEL AULA --}}
            {{-- ============================================ --}}
            @if($horariosActuales->count() > 0)
                <div class="col-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="4" y="5" width="16" height="16" rx="2"/>
                                    <line x1="16" y1="3" x2="16" y2="7"/>
                                    <line x1="8" y1="3" x2="8" y2="7"/>
                                    <line x1="4" y1="11" x2="20" y2="11"/>
                                </svg>
                                Horarios de Clases en Esta Aula
                            </h3>
                            <div class="card-actions">
                                <span class="badge bg-primary">{{ $horariosActuales->count() }} clase{{ $horariosActuales->count() != 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Día</th>
                                            <th>Horario</th>
                                            <th>Materia</th>
                                            <th>Profesor</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($horariosActuales as $horario)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">{{ ucfirst($horario->dia_semana) }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</strong>
                                                    @php
                                                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                                        $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                                        $duracion = $fin->diffInMinutes($inicio);
                                                    @endphp
                                                    <br><small class="text-muted">{{ floor($duracion / 60) }}h {{ $duracion % 60 }}min</small>
                                                </td>
                                                <td>
                                                    @if($horario->materia)
                                                        <strong>{{ $horario->materia->materia }}</strong>
                                                        @if($horario->materia->codigo)
                                                            <br><small class="text-muted">{{ $horario->materia->codigo }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Sin materia asignada</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($horario->maestro)
                                                        <div class="d-flex align-items-center">
                                                            <span class="avatar avatar-sm me-2 bg-secondary text-white">
                                                                {{ substr($horario->maestro->name, 0, 2) }}
                                                            </span>
                                                            <div>
                                                                <div>{{ $horario->maestro->name }}</div>
                                                                <small class="text-muted">{{ $horario->maestro->email }}</small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Sin profesor asignado</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $horario->estaActivo() ? 'success' : 'secondary' }}">
                                                        {{ $horario->estaActivo() ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('estudiantes.horarios.show', $horario) }}" class="btn btn-outline-primary btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                                        </svg>
                                                        Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Estado Vacío para Horarios --}}
                <div class="col-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="4" y="5" width="16" height="16" rx="2"/>
                                    <line x1="16" y1="3" x2="16" y2="7"/>
                                    <line x1="8" y1="3" x2="8" y2="7"/>
                                    <line x1="4" y1="11" x2="20" y2="11"/>
                                </svg>
                                Horarios de Clases
                            </h3>
                        </div>
                        <div class="card-body text-center py-5">
                            <div class="empty">
                                <div class="empty-img">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="96" height="96" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                                        <line x1="16" y1="3" x2="16" y2="7"/>
                                        <line x1="8" y1="3" x2="8" y2="7"/>
                                        <line x1="4" y1="11" x2="20" y2="11"/>
                                    </svg>
                                </div>
                                <p class="empty-title">Sin horarios asignados</p>
                                <p class="empty-subtitle text-muted">
                                    Esta aula no tiene clases programadas actualmente.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SCRIPTS Y RECURSOS EXTERNOS --}}
{{-- ============================================ --}}

{{-- SweetAlert2 para notificaciones --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ============================================ --}}
{{-- FUNCIONALIDAD JAVASCRIPT --}}
{{-- ============================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners para botones de unirse a reuniones
    document.querySelectorAll('.btn-unirse-reunion').forEach(button => {
        button.addEventListener('click', function() {
            const reunionId = this.dataset.reunionId;
            unirseReunion(reunionId, this);
        });
    });
});

/**
 * Función para unirse a una reunión virtual
 * @param {string} reunionId - ID de la reunión
 * @param {HTMLElement} button - Botón que activó la función
 */
function unirseReunion(reunionId, button) {
    // Cambiar estado del botón
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Conectando...';
    
    fetch(`/reuniones/${reunionId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '🎥 ¡Conectando a la Reunión!',
                html: `
                    <div class="text-start">
                        <p><strong>📝 Reunión:</strong> ${data.reunion.titulo}</p>
                        <p><strong>🖥️ Plataforma:</strong> ${data.reunion.plataforma}</p>
                        <p><strong>🌐 Estado:</strong> Abriendo en nueva ventana...</p>
                    </div>
                `,
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true
            }).then(() => {
                // Abrir enlace en nueva ventana
                window.open(data.reunion.enlace, '_blank');
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: '❌ Error',
                text: data.message || 'No se puede acceder a la reunión en este momento'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '🔌 Error de Conexión',
            text: 'No se pudo conectar con el servidor. Intenta de nuevo.'
        });
    })
    .finally(() => {
        // Restaurar botón después de un delay
        setTimeout(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        }, 2000);
    });
}
</script>

{{-- ============================================ --}}
{{-- ESTILOS PERSONALIZADOS --}}
{{-- ============================================ --}}
<style>
/* Estilos para tarjetas de reuniones */
.reunion-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border-left: 4px solid #0d6efd !important;
}

.reunion-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.15);
}

/* Estilos para botones de reunión */
.btn-unirse-reunion {
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    font-weight: 600;
}

.btn-unirse-reunion:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    background: linear-gradient(135deg, #218838 0%, #1ea984 100%);
}

.btn-unirse-reunion:disabled {
    transform: none;
    box-shadow: none;
    opacity: 0.7;
}

/* Degradado azul a lila para la descripción */
.descripcion-gradient {
    background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
    transition: all 0.3s ease;
}

.descripcion-gradient:hover {
    background: linear-gradient(135deg, #0056b3 0%, #5a32a3 100%);
    transform: translateY(-1px);
    box-shadow: 0 8px 25px rgba(111, 66, 193, 0.3);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

@endsection
