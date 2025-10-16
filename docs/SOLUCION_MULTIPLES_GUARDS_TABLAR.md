# Solución: Pérdida de Roles y Componentes de Tablar con Múltiples Guards

## 📋 Resumen del Problema

El sistema SAFyCE utiliza múltiples guards de autenticación (`web` y `alumno`), pero los componentes de Tablar (header, sidebar, filtros de menú) solo consideraban el guard por defecto (`web`), causando que:

- Los usuarios autenticados con el guard `alumno` vieran "Sin rol"
- El sidebar no mostrara elementos del menú
- Los componentes de Tablar no cargaran correctamente
- Se perdiera la funcionalidad de navegación

## ⚠️ Síntomas del Error

1. **Mensaje "Sin rol"** en el header de Tablar
2. **Sidebar vacío** sin elementos de navegación
3. **Componentes de Tablar no funcionan** apropiadamente
4. **Pérdida de contexto de usuario** en biblioteca virtual

## 🔍 Análisis de la Causa

### Problema Principal: Uso de `Auth::user()` en lugar de múltiples guards

Los componentes de Tablar estaban configurados para usar únicamente:
```php
$user = Auth::user(); // Solo considera el guard 'web'
```

Cuando un alumno se autentica usando el guard `alumno`, `Auth::user()` devuelve `null` porque no está autenticado en el guard por defecto.

### Archivos Afectados:
1. `routes/web.php` - Rutas duplicadas
2. `resources/views/vendor/tablar/partials/header/top-right.blade.php` - Header
3. `app/Filter/RolePermissionMenuFilter.php` - Filtro de menú del sidebar
4. `app/Http/Controllers/BibliotecaVirtualController.php` - Controlador

## 🛠️ Solución Implementada

### 1. Corrección de Rutas Duplicadas

**Problema:** Rutas duplicadas para biblioteca virtual causaban conflictos de guards.

**Archivo:** `routes/web.php`

**Antes:**
```php
// Ruta sin middleware (línea 138)
Route::get('/biblioteca-virtual', [BibliotecaVirtualController::class, 'bibliotecaEstudiantes'])
    ->name('biblioteca-virtual.estudiantes');

// Ruta dentro de auth:alumno (línea 367)
Route::get('/biblioteca-virtual', [BibliotecaVirtualController::class, 'bibliotecaEstudiantes'])
    ->name('biblioteca-virtual');
```

**Después:**
```php
// Ruta única con middleware MultiGuardAuth
Route::middleware(['multi.auth'])->group(function () {
    Route::get('/biblioteca-virtual', [BibliotecaVirtualController::class, 'bibliotecaEstudiantes'])
        ->name('biblioteca-virtual.estudiantes');
});
```

### 2. Corrección del Header de Tablar

**Archivo:** `resources/views/vendor/tablar/partials/header/top-right.blade.php`

**Antes:**
```php
@auth
    @php
        $user = Auth::user(); // Solo guard 'web'
        $userName = 'Usuario';
        $userRoles = $user->roles->pluck('name')->implode(', ') ?? 'Sin rol';
    @endphp
```

**Después:**
```php
@php
    // Detectar usuario autenticado en cualquier guard
    $webUser = auth('web')->user();
    $alumnoUser = auth('alumno')->user();
    $user = $webUser ?? $alumnoUser;
    $isAuthenticated = $user !== null;
@endphp

@if($isAuthenticated)
    @php
        $userName = 'Usuario';
        $userRoles = 'Sin rol';
        
        try {
            if ($user && method_exists($user, 'getRoleNames')) {
                $rolesCollection = $user->getRoleNames();
                $userRoles = $rolesCollection->implode(', ') ?: 'Sin rol';
            }
        } catch (\Exception $e) {
            $userRoles = 'Sin rol';
        }

        // Obtener el nombre según el tipo de usuario
        if ($webUser) {
            $userName = $webUser->name ?? 'Usuario Web';
        } elseif ($alumnoUser) {
            $userName = $alumnoUser->Nombre ?? 'Alumno';
        }
    @endphp
```

### 3. Corrección del Filtro de Menú del Sidebar

**Archivo:** `app/Filter/RolePermissionMenuFilter.php`

**Antes:**
```php
protected function isVisible($item)
{
    $user = Auth::user(); // Solo guard 'web'

    if (!$user) {
        return false;
    }
    // ... resto de la lógica
}
```

