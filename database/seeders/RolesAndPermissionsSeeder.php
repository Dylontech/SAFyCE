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
            'ver usuarios'
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
        if (!Role::where('name', 'admin')->exists()) {
            $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
            $adminRole->givePermissionTo([
                'crear alumnos', 'editar alumnos', 'eliminar alumnos', 'ver alumnos',
                'crear usuarios', 'editar usuarios', 'eliminar usuarios', 'ver usuarios'
            ]);
        }

        if (!Role::where('name', 'control_escolar')->exists()) {
            $controlEscolarRole = Role::create(['name' => 'control_escolar', 'guard_name' => 'web']);
            $controlEscolarRole->givePermissionTo([
                'crear alumnos', 'editar alumnos', 'ver alumnos', 'gestionar solicitud'
            ]);
        }

        if (!Role::where('name', 'servicio_financiero')->exists()) {
            $servicioFinancieroRole = Role::create(['name' => 'servicio_financiero', 'guard_name' => 'web']);
            $servicioFinancieroRole->givePermissionTo([
                'ver pagos', 'procesar pagos', 'generar liga de pago'
            ]);
        }

        // Crear Rol y Asignar Permisos para guardia alumno
        if (!Role::where('name', 'alumno')->exists()) {
            $alumnoRole = Role::create(['name' => 'alumno', 'guard_name' => 'alumno']);
            $alumnoRole->givePermissionTo($alumnoPermissions);
        }
    }
}
