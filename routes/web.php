<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WhatsappSettingsController;
use App\Http\Controllers\FormularioEController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\ControlUserController;
use App\Http\Controllers\GestionSController;
use App\Http\Controllers\FinanzasUserController;
use App\Http\Controllers\GestionEController;
use App\Http\Controllers\CarruselController;
use App\Http\Controllers\PaginaInicioController;
use App\Http\Controllers\AdminPaginaInicioController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\EstudianteController;



// Página de inicio pública
Route::get('/', [PaginaInicioController::class, 'index'])->name('inicio');

// Redirección del home al login
Route::get('/home', function () {
    return redirect('/login');
});

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas de registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Rutas de restablecimiento de contraseña
Route::get('/password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Rutas para usuarios autenticados
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('/alumnos', AlumnoController::class);
    
    // === MÓDULOS DE CONTROL ESCOLAR ===
    
    // Rutas para Salas
    Route::resource('salas', \App\Http\Controllers\SalaController::class);
    Route::post('salas/verificar-disponibilidad', [\App\Http\Controllers\SalaController::class, 'verificarDisponibilidad'])
        ->name('salas.verificar-disponibilidad');
    
    // Rutas para Reuniones
    Route::post('salas/crear-reunion', [\App\Http\Controllers\SalaController::class, 'crearReunion'])
        ->name('salas.crear-reunion');
    Route::get('salas/{sala}/reuniones', [\App\Http\Controllers\SalaController::class, 'reunionesSala'])
        ->name('salas.reuniones');
    Route::get('reuniones/{reunion}', [\App\Http\Controllers\SalaController::class, 'obtenerReunion'])
        ->name('reuniones.obtener');
    Route::patch('reuniones/{reunion}/cancelar', [\App\Http\Controllers\SalaController::class, 'cancelarReunion'])
        ->name('reuniones.cancelar');
    Route::delete('reuniones/{reunion}', [\App\Http\Controllers\SalaController::class, 'eliminarReunion'])
        ->name('reuniones.eliminar');
    
    // Rutas para Horarios
    Route::resource('horarios', \App\Http\Controllers\HorarioController::class);
    Route::get('horarios/grupo/{grupo}', [\App\Http\Controllers\HorarioController::class, 'porGrupo'])
        ->name('horarios.grupo');
    Route::get('mi-horario', [\App\Http\Controllers\HorarioController::class, 'miHorario'])
        ->name('horarios.mi-horario');
    
    // Rutas para Tareas
    Route::resource('tareas', \App\Http\Controllers\TareaController::class);
    Route::get('tareas/{tarea}/descargar', [\App\Http\Controllers\TareaController::class, 'descargarArchivo'])
        ->name('tareas.descargar');
    Route::post('tareas/marcar-vencidas', [\App\Http\Controllers\TareaController::class, 'marcarVencidas'])
        ->name('tareas.marcar-vencidas');
    Route::get('mis-tareas', [\App\Http\Controllers\TareaController::class, 'misTareas'])
        ->name('tareas.mis-tareas');
    
    // Rutas para Calificaciones
    Route::resource('calificaciones', \App\Http\Controllers\CalificacionController::class);
    Route::get('tareas/{tarea}/calificar', [\App\Http\Controllers\CalificacionController::class, 'calificarTarea'])
        ->name('calificaciones.calificar-tarea');
    Route::post('tareas/{tarea}/calificar', [\App\Http\Controllers\CalificacionController::class, 'guardarCalificacionesTarea'])
        ->name('calificaciones.guardar-tarea');
    Route::get('alumnos/{alumno}/boleta/{periodo?}', [\App\Http\Controllers\CalificacionController::class, 'boleta'])
        ->name('calificaciones.boleta');
    Route::get('mis-calificaciones', [\App\Http\Controllers\CalificacionController::class, 'misCalificaciones'])
        ->name('calificaciones.mis-calificaciones');
    Route::get('api/tareas-por-materia', [\App\Http\Controllers\CalificacionController::class, 'obtenerTareasPorMateria'])
        ->name('api.tareas-por-materia');
    
    // === FIN MÓDULOS DE CONTROL ESCOLAR ===
    
    Route::get('/configuracion', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/configuracion/asignar', [RoleController::class, 'assignRoles'])->name('roles.assign');
    Route::resource('users', UserController::class);

    // Ruta para búsqueda de alumnos
    Route::get('/search/alumnos', [AlumnoController::class, 'search'])->name('search.alumnos');
    
    // Ruta AJAX para obtener grupos por semestre
    Route::get('/api/grupos/semestre/{semestre}', [App\Http\Controllers\GrupoController::class, 'getGruposPorSemestre'])->name('grupos.por-semestre');
    
    // Configuración de página de inicio (protegida por auth)
    // Ruta para mostrar el formulario (GET)
Route::get('/admin/pagina-inicio', [AdminPaginaInicioController::class, 'edit'])
    ->name('admin.pagina-inicio.edit');

// Ruta para actualizar (PUT) - CAMBIADO DE POST A PUT
Route::put('/admin/pagina-inicio', [AdminPaginaInicioController::class, 'update'])
    ->name('admin.pagina-inicio.update');
});

