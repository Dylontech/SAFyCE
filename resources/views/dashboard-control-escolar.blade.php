@extends('tablar::page')

@section('title', 'Panel de Control Escolar')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-dashboard me-2"></i>
                    Panel de Control Escolar
                </h2>
                <div class="text-muted mt-1">¡Bienvenido, {{ Auth::user()->name }}! - Sistema de Administración y Control Escolar CECEYT</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <span class="badge bg-success text-white">
                        <i class="ti ti-circle-check me-1"></i>
                        Sistema Activo
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">

<!-- Estadísticas rápidas -->
<div class="row row-deck row-cards mb-4">
    @can('ver salas')
    <div class="col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="h1 mb-0 text-success">{{ \App\Models\Sala::where('estado', 'disponible')->count() }}</div>
                        <div class="text-muted">Salas Disponibles</div>
                    </div>
                    <i class="ti ti-door fs-1 text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    @endcan
    
    @can('ver tareas')
    <div class="col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="h1 mb-0 text-warning">{{ \App\Models\Tarea::where('estado', 'activa')->count() }}</div>
                        <div class="text-muted">Tareas Activas</div>
                    </div>
                    <i class="ti ti-clipboard-list fs-1 text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    @endcan
    
    @can('ver horarios')
    <div class="col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="h1 mb-0 text-info">{{ \App\Models\Horario::activos()->count() }}</div>
                        <div class="text-muted">Horarios Activos</div>
                    </div>
                    <i class="ti ti-calendar fs-1 text-info opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    @endcan
    
    @hasrole('admin')
    <div class="col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="h1 mb-0 text-primary">{{ \App\Models\Alumno::count() }}</div>
                        <div class="text-muted">Total Alumnos</div>
                    </div>
                    <i class="ti ti-users fs-1 text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    @endhasrole
</div>

<!-- Módulos del Sistema -->
<div class="page-title">Módulos del Sistema</div>

<div class="row row-deck row-cards">
    <!-- Gestión de Alumnos - Solo para admins -->
    @hasrole('admin')
    <div class="col-lg-3 col-md-6">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-school fs-1 text-primary"></i>
                </div>
                <h3 class="card-title">Gestión de Alumnos</h3>
                <p class="text-muted">Administrar información de estudiantes</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('alumnos.index') }}" class="btn btn-primary w-100">Acceder</a>
            </div>
        </div>
    </div>
    @endhasrole

    <!-- Salas de Clase -->
    @can('ver salas')
    <div class="col-lg-3 col-md-6">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-door fs-1 text-success"></i>
                </div>
                <h3 class="card-title">Salas de Clase</h3>
                <p class="text-muted">Gestionar aulas y laboratorios</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('salas.index') }}" class="btn btn-success w-100">Acceder</a>
            </div>
        </div>
    </div>
    @endcan

    <!-- Horarios -->
    @can('ver horarios')
    <div class="col-lg-3 col-md-6">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-calendar fs-1 text-info"></i>
                </div>
                <h3 class="card-title">Horarios</h3>
                <p class="text-muted">Programación de clases</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('horarios.index') }}" class="btn btn-info w-100">Acceder</a>
            </div>
        </div>
    </div>
    @endcan

    <!-- Tareas y Proyectos -->
    @can('ver tareas')
    <div class="col-lg-3 col-md-6">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-clipboard-list fs-1 text-warning"></i>
                </div>
                <h3 class="card-title">Tareas y Proyectos</h3>
                <p class="text-muted">Asignar y gestionar tareas</p>
            </div>
            <div class="card-footer">
                @if(Auth::user()->hasRole('maestro'))
                    <a href="{{ route('maestros.tareas.index') }}" class="btn btn-warning w-100">Acceder</a>
                @else
                    <a href="{{ route('tareas.index') }}" class="btn btn-warning w-100">Acceder</a>
                @endif
            </div>
        </div>
    </div>
    @endcan

    <!-- Calificaciones -->
    @can('ver calificaciones')
    <div class="col-lg-3 col-md-6">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-chart-line fs-1 text-danger"></i>
                </div>
                <h3 class="card-title">Calificaciones</h3>
                <p class="text-muted">Evaluar y calificar</p>
            </div>
            <div class="card-footer">
                @if(Auth::user()->hasRole('maestro'))
                    <a href="{{ route('maestros.calificaciones.index') }}" class="btn btn-danger w-100">Acceder</a>
                @else
                    <a href="{{ route('calificaciones.index') }}" class="btn btn-danger w-100">Acceder</a>
                @endif
            </div>
        </div>
    </div>
    @endcan

    <!-- Usuarios y Roles - Solo para admins -->
    @hasrole('admin')
    <div class="col-lg-3 col-md-6">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-user-cog fs-1 text-secondary"></i>
                </div>
                <h3 class="card-title">Usuarios y Roles</h3>
                <p class="text-muted">Gestionar permisos</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">Acceder</a>
            </div>
        </div>
    </div>
    @endhasrole

    <!-- Configuración - Solo para admins -->
    @hasrole('admin')
    <div class="col-lg-3 col-md-6">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-settings fs-1 text-purple"></i>
                </div>
                <h3 class="card-title">Configuración</h3>
                <p class="text-muted">Ajustes del sistema</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.index') }}" class="btn btn-purple w-100">Acceder</a>
            </div>
        </div>
    </div>
    @endhasrole

    <!-- Moderación Social - Para Control Escolar -->
    @hasrole('control_escolar')
    <div class="col-lg-3 col-md-6">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-shield-check fs-1 text-danger"></i>
                </div>
                <h3 class="card-title">Moderación Social</h3>
                <p class="text-muted">Supervisar plataforma estudiantil</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('moderacion.dashboard') }}" class="btn btn-danger w-100">Acceder</a>
            </div>
        </div>
    </div>
    @endhasrole
