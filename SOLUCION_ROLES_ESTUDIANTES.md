# Solución al problema: "El rol de alumno se pierde al entrar a horarios y salas"

## Problema identificado
Las rutas del portal estudiantil (`/estudiantes/horarios` y `/estudiantes/salas`) no estaban protegidas por middleware de autenticación correcto y había un conflicto entre los guards `web` y `alumno`.

## Cambios realizados

### 1. Protección de rutas con middleware correcto
**Archivo:** `routes/web.php`
- Se agregó middleware `auth:alumno` a las rutas de estudiantes (corregido de `auth:web`)
- Ahora las rutas están protegidas para usuarios autenticados con el guard `alumno`

### 2. Simplificación del controlador
**Archivo:** `app/Http/Controllers/EstudianteController.php`
- Se simplificó el middleware a solo `auth:alumno`
- Se removió la verificación adicional de roles ya que el guard `alumno` garantiza que solo alumnos autenticados accedan

### 3. Comprensión del sistema de autenticación
- **Alumnos**: Se autentican con CURP + número de control usando guard `alumno`
- **Usuarios del sistema**: Se autentican con email + password usando guard `web`

## Sistema de autenticación

### Para alumnos (guard `alumno`)
- **Credenciales**: CURP como usuario + número de control como contraseña
- **Modelo**: `App\Models\Alumno`
- **Rutas protegidas**: 
  - `/alumnos_user` (dashboard de alumnos)
  - `/formulario` (formularios estudiantiles)
  - `/estudiantes/horarios` (portal estudiantil - horarios)
  - `/estudiantes/salas` (portal estudiantil - salas)

### Para usuarios del sistema (guard `web`)
- **Credenciales**: Email + password
- **Modelo**: `App\Models\User`
- **Rutas protegidas**: Todas las rutas administrativas y de gestión

## Cómo probar la solución

### 1. Autenticación como alumno
1. Ir a `/login`
2. Usar CURP como "Usuario"
3. Usar número de control como "Contraseña"
4. Después del login, navegar a:
   - `/estudiantes/horarios`
   - `/estudiantes/salas`

### 2. Verificar que funciona
- No debería redirigir al login
- Debería mostrar la información de horarios y salas
- La sesión de alumno se mantiene

## Verificación de la solución

### Comando de verificación (ya no aplicable)
El comando `php artisan verify:student-setup` era para el guard `web`, ya no es necesario.

### Pruebas manuales
1. Iniciar sesión como alumno (CURP + número de control)
2. Navegar a `/estudiantes/horarios`
3. Navegar a `/estudiantes/salas`
4. Verificar que no se pierde la sesión

## Estructura final

### Guard Alumno
- **Modelo**: `Alumno`
- **Autenticación**: CURP + número de control
- **Rutas**: Portal estudiantil y formularios

### Guard Web
- **Modelo**: `User` con roles (admin, maestro, control_escolar, etc.)
- **Autenticación**: Email + password
- **Rutas**: Sistema administrativo

## Notas importantes
- Los alumnos ya no necesitan roles específicos, el guard `alumno` garantiza el acceso
- La autenticación está correctamente separada por tipos de usuario
- No hay conflictos entre guards
- El sistema mantiene las sesiones correctamente
