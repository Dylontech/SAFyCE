<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear Permisos
        $permissions = [
            'crear alumnos',
            'editar alumnos',
            'eliminar alumnos',
            'ver alumnos',
            'crear solicitud',
            'gestionar solicitud',
            'generar liga de pago',
            'procesar pagos',
            'ver propias solicitudes',
            'ver pagos',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'ver usuarios',
            // Permisos para control escolar
            'gestionar horarios',
            'crear horarios',
            'editar horarios',
            'eliminar horarios',
            'ver horarios',
            'gestionar tareas',
            'crear tareas',
            'editar tareas',
            'eliminar tareas',
            'ver tareas',
            'calificar tareas',
            'gestionar calificaciones',
            'ver calificaciones',
            'editar calificaciones',
            'gestionar salas',
            'crear salas',
            'editar salas',
            'eliminar salas',
            'ver salas',
            'crear reuniones',
            'eliminar reuniones'
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        // Crear Permisos para guardia alumno
        $alumnoPermissions = [
            'ver alumnos',
            'crear solicitud',
            'ver propias solicitudes'
        ];

        foreach ($alumnoPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'alumno']);
        }

        // Crear Roles y Asignar Permisos
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'crear alumnos', 'editar alumnos', 'eliminar alumnos', 'ver alumnos',
            'crear usuarios', 'editar usuarios', 'eliminar usuarios', 'ver usuarios'
        ]);

        $controlEscolarRole = Role::firstOrCreate(['name' => 'control_escolar', 'guard_name' => 'web']);
        $controlEscolarRole->syncPermissions([
            'crear alumnos', 'editar alumnos', 'ver alumnos', 'gestionar solicitud',
            'gestionar salas', 'crear salas', 'editar salas', 'eliminar salas', 'ver salas',
            'gestionar horarios', 'crear horarios', 'editar horarios', 'eliminar horarios', 'ver horarios',
            'gestionar tareas', 'crear tareas', 'editar tareas', 'eliminar tareas', 'ver tareas',
            'gestionar calificaciones', 'ver calificaciones', 'editar calificaciones',
            'crear reuniones', 'eliminar reuniones'
        ]);

        $servicioFinancieroRole = Role::firstOrCreate(['name' => 'servicio_financiero', 'guard_name' => 'web']);
        $servicioFinancieroRole->syncPermissions([
            'ver pagos', 'procesar pagos', 'generar liga de pago'
        ]);

        $maestroRole = Role::firstOrCreate(['name' => 'maestro', 'guard_name' => 'web']);
        $maestroRole->syncPermissions([
            'ver horarios',
            'gestionar tareas', 'crear tareas', 'editar tareas', 'ver tareas', 'calificar tareas',
            'gestionar calificaciones', 'ver calificaciones', 'editar calificaciones',
            'ver salas', 'crear reuniones'
        ]);

        // Crear Rol alumno para guardia web (para portal estudiantil)
        $alumnoWebRole = Role::firstOrCreate(['name' => 'alumno', 'guard_name' => 'web']);
        $alumnoWebRole->syncPermissions([
            'ver horarios', 'ver salas', 'ver tareas'
        ]);

        // Crear Rol y Asignar Permisos para guardia alumno (sistema separado)
        $alumnoRole = Role::firstOrCreate(['name' => 'alumno', 'guard_name' => 'alumno']);
        $alumnoRole->syncPermissions($alumnoPermissions);
    }
}
