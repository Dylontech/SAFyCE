@extends('tablar::page')

@section('title', 'Horario Semanal')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Portal Estudiantil
                </div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                        <path d="M8 14h.01"/>
                        <path d="M12 14h.01"/>
                        <path d="M16 14h.01"/>
                        <path d="M8 18h.01"/>
                        <path d="M12 18h.01"/>
                        <path d="M16 18h.01"/>
                    </svg>
                    Horario Semanal
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('estudiantes.horarios') }}" class="btn btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                            <line x1="16" y1="3" x2="16" y2="7"/>
                            <line x1="8" y1="3" x2="8" y2="7"/>
                            <line x1="4" y1="11" x2="20" y2="11"/>
                        </svg>
                        Lista de Horarios
                    </a>
                    <a href="{{ route('estudiantes.salas') }}" class="btn btn-outline-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 21h18"/>
                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                            <path d="M9 9h6"/>
                            <path d="M9 12h6"/>
                            <path d="M9 15h6"/>
                        </svg>
                        Ver Salas
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"/>
                            <rect x="7" y="13" width="10" height="8" rx="2"/>
                        </svg>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        @if($horariosPorDia->count() > 0)
            <div class="row row-deck">
                @foreach($dias as $dia => $nombreDia)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title text-white mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <line x1="12" y1="7" x2="12" y2="12"/>
                                        <line x1="16" y1="10" x2="12" y2="12"/>
                                    </svg>
                                    {{ $nombreDia }}
                                </h3>
                                @if($horariosPorDia->has($dia))
                                    <div class="card-actions">
                                        <span class="badge bg-white text-primary">
                                            {{ $horariosPorDia[$dia]->count() }} clase{{ $horariosPorDia[$dia]->count() != 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body p-0">
                                @if($horariosPorDia->has($dia) && $horariosPorDia[$dia]->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($horariosPorDia[$dia]->sortBy('hora_inicio') as $horario)
                                            <div class="list-group-item list-group-item-action">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <!-- Horario -->
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="badge bg-info me-2">
                                                                {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - 
                                                                {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                                                            </span>
                                                            @php
                                                                $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                                                $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                                                $duracion = $fin->diffInMinutes($inicio);
                                                            @endphp
                                                            <small class="text-muted">{{ floor($duracion / 60) }}h {{ $duracion % 60 }}m</small>
                                                        </div>

                                                        <!-- Materia -->
                                                        <div class="fw-bold text-primary mb-1">
                                                            @if($horario->materia)
                                                                {{ $horario->materia->materia }}
                                                            @else
                                                                <span class="text-muted">Sin materia asignada</span>
                                                            @endif
                                                        </div>

                                                        <!-- Maestro -->
                                                        @if($horario->maestro)
                                                            <div class="d-flex align-items-center mb-2">
                                                                <span class="avatar avatar-xs me-2 bg-secondary text-white">
                                                                    {{ substr($horario->maestro->name, 0, 1) }}
                                                                </span>
                                                                <small class="text-muted">{{ $horario->maestro->name }}</small>
                                                            </div>
                                                        @endif

                                                        <!-- Sala -->
                                                        @if($horario->sala)
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div>
                                                                    <small class="text-muted">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                            <path d="M3 21h18"/>
                                                                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                                                        </svg>
                                                                        {{ $horario->sala->nombre }}
                                                                    </small>
                                                                </div>
                                                                @if($horario->sala->capacidad)
                                                                    <span class="badge bg-success badge-sm">{{ $horario->sala->capacidad }}</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Estado -->
                                                    <div class="ms-2">
                                                        <span class="badge bg-{{ $horario->estaActivo() ? 'success' : 'secondary' }} badge-sm">
                                                            {{ $horario->estaActivo() ? 'Activo' : 'Inactivo' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Acciones -->
                                                <div class="mt-2 pt-2 border-top">
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('estudiantes.horarios.show', $horario) }}" class="btn btn-outline-primary btn-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                                            </svg>
                                                            Ver
                                                        </a>
                                                        @if($horario->sala)
                                                            <a href="{{ route('estudiantes.salas.show', $horario->sala) }}" class="btn btn-outline-info btn-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <path d="M3 21h18"/>
                                                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                                                                </svg>
                                                                Aula
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="card-body text-center py-5">
                                        <div class="text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="9"/>
                                                <line x1="9" y1="10" x2="9.01" y2="10"/>
                                                <line x1="15" y1="10" x2="15.01" y2="10"/>
                                                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"/>
                                            </svg>
                                            <div>Sin clases programadas</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Resumen Semanal -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                Resumen Semanal
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="h1 text-primary">{{ $horariosPorDia->flatten()->count() }}</div>
                                        <div class="text-muted">Total de Clases</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="h1 text-success">{{ $horariosPorDia->flatten()->where('sala', '!=', null)->count() }}</div>
                                        <div class="text-muted">Con Aula Asignada</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="h1 text-info">{{ $horariosPorDia->flatten()->where('maestro', '!=', null)->count() }}</div>
                                        <div class="text-muted">Con Profesor Asignado</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @php
                                            $activos = $horariosPorDia->flatten()->filter(function($h) { return $h->estaActivo(); })->count();
                                        @endphp
                                        <div class="h1 text-warning">{{ $activos }}</div>
                                        <div class="text-muted">Horarios Activos</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="empty">
                        <div class="empty-img">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="128" height="128" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                                <path d="M8 14h.01"/>
                                <path d="M12 14h.01"/>
                                <path d="M16 14h.01"/>
                                <path d="M8 18h.01"/>
                                <path d="M12 18h.01"/>
                                <path d="M16 18h.01"/>
                            </svg>
                        </div>
                        <p class="empty-title">No hay horarios disponibles</p>
                        <p class="empty-subtitle text-muted">
                            No se encontraron horarios académicos activos para mostrar en la vista semanal.
                        </p>
                        <div class="empty-action">
                            <a href="{{ route('estudiantes.horarios') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="4" y="5" width="16" height="16" rx="2"/>
                                    <line x1="16" y1="3" x2="16" y2="7"/>
                                    <line x1="8" y1="3" x2="8" y2="7"/>
                                    <line x1="4" y1="11" x2="20" y2="11"/>
                                </svg>
                                Ver Lista de Horarios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('css')
<style>
@media print {
    .page-header, .btn-list, .card-footer {
        display: none !important;
    }
    .card {
        break-inside: avoid;
        margin-bottom: 10px;
    }
}
</style>
@endpush
@endsection