// Rutas para alumnos autenticados
Route::middleware(['auth:alumno'])->group(function () {
    Route::get('/alumnos_user', [App\Http\Controllers\alumnos_userController::class, 'index'])->name('alumnos_user.index');

    // NUEVA RUTA: Descargar liga de pago desde tabla formularios
Route::get('/formularios/download-liga-pago-formularios/{id}', [FormularioController::class, 'downloadLigaPagoFormularios'])->name('formularios.downloadLigaPagoFormularios');

    // Ruta para el formulario independiente
    Route::get('/formulario', [FormularioEController::class, 'create'])->name('formulario');

    // Rutas para el controlador FormularioEController
    Route::get('/formulario/create', [FormularioEController::class, 'create'])->name('formulario.create');
    Route::post('/formulario/store', [FormularioEController::class, 'store'])->name('formulario.store');
    Route::delete('/formulario/{id}', [FormularioEController::class, 'destroy'])->name('formulario.destroy');
    Route::delete('/solicitudesE/{id}', [FormularioEController::class, 'destroy'])->name('solicitudesE.destroy');

    // Ruta para la vista de solicitudesE
    Route::get('/solicitudesE', [FormularioEController::class, 'solicitudesE'])->name('solicitudesE.index');

    // Ruta para la vista de servicios
    Route::get('/servicios', function () {
        return view('alumnos_user.servicios');
    })->name('servicios');

    // Ruta para la vista del editor
    Route::get('/editor', function () {
        return view('alumnos_user.editor');
    })->name('editor');

    // Rutas para FormularioController
    Route::get('/formularios', [FormularioController::class, 'index'])->name('formularios.index');
    Route::post('/ruta-de-envio', [FormularioController::class, 'store']);
    Route::patch('/formularios/{id}/status', [FormularioController::class, 'updateStatus'])->name('formularios.updateStatus');

    // Rutas para subir y descargar archivos
    Route::post('/formularios/upload', [FormularioController::class, 'upload'])->name('formularios.upload');
    Route::get('/formularios/download-liga/{id}', [FormularioController::class, 'downloadLigaDePago'])->name('formularios.downloadLigaDePago');
    Route::get('/formularios/download-comprobante/{id}', [FormularioController::class, 'downloadComprobanteAlumno'])->name('formularios.downloadComprobanteAlumno');
    Route::post('/formularios/subir-comprobante-alumno/{id}', [FormularioController::class, 'subirComprobanteAlumno'])->name('formularios.subirComprobanteAlumno');

    // Ruta para eliminar una solicitud
    Route::delete('/formularios/{id}', [FormularioController::class, 'destroy'])->name('formulario.destroy');
});

// Ruta para la vista de administración sin bloqueo de rol
Route::get('/admin/index', function () {
    $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
    $tables = array_map('current', $tables);
    return view('admin.index', compact('tables'));
})->name('admin.index');

Route::post('/admin/download-backup-database', [AdminController::class, 'downloadBackupDatabase'])->name('admin.download-backup-database');
Route::post('/admin/download-backup-table', [AdminController::class, 'downloadBackupTable'])->name('admin.download-backup-table');

// Rutas para la configuración de WhatsApp
Route::get('/admin/whatsapp-settings', [WhatsappSettingsController::class, 'edit'])->name('edit.whatsapp.settings');
Route::post('/admin/update-whatsapp-settings', [WhatsappSettingsController::class, 'update'])->name('update.whatsapp.settings');

