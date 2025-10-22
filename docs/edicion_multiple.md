# Edición múltiple

Este documento describe cómo funciona la funcionalidad de "edición múltiple" en la aplicación SAFyCE. Está orientado a desarrolladores que necesitan entender, mantener o probar la característica.

## Objetivo

Permitir al usuario seleccionar múltiples registros (por ejemplo: alumnos, calificaciones, documentos) desde la vista y aplicar una acción de edición o actualización en lote.

## Flujo general

1. En la vista (Blade) el usuario marca checkboxes para seleccionar varios registros.
2. Se habilita un panel o botón "Editar selección" que abre un modal o redirige a una página con un formulario que contiene campos editables para la acción en lote.
3. Al enviar el formulario, el cliente envía una petición al servidor (normalmente POST/PATCH) con:
   - ids[]: lista de IDs seleccionados
   - campos a modificar (por ejemplo: grupo_id, estatus, fecha_limite)
4. El controlador valida la petición, aplica permisos y ejecuta la actualización en la base de datos dentro de una transacción.
5. Se responde al cliente con éxito o errores por ID si corresponde. El frontend muestra mensajes y actualiza la UI.

## Rutas (ejemplo)

Ruta recomendada en `routes/web.php`:

- POST `/alumnos/edicion-multiple` -> `AlumnoController@edicionMultiple`

También puede usarse PATCH si se prefiere semántica REST.

## Controlador (ejemplo)

Pseudocódigo para el método `edicionMultiple` en `App\Http\Controllers\AlumnoController`:

- 1) Autorizar: usar Policy o Gate (por ejemplo: `authorize('updateMultiple', Alumno::class)`)
- 2) Validar request:
  - `ids` es arreglo requerido y cada elemento es `integer|exists:alumnos,id`
  - campos opcionales según lo que se permita modificar (ej: `grupo_id`, `estatus`)
- 3) Iniciar transacción:
  - Recuperar modelos por ids
  - Para cada modelo:
    - Aplicar valores permitidos
    - Validar reglas específicas por modelo si aplica
    - Guardar
  - Commit
  - Atrapar excepciones y rollback
- 4) Retornar respuesta JSON o redirección con mensaje

Ejemplo mínimo (Laravel):

```php
public function edicionMultiple(Request $request)
{
    $this->authorize('updateMultiple', Alumno::class);

    $data = $request->validate([
        'ids' => 'required|array|min:1',
        'ids.*' => 'integer|exists:alumnos,id',
        'grupo_id' => 'nullable|integer|exists:grupos,id',
        'estatus' => 'nullable|string|in:activo,inactivo',
    ]);

    DB::beginTransaction();
    try {
        $alumnos = Alumno::whereIn('id', $data['ids'])->get();
        foreach ($alumnos as $alumno) {
            if (isset($data['grupo_id'])) $alumno->grupo_id = $data['grupo_id'];
            if (isset($data['estatus'])) $alumno->estatus = $data['estatus'];
            $alumno->save();
        }
        DB::commit();
        return back()->with('success', 'Actualización en lote completada.');
    } catch (\Exception $e) {
        DB::rollBack();
        report($e);
        return back()->withErrors(['error' => 'Error al actualizar registros.']);
    }
}
```

## Blade: ejemplos de UI

En `resources/views/alumno/index.blade.php` (ejemplo simplificado):

```blade
<form id="bulkForm" method="POST" action="{{ route('alumnos.edicion-multiple') }}">
  @csrf
  <table>
    <thead>
      <tr>
        <th><input type="checkbox" id="selectAll"></th>
        <th>Nombre</th>
        <th>Grupo</th>
      </tr>
    </thead>
    <tbody>
      @foreach($alumnos as $alumno)
      <tr>
        <td><input type="checkbox" name="ids[]" value="{{ $alumno->id }}" class="selectRow"></td>
        <td>{{ $alumno->nombre }}</td>
        <td>{{ $alumno->grupo->nombre ?? '-' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div id="bulkActions" style="display:none;">
    <label>Grupo: <select name="grupo_id">...</select></label>
    <button type="submit">Aplicar</button>
  </div>
</form>

<script>
// JS mínimo: controlar select all y mostrar acciones
document.getElementById('selectAll').addEventListener('change', function(e) {
  const checked = e.target.checked;
  document.querySelectorAll('.selectRow').forEach(cb => cb.checked = checked);
  toggleBulkActions();
});
document.querySelectorAll('.selectRow').forEach(cb => cb.addEventListener('change', toggleBulkActions));
function toggleBulkActions(){
  const any = Array.from(document.querySelectorAll('.selectRow')).some(c => c.checked);
  document.getElementById('bulkActions').style.display = any ? 'block' : 'none';
}
</script>
```

## Validaciones y permisos

- Usar Policies para decidir si el usuario puede editar en lote (`updateMultiple`).
- Validar que los IDs existan y pertenezcan al alcance del usuario (por ejemplo: mismo departamento).
- Evitar la actualización de campos no permitidos por seguridad (usar fillable o asignación manual).

## Manejo de errores y reportes por ID

Si la operación puede fallar parcialmente (por reglas de negocio por alumno), hay dos opciones:

- Fallar todo y devolver error (transacción completa): más simple y consistente.
- Intentar por registro y devolver un resumen con éxitos y fallos por ID: más trabajo, pero más informativo.

Ejemplo de respuesta parcial (JSON):

{
  "updated": [1,2,5],
  "failed": {3: "Alumno en estado no editable", 4: "Fallo DB"}
}

## Casos límite y recomendaciones

- Si la lista es muy grande (miles de IDs), no traer todos los modelos a memoria: usar chunked updates o consultas SQL directas (`Model::whereIn(...)->update([...])`) considerando eventos/observers.
- Revisar events/observers que se disparan en `save()` si se actualiza con `update()` directo.
- Añadir un límite razonable al número de IDs admitidos por petición o paginar la selección.
- Considerar colas para operaciones largas y notificar al usuario cuando finalicen.

## Testing

- Tests unitarios del controlador: enviar `ids` válidos y campos y verificar que los modelos se actualizan.
- Test de permisos: usuario sin permiso recibe 403.
- Test de edge case: ids vacíos o ids que no existen.

Ejemplo básico de prueba (PHPUnit/Laravel):

```php
public function test_edicion_multiple_actualiza_usuarios()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $alumnos = Alumno::factory()->count(3)->create(['estatus' => 'activo']);

    $response = $this->post(route('alumnos.edicion-multiple'), [
        'ids' => $alumnos->pluck('id')->toArray(),
        'estatus' => 'inactivo'
    ]);

    $response->assertRedirect();
    foreach ($alumnos as $a) $this->assertDatabaseHas('alumnos', ['id'=>$a->id,'estatus'=>'inactivo']);
}
```

## Pasos siguientes / mejoras

- Implementar feedback por registro (lista de fallos) si el negocio lo requiere.
- Soportar acciones adicionales (asignar badge, cambiar grupo, borrar en lote) con confirmaciones y permisos separados.
- Añadir operativa por colas para cambios que disparan procesos costosos.

## Referencias en el proyecto

- Buscar en el proyecto ejemplos existentes en `resources/views/*/index.blade.php` y controladores relacionados para replicar estilos y componentes.
- Policies en `app/Policies` y Gates en `AuthServiceProvider`.

---

Documento creado para ayudar a mantener y probar la funcionalidad de edición múltiple.