</div>

<!-- Accesos rápidos para maestros -->
@if(Auth::user()->hasRole('maestro'))
<div class="row mt-5">
    <div class="col-12">
        <div class="page-title">Panel de Maestros - Accesos Rápidos</div>
    </div>
    
    <!-- Dashboard de Maestros -->
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-layout-dashboard fs-1 text-primary"></i>
                </div>
                <h3 class="card-title">Dashboard Maestros</h3>
                <p class="text-muted">Panel principal con estadísticas</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('maestros.dashboard') }}" class="btn btn-primary w-100">Acceder</a>
            </div>
        </div>
    </div>
    
    <!-- Gestión de Tareas -->
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-clipboard-list fs-1 text-success"></i>
                </div>
                <h3 class="card-title">Mis Tareas</h3>
                <p class="text-muted">Crear y gestionar tareas</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('maestros.tareas.index') }}" class="btn btn-success w-100">Gestionar</a>
            </div>
        </div>
    </div>
    
    <!-- Gestión de Calificaciones -->
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-star fs-1 text-warning"></i>
                </div>
                <h3 class="card-title">Calificaciones</h3>
                <p class="text-muted">Evaluar y calificar estudiantes</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('maestros.calificaciones.index') }}" class="btn btn-warning w-100">Calificar</a>
            </div>
        </div>
    </div>
    
    <!-- Reportes -->
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-chart-bar fs-1 text-info"></i>
                </div>
                <h3 class="card-title">Reportes</h3>
                <p class="text-muted">Estadísticas y reportes</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('maestros.calificaciones.reportes') }}" class="btn btn-info w-100">Ver Reportes</a>
            </div>
        </div>
    </div>
</div>

<!-- Accesos adicionales para maestros -->
<div class="row mt-3 row-deck row-cards">
    <div class="col-12">
        <div class="page-title">Recursos Adicionales</div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-calendar fs-1 text-primary"></i>
                </div>
                <h3 class="card-title">Horarios</h3>
                <p class="text-muted">Consultar horarios de clases</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('horarios.index') }}" class="btn btn-primary w-100">Ver Horarios</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-door fs-1 text-success"></i>
                </div>
                <h3 class="card-title">Salas</h3>
                <p class="text-muted">Información de aulas disponibles</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('salas.index') }}" class="btn btn-success w-100">Ver Salas</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-plus fs-1 text-warning"></i>
                </div>
                <h3 class="card-title">Nueva Tarea</h3>
                <p class="text-muted">Crear tarea rápidamente</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('maestros.tareas.create') }}" class="btn btn-warning w-100">Crear Tarea</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-star fs-1 text-info"></i>
                </div>
                <h3 class="card-title">Nueva Calificación</h3>
                <p class="text-muted">Registrar calificación</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('maestros.calificaciones.create') }}" class="btn btn-info w-100">Calificar</a>
            </div>
        </div>
    </div>
</div>

<!-- Acceso de moderación para Control Escolar y Maestros -->
<div class="row mt-3 row-deck row-cards">
    <div class="col-12">
        <div class="page-title">Sistema de Moderación Social</div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-shield-check fs-1 text-danger"></i>
                </div>
                <h3 class="card-title">Moderación Social</h3>
                <p class="text-muted">Panel de control para moderar publicaciones estudiantiles</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('moderacion.dashboard') }}" class="btn btn-danger w-100">
                    <i class="ti ti-shield-check me-1"></i>
                    Acceder al Panel
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-flag fs-1 text-warning"></i>
                </div>
                <h3 class="card-title">Reportes</h3>
                <p class="text-muted">Revisar reportes de contenido inapropiado</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('moderacion.reportes.index') }}" class="btn btn-warning w-100">
                    <i class="ti ti-flag me-1"></i>
                    Ver Reportes
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-ban fs-1 text-secondary"></i>
                </div>
                <h3 class="card-title">Usuarios Bloqueados</h3>
                <p class="text-muted">Gestionar estudiantes con restricciones</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('moderacion.bloqueados.index') }}" class="btn btn-secondary w-100">
                    <i class="ti ti-ban me-1"></i>
                    Gestionar Bloqueos
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Accesos rápidos para alumnos -->
@if(Auth::user()->esAlumno())
<div class="row mt-5 row-deck row-cards">
    <div class="col-12">
        <div class="page-title">Mi Panel de Estudiante</div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-calendar fs-1 text-primary"></i>
                </div>
                <h3 class="card-title">Mis Horarios</h3>
                <p class="text-muted">Consultar horarios académicos</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('estudiantes.horarios') }}" class="btn btn-primary w-100">Ver Horarios</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-door fs-1 text-success"></i>
                </div>
                <h3 class="card-title">Aulas</h3>
                <p class="text-muted">Explorar salas y aulas</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('estudiantes.salas') }}" class="btn btn-success w-100">Ver Aulas</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-clipboard-list fs-1 text-info"></i>
                </div>
                <h3 class="card-title">Mis Tareas</h3>
                <p class="text-muted">Ver tareas asignadas</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('tareas.mis-tareas') }}" class="btn btn-info w-100">Ver Tareas</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-link">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="ti ti-chart-line fs-1 text-warning"></i>
                </div>
                <h3 class="card-title">Mis Calificaciones</h3>
                <p class="text-muted">Consultar calificaciones</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('calificaciones.mis-calificaciones') }}" class="btn btn-warning w-100">Ver Calificaciones</a>
            </div>
        </div>
    </div>
</div>
@endif


@endsection