// Nuevas rutas para GestionSController
Route::get('/gestions', [GestionSController::class, 'index'])->name('gestions.index');
Route::get('/formularios/{id}', [GestionSController::class, 'show'])->name('formularios.show');
Route::patch('/formulario/{id}/status', [GestionSController::class, 'updateStatus'])->name('gestions.updateStatus');

// Rutas para la vista de finanzas sin protección de middleware
Route::get('/finanzas', [FinanzasUserController::class, 'index'])->name('finanzas.index');
Route::get('/finanzas/comprobantes/{id}', [FinanzasUserController::class, 'show'])->name('finanzas.show');
Route::get('/finanzas/comprobantes/subir', [FinanzasUserController::class, 'create'])->name('finanzas.create');
Route::post('/finanzas/comprobantes/subir', [FinanzasUserController::class, 'store'])->name('finanzas.store');

// Rutas para descargar archivos
Route::get('/finanzas/comprobantes/descargar-liga/{id}', [FinanzasUserController::class, 'downloadLigaDePago'])->name('finanzas.downloadLigaDePago');
Route::get('/finanzas/comprobantes/descargar-comprobante/{id}', [FinanzasUserController::class, 'downloadComprobanteAlumno'])->name('finanzas.downloadComprobanteAlumno');

// Rutas para FormularioController
Route::get('/download/comprobante/{id}', [FormularioController::class, 'downloadComprobante'])->name('formularios.downloadComprobante');

// Rutas para GestionSController
Route::get('/gestions/{id}', [GestionSController::class, 'show'])->name('gestions.show');
Route::get('/gestions/comprobante-alumno/{id}', [GestionSController::class, 'downloadComprobanteAlumno'])->name('gestions.downloadComprobanteAlumno');
Route::post('/gestions/upload-comprobante/{id}', [GestionSController::class, 'uploadComprobante'])->name('gestions.uploadComprobante');
Route::get('/control_user/gestions', [GestionSController::class, 'index'])->name('Control_user.GestionS');

// Ruta para la nueva vista ExpedienteSS
Route::get('/expediente', [FormularioController::class, 'expediente'])->name('formularios.expediente');
Route::get('alumnos/search', [AlumnoController::class, 'search'])->name('alumnos.search');

// Rutas para descargar archivos_comprobante_oficial
Route::get('gestions/downloadComprobanteOficial/{id}', [GestionSController::class, 'downloadComprobanteOficial'])->name('gestions.downloadComprobanteOficial');

// Ruta para revisión
Route::get('finanzas/downloadComprobanteOficial/{id}', [App\Http\Controllers\FinanzasUserController::class, 'downloadComprobanteOficial'])->name('finanzas.downloadComprobanteOficial');

// Rutas para la vista de expedientes finalizados para control escolar
Route::get('control_user/expedientes-finalizados', [App\Http\Controllers\GestionSController::class, 'expedientesFinalizados'])->name('control_user.expedientesFinalizados');
Route::get('control_user/{id}', [App\Http\Controllers\GestionSController::class, 'show'])->name('control_user.show');
Route::get('gestions/downloadComprobante/{id}', [App\Http\Controllers\GestionSController::class, 'downloadComprobante'])->name('gestions.downloadComprobante');

Route::resource('/materias', App\Http\Controllers\MateriaController::class);

// Rutas para el CRUD de Grupos
Route::resource('/grupos', App\Http\Controllers\GrupoController::class);

// Rutas para el controlador FormularioEController
Route::get('/formulario/{id}/edit', [FormularioEController::class, 'edit'])->name('formulario.edit');
Route::put('/formulario/{id}', [FormularioEController::class, 'update'])->name('formulario.update');

// Ruta para el carrusel
Route::resource('carrusels', CarruselController::class);
Route::resource('/carrusel', App\Http\Controllers\CarruselController::class);
Route::get('gestions/{id}', [GestionSController::class, 'show'])->name('gestions.show');

// Rutas para GestionEController
Route::get('/control_user', [GestionEController::class, 'index'])->name('control_user.index');
Route::get('/control_user/{id}', [GestionEController::class, 'show'])->name('control_user.show');
Route::patch('/control_user/{id}/updateStatus', [GestionEController::class, 'updateStatus'])->name('control_user.updateStatus');
Route::post('/control_user/{id}/uploadComprobante', [GestionEController::class, 'uploadComprobante'])->name('control_user.uploadComprobante');
Route::get('/control_user/{id}/downloadComprobante/{type}', [GestionEController::class, 'downloadComprobante'])->name('control_user.downloadComprobante');

