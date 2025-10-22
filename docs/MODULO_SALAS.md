# 📚 Documentación del Módulo de Salas

## 📋 Índice
1. [Descripción General](#-descripción-general)
2. [Arquitectura del Sistema](#-arquitectura-del-sistema)
3. [Modelos](#-modelos)
4. [Controladores](#-controladores)
5. [Rutas](#-rutas)
6. [Vistas](#-vistas)
7. [Funcionalidades](#-funcionalidades)
8. [Permisos y Seguridad](#-permisos-y-seguridad)
9. [Base de Datos](#-base-de-datos)
10. [API Endpoints](#-api-endpoints)
11. [Guía de Uso](#-guía-de-uso)
12. [Solución de Problemas](#-solución-de-problemas)

---

## 🎯 Descripción General

El **Módulo de Salas** es un sistema integral para la gestión de aulas virtuales y físicas en el sistema educativo SAFyCE. Permite administrar salas, crear reuniones virtuales, gestionar horarios y facilitar el acceso de estudiantes a clases en línea.

### Características Principales
- 🏢 **Gestión de Salas**: Crear, editar, eliminar y visualizar salas/aulas
- 📅 **Reuniones Virtuales**: Integración con Google Meet, Zoom, Teams y Webex
- 👥 **Acceso Estudiantil**: Portal para que estudiantes vean y se unan a reuniones
- 📊 **Horarios**: Visualización de horarios de clases por sala
- 🔐 **Permisos**: Control de acceso basado en roles (maestros, administradores, estudiantes)
- ⚡ **Tiempo Real**: Validación de disponibilidad de reuniones en tiempo real

---

## 🏗️ Arquitectura del Sistema

```
├── app/
│   ├── Models/
│   │   ├── Sala.php              # Modelo principal de salas
│   │   ├── Reunion.php           # Modelo de reuniones virtuales
│   │   └── Horario.php           # Modelo de horarios
│   ├── Http/Controllers/
│   │   ├── SalaController.php    # Controlador principal de salas
│   │   └── EstudianteController.php # Controlador para estudiantes
│   └── Policies/
│       └── SalaPolicy.php        # Políticas de autorización
├── resources/views/
│   ├── salas/                    # Vistas de administración
│   └── estudiantes/salas/        # Vistas para estudiantes
├── database/migrations/
│   ├── create_salas_table.php
│   ├── create_reuniones_table.php
│   └── create_horarios_table.php
└── routes/web.php                # Definición de rutas
```

---

## 📦 Modelos

### 🏢 Modelo Sala (`app/Models/Sala.php`)

**Campos principales:**
```php
protected $fillable = [
    'nombre',           // Nombre de la sala (ej: "Aula 101")
    'codigo',          // Código único de la sala
    'capacidad',       // Capacidad máxima de personas
    'descripcion',     // Descripción opcional
    'activo'           // Estado activo/inactivo
];
```

**Relaciones:**
- `hasMany(Horario::class)` - Horarios asignados a la sala
- `hasMany(Reunion::class)` - Reuniones programadas en la sala

**Métodos principales:**
- `estaDisponible($dia, $horaInicio, $horaFin)` - Verifica disponibilidad
- `scopeActivas($query)` - Solo salas activas

### 📅 Modelo Reunion (`app/Models/Reunion.php`)

**Campos principales:**
```php
protected $fillable = [
    'titulo',              // Título de la reunión
    'descripcion',         // Descripción opcional
    'fecha',              // Fecha de la reunión
    'hora',               // Hora de inicio
    'duracion',           // Duración en minutos
    'plataforma',         // meet|zoom|teams|webex
    'enlace_reunion',     // URL de la reunión
    'codigo_reunion',     // Código único
    'sala_id',            // ID de la sala
    'user_id',            // ID del creador
    'tipo',               // clase|tutorial|reunion|examen|otro
    'estado',             // activa|cancelada|finalizada
    'max_participantes'   // Máximo número de participantes
];
```

**Métodos importantes:**
- `puedeUnirse()` - Verifica si un estudiante puede unirse
- `estaEnCurso()` - Verifica si la reunión está en progreso
- `haTerminado()` - Verifica si la reunión ha finalizado
- `getPlataformaNombreAttribute()` - Nombre legible de la plataforma

**Scopes:**
- `scopeActivas($query)` - Solo reuniones activas
- `scopeProximasActivas($query)` - Reuniones futuras y activas
- `scopeHoy($query)` - Reuniones de hoy

---

## 🎮 Controladores

### 🏢 SalaController (`app/Http/Controllers/SalaController.php`)

**Métodos CRUD:**
- `index()` - Lista todas las salas
- `create()` - Formulario de creación
- `store()` - Guardar nueva sala
- `show($sala)` - Mostrar detalles de sala
- `edit($sala)` - Formulario de edición
- `update($sala)` - Actualizar sala
- `destroy($sala)` - Eliminar sala

**Métodos de Reuniones:**
- `crearReunion(Request $request)` - Crear nueva reunión
- `reunionesSala(Sala $sala)` - Obtener reuniones de una sala
- `obtenerReunion(Reunion $reunion)` - Obtener datos de reunión
- `cancelarReunion(Reunion $reunion)` - Cancelar reunión
- `eliminarReunion(Reunion $reunion)` - Eliminar reunión

**Métodos Auxiliares:**
- `verificarDisponibilidad()` - Verificar disponibilidad de sala
- `generarCodigoReunion($plataforma)` - Generar código único

### 👨‍🎓 EstudianteController (`app/Http/Controllers/EstudianteController.php`)

**Métodos para Estudiantes:**
- `salas()` - Lista de salas para estudiantes
- `showSala(Sala $sala)` - Vista detallada de sala para estudiantes

---

## 🛣️ Rutas

### Rutas de Administración
```php
// CRUD de Salas
Route::resource('salas', SalaController::class);

// Verificación de disponibilidad
Route::post('salas/verificar-disponibilidad', [SalaController::class, 'verificarDisponibilidad'])
    ->name('salas.verificar-disponibilidad');
```

### Rutas de Reuniones
```php
// Crear reunión
Route::post('salas/crear-reunion', [SalaController::class, 'crearReunion'])
    ->name('salas.crear-reunion');

// Obtener reuniones de sala
Route::get('salas/{sala}/reuniones', [SalaController::class, 'reunionesSala'])
    ->name('salas.reuniones');

// Gestión de reuniones
Route::get('reuniones/{reunion}', [SalaController::class, 'obtenerReunion'])
    ->name('reuniones.obtener');

Route::patch('reuniones/{reunion}/cancelar', [SalaController::class, 'cancelarReunion'])
    ->name('reuniones.cancelar');

Route::delete('reuniones/{reunion}', [SalaController::class, 'eliminarReunion'])
    ->name('reuniones.eliminar');
```

### Rutas para Estudiantes
```php
// Portal de estudiantes
Route::prefix('estudiantes')->name('estudiantes.')->middleware('auth:alumno')->group(function () {
    Route::get('/salas', [EstudianteController::class, 'salas'])->name('salas');
    Route::get('/salas/{sala}', [EstudianteController::class, 'showSala'])->name('salas.show');
});
```

---

## 🖼️ Vistas

### Vistas de Administración (`resources/views/salas/`)

#### `index.blade.php` - Lista de Salas
- Tabla con todas las salas
- Filtros por estado y capacidad
- Botones de acción (ver, editar, eliminar)
- Botón para crear nueva sala

#### `create.blade.php` - Crear Sala
- Formulario de creación
- Validación en tiempo real
- Campos: nombre, código, capacidad, descripción

#### `show.blade.php` - Detalles de Sala
**Secciones principales:**
- **Información de la Sala**: Datos básicos
- **Reuniones Programadas**: Panel lateral con reuniones activas
- **Horarios de Clase**: Tabla con horarios asignados
- **Modal de Creación**: Formulario para crear reuniones
- **Botones de Administración**: Cancelar/Eliminar reuniones (para creadores)

**Funcionalidades:**
- Crear reuniones virtuales
- Ver reuniones programadas
- Acceso directo a reuniones (estudiantes)
- Administrar reuniones (maestros)

#### `edit.blade.php` - Editar Sala
- Formulario pre-llenado
- Validación de campos únicos
- Opción de activar/desactivar

### Vistas de Estudiantes (`resources/views/estudiantes/salas/`)

#### `index.blade.php` - Portal de Salas
- Cards con salas disponibles
- Información básica de cada sala
- Enlaces a vista detallada

#### `show.blade.php` - Vista de Sala para Estudiantes
**Características especiales:**
- **Diseño Estudiantil**: Interfaz simplificada y amigable
- **Reuniones Destacadas**: Panel prominente con reuniones disponibles
- **Acceso Directo**: Botones para unirse a reuniones sin JavaScript
- **Información de Horarios**: Horarios de clase de la sala
- **Estado del Aula**: Información sobre disponibilidad

---

## ⚙️ Funcionalidades

### 🏢 Gestión de Salas

#### Crear Sala
```php
// Campos requeridos
- nombre: string, max 255 caracteres
- codigo: string, único, max 50 caracteres  
- capacidad: integer, mínimo 1
- descripcion: opcional, texto
- activo: boolean, default true
```

#### Editar Sala
- Validación de unicidad de código
- Verificación de horarios activos antes de eliminar
- Mantenimiento de relaciones con horarios y reuniones

### 📅 Reuniones Virtuales

#### Crear Reunión
**Formulario de Creación:**
```javascript
// Campos del modal
- titulo: Título descriptivo
- fecha: Fecha futura (validación >= hoy)
- hora: Hora en formato HH:MM
- duracion_minutos: 30, 60, 90, 120, 180 minutos
- plataforma: meet, zoom, teams, webex
- enlace: URL válida de la reunión
- descripcion: Opcional
```

**Proceso de Creación:**
1. Validación de campos
2. Generación de código único por plataforma
3. Almacenamiento en base de datos
4. Respuesta JSON con datos de la reunión

#### Plataformas Soportadas

| Plataforma | Código | Formato de Código |
|------------|--------|-------------------|
| Google Meet | `meet` | `abc-defg-hij` |
| Zoom | `zoom` | `1234567890` |
| Microsoft Teams | `teams` | `teams_uniqid` |
| Cisco Webex | `webex` | `123456789` |

#### Lógica de Disponibilidad
```php
public function puedeUnirse()
{
    // Condiciones para unirse:
    // 1. Reunión debe estar activa
    // 2. Tiempo actual entre 15 minutos antes y 2 horas después
    
    if ($this->estado !== 'activa') return false;
    
    $fechaHoraReunion = Carbon::parse($this->fecha_hora);
    $ahora = now();
    
    return $ahora->between(
        $fechaHoraReunion->copy()->subMinutes(15),
        $fechaHoraReunion->copy()->addHours(2)
    );
}
```

### 👥 Acceso de Estudiantes

#### Portal de Salas
- Lista de salas disponibles
- Información básica (nombre, capacidad)
- Acceso a vista detallada

#### Vista de Sala
- **Reuniones Disponibles**: Cards con reuniones que pueden unirse
- **Botón "Unirse"**: Enlace directo que abre en nueva pestaña
- **Estado Visual**: Indicadores de disponibilidad
- **Información Contextual**: Horarios y detalles de la sala

### 🔧 Administración de Reuniones

#### Para Maestros/Creadores
**Botones de Administración:**
- **Cancelar**: Cambia estado a 'cancelada' (mantiene registro)
- **Eliminar**: Elimina permanentemente de la base de datos

**Permisos:**
- Solo el creador de la reunión puede administrarla
- Administradores pueden gestionar cualquier reunión

**Confirmaciones de Seguridad:**
```javascript
// Cancelar reunión
Swal.fire({
    title: '⚠️ ¿Cancelar Reunión?',
    text: 'La reunión se marcará como cancelada',
    icon: 'warning'
});

// Eliminar reunión  
Swal.fire({
    title: '🗑️ ¿Eliminar Reunión?',
    text: '⚠️ Esta acción no se puede deshacer',
    icon: 'error'
});
```

---

## 🔐 Permisos y Seguridad

### Roles y Permisos

#### Administradores
- ✅ Crear, editar, eliminar salas
- ✅ Crear, cancelar, eliminar cualquier reunión
- ✅ Ver todas las salas y reuniones
- ✅ Gestionar horarios

#### Maestros/Profesores
- ✅ Ver salas
- ✅ Crear reuniones
- ✅ Administrar sus propias reuniones
- ❌ Modificar salas
- ❌ Eliminar reuniones de otros

#### Estudiantes
- ✅ Ver salas asignadas
- ✅ Unirse a reuniones disponibles
- ✅ Ver horarios de clases
- ❌ Crear o modificar reuniones
- ❌ Administrar salas

### Gates y Políticas

```php
// Gates definidos
Gate::define('gestionar salas', function ($user) {
    return $user->hasRole(['administrador', 'director']);
});

Gate::define('crear reuniones', function ($user) {
    return $user->hasRole(['administrador', 'maestro', 'director']);
});
```

### Validaciones de Seguridad

#### En Controladores
```php
// Verificación de permisos para eliminar reunión
if ($reunion->user_id !== Auth::id() && !Auth::user()->hasRole('administrador')) {
    return response()->json([
        'success' => false,
        'message' => 'No tienes permisos para eliminar esta reunión'
    ], 403);
}
```

#### Protección CSRF
- Todos los formularios incluyen token CSRF
- Peticiones AJAX incluyen header `X-CSRF-TOKEN`
- Validación automática por middleware

---

## 🗄️ Base de Datos

### Tabla `salas`
```sql
CREATE TABLE `salas` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `nombre` varchar(255) NOT NULL,
    `codigo` varchar(50) NOT NULL UNIQUE,
    `capacidad` int NOT NULL,
    `descripcion` text,
    `activo` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);
```

### Tabla `reuniones`
```sql
CREATE TABLE `reuniones` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `titulo` varchar(255) NOT NULL,
    `descripcion` text,
    `fecha` date NOT NULL,
    `hora` time NOT NULL,
    `duracion` int NOT NULL, -- minutos
    `plataforma` enum('meet','zoom','teams','webex') NOT NULL,
    `enlace_reunion` varchar(500) NOT NULL,
    `codigo_reunion` varchar(100),
    `sala_id` bigint unsigned,
    `user_id` bigint unsigned NOT NULL,
    `tipo` enum('clase','tutorial','reunion','examen','otro') NOT NULL,
    `estado` enum('activa','cancelada','finalizada') DEFAULT 'activa',
    `max_participantes` int DEFAULT 100,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    
    FOREIGN KEY (`sala_id`) REFERENCES `salas`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);
```

### Índices Importantes
```sql
-- Índices para optimizar consultas
CREATE INDEX idx_reuniones_fecha_estado ON reuniones(fecha, estado);
CREATE INDEX idx_reuniones_sala_fecha ON reuniones(sala_id, fecha);
CREATE INDEX idx_salas_activo ON salas(activo);
```

---

## 🔌 API Endpoints

### Reuniones

#### POST `/salas/crear-reunion`
**Crear nueva reunión**
```json
// Request
{
    "titulo": "Clase de Matemáticas",
    "fecha": "2025-10-15",
    "hora": "10:00",
    "duracion_minutos": 60,
    "plataforma": "meet",
    "enlace": "https://meet.google.com/abc-defg-hij",
    "sala_id": 1,
    "descripcion": "Clase sobre álgebra lineal"
}

// Response (Success)
{
    "success": true,
    "message": "Reunión creada exitosamente",
    "reunion": {
        "id": 15,
        "titulo": "Clase de Matemáticas",
        "fecha": "15/10/2025",
        "hora": "10:00",
        "enlace": "https://meet.google.com/abc-defg-hij",
        "plataforma": "meet"
    }
}
```

#### PATCH `/reuniones/{id}/cancelar`
**Cancelar reunión**
```json
// Response
{
    "success": true,
    "message": "Reunión cancelada exitosamente"
}
```

#### DELETE `/reuniones/{id}`
**Eliminar reunión**
```json
// Response
{
    "success": true,
    "message": "Reunión 'Clase de Matemáticas' eliminada exitosamente"
}
```

#### GET `/reuniones/{id}`
**Obtener datos de reunión**
```json
// Response
{
    "success": true,
    "reunion": {
        "id": 15,
        "titulo": "Clase de Matemáticas",
        "enlace": "https://meet.google.com/abc-defg-hij",
        "plataforma": "Google Meet"
    }
}
```

### Salas

#### POST `/salas/verificar-disponibilidad`
**Verificar disponibilidad de sala**
```json
// Request
{
    "sala_id": 1,
    "dia_semana": "lunes",
    "hora_inicio": "10:00",
    "hora_fin": "11:00"
}

// Response
{
    "disponible": true,
    "mensaje": "La sala está disponible."
}
```

---

## 📖 Guía de Uso

### Para Administradores

#### 1. Crear una Nueva Sala
1. Navegar a **Salas > Crear Sala**
2. Llenar formulario:
   - **Nombre**: Ej. "Aula 101"
   - **Código**: Ej. "A101" (único)
   - **Capacidad**: Ej. 30
   - **Descripción**: Opcional
3. Hacer clic en **"Guardar Sala"**

#### 2. Gestionar Salas Existentes
1. Ir a **Salas > Lista de Salas**
2. Usar filtros para encontrar sala específica
3. Opciones disponibles:
   - **Ver**: Detalles completos
   - **Editar**: Modificar información
   - **Eliminar**: Solo si no tiene horarios activos

### Para Maestros

#### 1. Crear Reunión Virtual
1. Navegar a **Salas** y seleccionar una sala
2. Hacer clic en **"Crear Reunión"**
3. Llenar el modal:
   - **Título**: Descriptivo de la clase
   - **Fecha/Hora**: Cuándo será la reunión
   - **Duración**: Tiempo estimado
   - **Plataforma**: Google Meet, Zoom, etc.
   - **Enlace**: URL de la reunión creada en la plataforma
4. Hacer clic en **"Crear Reunión"**

#### 2. Administrar Reuniones
1. En la vista de sala, localizar la reunión
2. Usar botones de administración:
   - **Cancelar**: Para reuniones que no se realizarán
   - **Eliminar**: Para eliminar permanentemente

### Para Estudiantes

#### 1. Ver Salas Disponibles
1. Desde el portal de estudiante, ir a **"Salas"**
2. Explorar las salas disponibles
3. Hacer clic en una sala para ver detalles

#### 2. Unirse a Reunión
1. En la vista de sala, buscar sección **"Reuniones Programadas"**
2. Identificar reuniones con botón verde **"🎥 Unirse a Reunión"**
3. Hacer clic para abrir la reunión en nueva pestaña
4. Seguir instrucciones de la plataforma de videollamada

---

## 🛠️ Solución de Problemas

### Errores Comunes

#### "Error de Conexión" al crear reunión
**Problema**: Aparece error al intentar crear reunión

**Soluciones**:
1. Verificar que el enlace de reunión sea válido
2. Confirmar que la plataforma seleccionada coincida con el enlace
3. Revisar permisos del usuario
4. Verificar conexión a internet

#### "Reunión no disponible" para estudiantes
**Problema**: Estudiantes no pueden unirse a reuniones

**Soluciones**:
1. Verificar que la reunión esté activa
2. Confirmar que estén en el rango de tiempo permitido (15 min antes - 2 horas después)
3. Revisar que estén autenticados como estudiante
4. Verificar que la sala tenga reuniones programadas

#### Botones de administración no aparecen
**Problema**: Maestros no ven botones de cancelar/eliminar

**Soluciones**:
1. Confirmar que sea el creador de la reunión
2. Verificar permisos de "crear reuniones"
3. Revisar que esté autenticado correctamente
4. Comprobar que la reunión exista y esté activa

### Logs y Debugging

#### Revisar Logs de Laravel
```bash
# Ver últimos errores
tail -f storage/logs/laravel.log

# Buscar errores específicos de reuniones
grep -i "reunion\|meeting" storage/logs/laravel.log
```

#### Debug en Navegador
1. Abrir **Consola de Desarrollador** (F12)
2. Verificar errores JavaScript en **Console**
3. Revisar peticiones AJAX en **Network**
4. Confirmar presencia de token CSRF en headers

### Comandos Útiles

#### Verificar Rutas
```bash
php artisan route:list | grep -i sala
php artisan route:list | grep -i reunion
```

#### Limpiar Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### Verificar Base de Datos
```bash
# Verificar conexión
php artisan tinker
> DB::connection()->getPdo();

# Contar reuniones
> App\Models\Reunion::count();

# Verificar salas activas  
> App\Models\Sala::activas()->count();
```

---

## 📝 Notas Técnicas

### Consideraciones de Rendimiento
- Las consultas de reuniones están optimizadas con scopes
- Se utilizan índices en campos frecuentemente consultados
- Carga perezosa (lazy loading) en relaciones

### Seguridad
- Validación de datos en frontend y backend
- Protección CSRF en todos los formularios
- Autorización granular por roles
- Sanitización de URLs de reuniones

### Compatibilidad
- Compatible con Laravel 8+
- Soporte para múltiples plataformas de videollamada
- Responsive design para dispositivos móviles
- Compatible con navegadores modernos

### Mantenimiento
- Logs detallados para debugging
- Validaciones robustas para prevenir errores
- Código documentado y estructurado
- Fácil extensión para nuevas plataformas

---

## 🔮 Futuras Mejoras

### Funcionalidades Pendientes
- [ ] Notificaciones automáticas por email
- [ ] Integración con calendarios (Google Calendar, Outlook)
- [ ] Grabación automática de reuniones
- [ ] Estadísticas de asistencia
- [ ] Chat integrado en reuniones
- [ ] Sala de espera virtual
- [ ] Programación recurrente de reuniones

### Optimizaciones Técnicas
- [ ] Cache de consultas frecuentes
- [ ] Implementación de WebSockets para actualizaciones en tiempo real
- [ ] API RESTful completa
- [ ] Tests automatizados
- [ ] Documentación de API con Swagger

---

## 📞 Soporte

Para soporte técnico o reportar bugs:

- **Desarrollador**: GitHub Copilot Assistant
- **Proyecto**: SAFyCE - Sistema Académico
- **Versión**: 1.0
- **Última actualización**: Octubre 2025

---

*Documentación generada automáticamente para el módulo de Salas del sistema SAFyCE*