**Después:**
```php
protected function isVisible($item)
{
    // Detectar usuario autenticado en cualquier guard
    $webUser = auth('web')->user();
    $alumnoUser = auth('alumno')->user();
    $user = $webUser ?? $alumnoUser;

    if (!$user) {
        return false;
    }
    // ... resto de la lógica
}

protected function userHasAnyRole($user, $roles)
{
    if (!$user || !method_exists($user, 'roles') || !$user->roles) {
        return false;
    }

    $userRoles = $user->roles->pluck('name')->toArray();
    foreach ($roles as $role) {
        if (in_array($role, $userRoles)) {
            return true;
        }
    }
    return false;
}
```

### 4. Optimización del Controlador

**Archivo:** `app/Http/Controllers/BibliotecaVirtualController.php`

**Mejora del método `bibliotecaEstudiantes()`:**
```php
public function bibliotecaEstudiantes()
{
    // El middleware MultiGuardAuth ya verificó la autenticación
    $webUser = auth('web')->user();
    $alumnoUser = auth('alumno')->user();
    
    // Determinar usuario actual (el middleware garantiza que uno existe)
    $currentUser = $webUser ?? $alumnoUser;
    $userType = $webUser ? 'web' : 'alumno';
    
    // Obtener roles de manera segura
    $rolesArray = [];
    $userRoles = [];
    
    if ($currentUser && method_exists($currentUser, 'getRoleNames')) {
        try {
            $rolesCollection = $currentUser->getRoleNames();
            $rolesArray = $rolesCollection->toArray();
            $userRoles = $rolesArray;
            
            \Log::info('BibliotecaVirtual - Usuario autenticado correctamente', [
                'user_type' => $userType,
                'user_id' => $currentUser->id,
                'roles_count' => count($rolesArray),
                'roles' => $rolesArray,
            ]);
        } catch (\Exception $e) {
            \Log::error('BibliotecaVirtual - Error obteniendo roles: ' . $e->getMessage());
            $userRoles = [];
            $rolesArray = [];
        }
    }
    // ... resto del método
}
```

## 🧪 Verificación de la Solución

### Comandos de Prueba

```bash
# Limpiar caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Verificar rutas de biblioteca virtual
php artisan route:list | grep -E "(biblioteca|virtual)"

# Probar autenticación de alumno
php artisan tinker --execute="
\$alumno = App\Models\Alumno::first();
if (\$alumno) {
    auth('alumno')->login(\$alumno);
    echo 'Alumno: ';
    dump(auth('alumno')->user()->Nombre);
    echo 'Roles: ';
    dump(auth('alumno')->user()->getRoleNames()->toArray());
}
"

# Probar filtro de menú
php artisan tinker --execute="
\$filter = new App\Filter\RolePermissionMenuFilter();
\$testItem = ['text' => 'Test', 'roles' => ['alumno']];
\$result = \$filter->transform(\$testItem);
dump(\$result);
"
```

### Resultados Esperados

✅ **Header muestra nombre y rol correcto**  
✅ **Sidebar muestra elementos del menú apropiados**  
✅ **Componentes de Tablar funcionan completamente**  
✅ **No aparece "Sin rol"**  
✅ **Navegación disponible para todos los tipos de usuario**

## 📁 Archivos Modificados

```
routes/web.php
resources/views/vendor/tablar/partials/header/top-right.blade.php
app/Filter/RolePermissionMenuFilter.php
app/Http/Controllers/BibliotecaVirtualController.php
```

## 🔧 Configuración Requerida

### Guards en `config/auth.php`
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'alumno' => [
        'driver' => 'session',
        'provider' => 'alumnos',
    ],
],
```

### Middleware MultiGuardAuth
Asegurar que esté registrado en `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'multi.auth' => \App\Http\Middleware\MultiGuardAuth::class,
    ]);
})
```

## 📝 Notas Importantes

1. **Siempre usar múltiples guards** cuando el sistema tenga diferentes tipos de usuarios
2. **Verificar que todos los componentes de UI** consideren todos los guards
3. **Probar con usuarios de diferentes tipos** para asegurar funcionalidad completa
4. **Limpiar caches** después de hacer cambios en autenticación
5. **Documentar logs** para facilitar debugging futuro

## 🚀 Prevención Futura

Para evitar este tipo de problemas en el futuro:

1. **Usar helpers consistentes:**
   ```php
   // En lugar de Auth::user()
   $webUser = auth('web')->user();
   $alumnoUser = auth('alumno')->user();
   $user = $webUser ?? $alumnoUser;
   ```

2. **Crear middleware centralizado** para manejar múltiples guards
3. **Probar con diferentes tipos de usuario** en desarrollo
4. **Revisar todos los componentes de UI** cuando se agreguen nuevos guards

---

**Autor:** GitHub Copilot  
**Fecha:** 15 de Octubre, 2025  
**Proyecto:** SAFyCE - Sistema Académico y Financiero  
**Rama:** Maestros-beta
