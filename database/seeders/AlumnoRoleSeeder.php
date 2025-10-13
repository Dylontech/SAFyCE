<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AlumnoRoleSeeder extends Seeder
{
    public function run()
    {
        // Crear Permisos para guardia alumno
        $permissions = [
            'ver alumnos',
            'crear solicitud',
            'ver propias solicitudes'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'alumno']);
        }

        // Crear Rol y Asignar Permisos para guardia alumno
        if (!Role::where('name', 'alumno')->exists()) {
            $alumnoRole = Role::create(['name' => 'alumno', 'guard_name' => 'alumno']);
            $alumnoRole->givePermissionTo($permissions);
        }
    }
}
