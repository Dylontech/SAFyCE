@extends('tablar::page')

@section('title', 'Panel de Control Escolar')

@section('content')
<style>
    .module-card {
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
        height: 100%;
    }
    
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .module-icon {
        font-size: 3rem;
        background: linear-gradient(145deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stats-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 4px solid #667eea;
    }
    
    .welcome-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
</style>

<div class="welcome-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="mb-2">¡Bienvenido, {{ Auth::user()->name }}!</h1>
            <p class="mb-0 opacity-75">Sistema de Administración y Control Escolar CECEYT</p>
        </div>
        <div class="col-md-4 text-md-end">
            <i class="fas fa-graduation-cap" style="font-size: 4rem; opacity: 0.3;"></i>
        </div>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="row mb-4">
    @can('ver salas')
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stats-card p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Salas Disponibles</h6>
                    <h3 class="mb-0">{{ \App\Models\Sala::where('estado', 'disponible')->count() }}</h3>
                </div>
                <div class="text-success">
                    <i class="fas fa-door-open fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    @endcan
    
    @can('ver tareas')
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stats-card p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Tareas Activas</h6>
                    <h3 class="mb-0">{{ \App\Models\Tarea::where('estado', 'activa')->count() }}</h3>
                </div>
                <div class="text-warning">
                    <i class="fas fa-tasks fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    @endcan
    
    @can('ver horarios')
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stats-card p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Horarios Activos</h6>
                    <h3 class="mb-0">{{ \App\Models\Horario::activos()->count() }}</h3>
                </div>
                <div class="text-info">
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    @endcan
    
    @hasrole('admin')
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stats-card p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Total Alumnos</h6>
                    <h3 class="mb-0">{{ \App\Models\Alumno::count() }}</h3>
                </div>
                <div class="text-primary">
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    @endhasrole
</div>

<!-- Módulos del Sistema -->
<h2 class="mb-4">Módulos del Sistema</h2>

<div class="row">
    <!-- Gestión de Alumnos - Solo para admins -->
    @hasrole('admin')
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card module-card text-center p-4">
            <div class="module-icon mb-3">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h5 class="card-title">Gestión de Alumnos</h5>
            <p class="card-text text-muted">Administrar información de estudiantes</p>
            <a href="{{ route('alumnos.index') }}" class="btn btn-primary">Acceder</a>
        </div>
    </div>
    @endhasrole

    <!-- Salas de Clase -->
    @can('ver salas')
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card module-card text-center p-4">
            <div class="module-icon mb-3">
                <i class="fas fa-door-open"></i>
            </div>
            <h5 class="card-title">Salas de Clase</h5>
            <p class="card-text text-muted">Gestionar aulas y laboratorios</p>
            <a href="{{ route('salas.index') }}" class="btn btn-success">Acceder</a>
        </div>
    </div>
    @endcan

    <!-- Horarios -->
    @can('ver horarios')
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card module-card text-center p-4">
            <div class="module-icon mb-3">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h5 class="card-title">Horarios</h5>
            <p class="card-text text-muted">Programación de clases</p>
            <a href="{{ route('horarios.index') }}" class="btn btn-info">Acceder</a>
        </div>
    </div>
    @endcan

    <!-- Tareas y Proyectos -->
    @can('ver tareas')
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card module-card text-center p-4">
            <div class="module-icon mb-3">
                <i class="fas fa-tasks"></i>
            </div>
            <h5 class="card-title">Tareas y Proyectos</h5>
            <p class="card-text text-muted">Asignar y gestionar tareas</p>
            <a href="{{ route('tareas.index') }}" class="btn btn-warning">Acceder</a>
        </div>
    </div>
    @endcan

    <!-- Calificaciones -->
    @can('ver calificaciones')
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card module-card text-center p-4">
            <div class="module-icon mb-3">
                <i class="fas fa-chart-line"></i>
            </div>
            <h5 class="card-title">Calificaciones</h5>
            <p class="card-text text-muted">Evaluar y calificar</p>
            <a href="{{ route('calificaciones.index') }}" class="btn btn-danger">Acceder</a>
        </div>
    </div>
    @endcan

    <!-- Usuarios y Roles - Solo para admins -->
    @hasrole('admin')
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card module-card text-center p-4">
            <div class="module-icon mb-3">
                <i class="fas fa-users-cog"></i>
            </div>
            <h5 class="card-title">Usuarios y Roles</h5>
            <p class="card-text text-muted">Gestionar permisos</p>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Acceder</a>
        </div>
    </div>
    @endhasrole

    <!-- Configuración - Solo para admins -->
    @hasrole('admin')
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card module-card text-center p-4">
            <div class="module-icon mb-3">
                <i class="fas fa-cogs"></i>
            </div>
            <h5 class="card-title">Configuración</h5>
            <p class="card-text text-muted">Ajustes del sistema</p>
            <a href="{{ route('admin.index') }}" class="btn btn-purple">Acceder</a>
        </div>
    </div>
    @endhasrole
</div>

<!-- Accesos rápidos para maestros -->
@if(Auth::user()->esMaestro())
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-3">Accesos Rápidos para Maestros</h3>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-primary" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                        <line x1="16" y1="3" x2="16" y2="7"/>
                        <line x1="8" y1="3" x2="8" y2="7"/>
                        <line x1="4" y1="11" x2="20" y2="11"/>
                    </svg>
                </div>
                <h5 class="card-title">Mis Horarios</h5>
                <p class="card-text text-muted">Ver y gestionar horarios de clases</p>
                <a href="{{ route('horarios.index') }}" class="btn btn-primary btn-sm">Ver Horarios</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-success" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 21h18"/>
                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                        <path d="M9 9h6"/>
                        <path d="M9 12h6"/>
                        <path d="M9 15h6"/>
                    </svg>
                </div>
                <h5 class="card-title">Salas</h5>
                <p class="card-text text-muted">Consultar información de aulas</p>
                <a href="{{ route('salas.index') }}" class="btn btn-success btn-sm">Ver Salas</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-info" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                        <line x1="9" y1="9" x2="10" y2="9"/>
                        <line x1="9" y1="13" x2="15" y2="13"/>
                        <line x1="9" y1="17" x2="15" y2="17"/>
                    </svg>
                </div>
                <h5 class="card-title">Tareas</h5>
                <p class="card-text text-muted">Gestionar tareas y actividades</p>
                <a href="{{ route('tareas.index') }}" class="btn btn-info btn-sm">Ver Tareas</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-warning" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 11l3 3l8 -8"/>
                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                    </svg>
                </div>
                <h5 class="card-title">Calificaciones</h5>
                <p class="card-text text-muted">Registrar y consultar calificaciones</p>
                <a href="{{ route('calificaciones.index') }}" class="btn btn-warning btn-sm">Ver Calificaciones</a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Accesos rápidos para alumnos -->
@if(Auth::user()->esAlumno())
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-3">Mi Panel de Estudiante</h3>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-primary" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                        <line x1="16" y1="3" x2="16" y2="7"/>
                        <line x1="8" y1="3" x2="8" y2="7"/>
                        <line x1="4" y1="11" x2="20" y2="11"/>
                    </svg>
                </div>
                <h5 class="card-title">Mis Horarios</h5>
                <p class="card-text text-muted">Consultar horarios académicos</p>
                <a href="{{ route('estudiantes.horarios') }}" class="btn btn-primary btn-sm">Ver Horarios</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-success" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 21h18"/>
                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                        <path d="M9 9h6"/>
                        <path d="M9 12h6"/>
                        <path d="M9 15h6"/>
                    </svg>
                </div>
                <h5 class="card-title">Aulas</h5>
                <p class="card-text text-muted">Explorar salas y aulas</p>
                <a href="{{ route('estudiantes.salas') }}" class="btn btn-success btn-sm">Ver Aulas</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-info" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                        <line x1="9" y1="9" x2="10" y2="9"/>
                        <line x1="9" y1="13" x2="15" y2="13"/>
                        <line x1="9" y1="17" x2="15" y2="17"/>
                    </svg>
                </div>
                <h5 class="card-title">Mis Tareas</h5>
                <p class="card-text text-muted">Ver tareas asignadas</p>
                <a href="{{ route('tareas.mis-tareas') }}" class="btn btn-info btn-sm">Ver Tareas</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-warning" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 11l3 3l8 -8"/>
                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                    </svg>
                </div>
                <h5 class="card-title">Mis Calificaciones</h5>
                <p class="card-text text-muted">Consultar calificaciones</p>
                <a href="{{ route('calificaciones.mis-calificaciones') }}" class="btn btn-warning btn-sm">Ver Calificaciones</a>
            </div>
        </div>
    </div>
</div>
@endif


@endsection
