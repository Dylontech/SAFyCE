<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TesterRoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear o actualizar rol Tester
        $testerRole = Role::firstOrCreate(
            ['name' => 'tester', 'guard_name' => 'web'],
            ['name' => 'tester', 'guard_name' => 'web']
        );

        // Obtener todos los permisos del guard web
        $allPermissions = Permission::where('guard_name', 'web')->pluck('name')->toArray();
        
        // Sincronizar permisos (agrega nuevos, elimina removidos)
        $testerRole->syncPermissions($allPermissions);

        $this->command->info("Rol Tester configurado con " . count($allPermissions) . " permisos.");
    }
}