// Definir la ruta para la vista de solicitudes de servicios financieros
Route::get('/solicitudes-servicios-s', [GestionEController::class, 'indexServicios'])->name('financiero_user.SolicitudesServiciosS');

// Ruta para subir la liga de pago
Route::post('/formularios/{id}/upload-liga-de-pago', [GestionEController::class, 'uploadLigaDePago'])->name('formularios.uploadLigaDePago');

// Ruta para ver los comprobantes financieros
Route::get('/finanzas/comprobantes/{id}', [GestionEController::class, 'indexComprobantes'])->name('finanzas.comprobantes');

// Ruta para mostrar los detalles financieros
Route::get('/finanzas/{id}', [FinanzasUserController::class, 'show'])->name('finanzas.show');

// Ruta para descargar el archivo de liga de pago
Route::get('/formularios/{id}/download-liga-de-pago', [FormularioController::class, 'downloadLigaDePago'])->name('formularios.downloadLigaDePago');

// Ruta para subir el comprobante del alumno
Route::post('/formularios/{id}/upload-comprobante-alumno', [GestionEController::class, 'uploadComprobanteAlumno'])->name('formularios.uploadComprobanteAlumno');
Route::get('/solicitudes-servicios-s', [GestionEController::class, 'indexServicios'])->name('solicitudes-servicios-s.index');

// Ruta para descargar el comprobante del alumno
Route::get('/formularios/{id}/download-comprobante-alumno', [GestionEController::class, 'downloadComprobanteAlumno'])->name('formularios.downloadComprobanteAlumno');

// Ruta para subir el comprobante oficial
Route::post('/formularios/{id}/upload-comprobante-oficial', [GestionEController::class, 'uploadComprobanteOficial'])->name('formularios.uploadComprobanteOficial');

// Rutas para subir y descargar el comprobante oficial
Route::post('/formularios/{id}/upload-comprobante-oficial', [GestionEController::class, 'uploadComprobanteOficial'])->name('formularios.uploadComprobanteOficial');
Route::get('/formularios/{id}/download-comprobante-oficial', [GestionEController::class, 'downloadComprobanteOficial'])->name('formularios.downloadComprobanteOficial');

// Rutas para subir y descargar el comprobante
Route::post('/formularios/{id}/upload-student-receipt', [GestionEController::class, 'uploadStudentReceipt'])->name('formularios.uploadStudentReceipt');
Route::get('/formularios/{id}/download-student-receipt', [GestionEController::class, 'downloadStudentReceipt'])->name('formularios.downloadStudentReceipt');

Route::get('carrusel/image/{id}', [CarruselController::class, 'getImage'])->name('carrusel.image');

Route::get('/debug-sessions', [LoginController::class, 'debugSessions']);

