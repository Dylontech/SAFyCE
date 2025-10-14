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
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-calendar-week"></i> Mi Horario</h5>
                <p class="card-text">Ver tu horario de clases</p>
                <a href="{{ route('horarios.mi-horario') }}" class="btn btn-primary btn-sm">Ver Horario</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-plus-circle"></i> Nueva Tarea</h5>
                <p class="card-text">Asignar tarea a tus grupos</p>
                <a href="{{ route('tareas.create') }}" class="btn btn-success btn-sm">Crear Tarea</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-chart-bar"></i> Mis Calificaciones</h5>
                <p class="card-text">Gestionar calificaciones</p>
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
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-tasks"></i> Mis Tareas</h5>
                <p class="card-text">Ver tareas pendientes</p>
                <a href="{{ route('tareas.mis-tareas') }}" class="btn btn-primary btn-sm">Ver Tareas</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-chart-line"></i> Mis Calificaciones</h5>
                <p class="card-text">Consultar calificaciones</p>
                <a href="{{ route('calificaciones.mis-calificaciones') }}" class="btn btn-success btn-sm">Ver Calificaciones</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-file-invoice"></i> Solicitudes</h5>
                <p class="card-text">Gestionar solicitudes</p>
                <a href="{{ route('alumnos_user.index') }}" class="btn btn-warning btn-sm">Ver Solicitudes</a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