// Rutas para maestros
Route::prefix('maestros')->name('maestros.')->middleware(['auth', 'role:maestro'])->group(function () {
    // Dashboard de maestros
    Route::get('/dashboard', [App\Http\Controllers\MaestroController::class, 'dashboard'])->name('dashboard');
    
    // Rutas de tareas para maestros
    Route::get('/tareas', [App\Http\Controllers\Maestros\TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/crear', [App\Http\Controllers\Maestros\TareaController::class, 'create'])->name('tareas.create');
    Route::post('/tareas', [App\Http\Controllers\Maestros\TareaController::class, 'store'])->name('tareas.store');
    Route::get('/tareas/{tarea}', [App\Http\Controllers\Maestros\TareaController::class, 'show'])->name('tareas.show');
    Route::get('/tareas/{tarea}/editar', [App\Http\Controllers\Maestros\TareaController::class, 'edit'])->name('tareas.edit');
    Route::put('/tareas/{tarea}', [App\Http\Controllers\Maestros\TareaController::class, 'update'])->name('tareas.update');
    Route::delete('/tareas/{tarea}', [App\Http\Controllers\Maestros\TareaController::class, 'destroy'])->name('tareas.destroy');
    Route::patch('/tareas/{tarea}/toggle-estado', [App\Http\Controllers\Maestros\TareaController::class, 'toggleEstado'])->name('tareas.toggle-estado');
    
    // Rutas de calificaciones para maestros
    Route::get('/calificaciones', [App\Http\Controllers\Maestros\CalificacionController::class, 'index'])->name('calificaciones.index');
    Route::get('/calificaciones/crear', [App\Http\Controllers\Maestros\CalificacionController::class, 'create'])->name('calificaciones.create');
    Route::post('/calificaciones', [App\Http\Controllers\Maestros\CalificacionController::class, 'store'])->name('calificaciones.store');
    Route::get('/calificaciones/{calificacion}/editar', [App\Http\Controllers\Maestros\CalificacionController::class, 'edit'])->name('calificaciones.edit');
    Route::put('/calificaciones/{calificacion}', [App\Http\Controllers\Maestros\CalificacionController::class, 'update'])->name('calificaciones.update');
    Route::delete('/calificaciones/{calificacion}', [App\Http\Controllers\Maestros\CalificacionController::class, 'destroy'])->name('calificaciones.destroy');
    Route::get('/calificaciones/reportes', [App\Http\Controllers\Maestros\CalificacionController::class, 'reportes'])->name('calificaciones.reportes');
    Route::get('/ajax/tareas-by-materia', [App\Http\Controllers\Maestros\CalificacionController::class, 'getTareasByMateria'])->name('ajax.tareas-by-materia');
});

Route::resource('/especialidades', App\Http\Controllers\EspecialidadeController::class);

// Rutas para estudiantes (portal estudiantil)
Route::prefix('estudiantes')->name('estudiantes.')->middleware(['auth:alumno'])->group(function () {
    // Rutas de horarios para estudiantes
    Route::get('/horarios', [App\Http\Controllers\EstudianteController::class, 'horarios'])->name('horarios');
    Route::get('/horarios/{horario}', [App\Http\Controllers\EstudianteController::class, 'showHorario'])->name('horarios.show');
    Route::get('/horarios-semanal', [App\Http\Controllers\EstudianteController::class, 'horarioSemanal'])->name('horarios.semanal');
    
    // Rutas de salas para estudiantes
    Route::get('/salas', [App\Http\Controllers\EstudianteController::class, 'salas'])->name('salas');
    Route::get('/salas/{sala}', [App\Http\Controllers\EstudianteController::class, 'showSala'])->name('salas.show');
    
    // Rutas de tareas para estudiantes
    Route::get('/tareas', [App\Http\Controllers\EstudianteController::class, 'tareas'])->name('tareas');
    Route::get('/tareas/{tarea}', [App\Http\Controllers\EstudianteController::class, 'showTarea'])->name('tareas.show');
    
    // Rutas de calificaciones para estudiantes
    Route::get('/calificaciones', [App\Http\Controllers\EstudianteController::class, 'calificaciones'])->name('calificaciones');
    Route::get('/calificaciones/reportes', [App\Http\Controllers\EstudianteController::class, 'reportesCalificaciones'])->name('calificaciones.reportes');
    Route::get('/calificaciones/boleta', [App\Http\Controllers\EstudianteController::class, 'boleta'])->name('calificaciones.boleta');
    
    // Rutas de reuniones para estudiantes
    Route::get('/reuniones', [App\Http\Controllers\EstudianteController::class, 'reuniones'])->name('reuniones');
    Route::get('/reuniones/activas', [App\Http\Controllers\EstudianteController::class, 'reunionesActivas'])->name('reuniones.activas');
    Route::post('/reuniones/{reunion}/unirse', [App\Http\Controllers\EstudianteController::class, 'unirseReunion'])->name('reuniones.unirse');
});

// Rutas para reuniones de alumnos
Route::prefix('alumnos')->name('alumnos.')->middleware(['auth:alumno'])->group(function () {
    Route::get('/reuniones', [App\Http\Controllers\ReunionController::class, 'index'])->name('reuniones.index');
    Route::get('/reuniones/{reunion}', [App\Http\Controllers\ReunionController::class, 'show'])->name('reuniones.show');
    Route::post('/reuniones/{reunion}/unirse', [App\Http\Controllers\ReunionController::class, 'unirse'])->name('reuniones.unirse');
    Route::get('/reuniones/api/hoy', [App\Http\Controllers\ReunionController::class, 'reunionesHoy'])->name('reuniones.hoy');
    Route::get('/reuniones/buscar', [App\Http\Controllers\ReunionController::class, 'buscar'])->name('reuniones.buscar');
});
