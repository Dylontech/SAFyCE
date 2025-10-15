<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixControlEscolarPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:control-escolar-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agregar permisos faltantes al rol control_escolar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Reparando permisos del rol control_escolar...');

        // Crear el permiso si no existe
        $permission = Permission::firstOrCreate([
            'name' => 'eliminar reuniones',
            'guard_name' => 'web'
        ]);

        $this->info("✅ Permiso 'eliminar reuniones' creado/verificado");

        // Obtener el rol control_escolar
        $controlEscolarRole = Role::where('name', 'control_escolar')->first();

        if (!$controlEscolarRole) {
            $this->error("❌ No se encontró el rol 'control_escolar'");
            return 1;
        }

        // Agregar el permiso al rol si no lo tiene
        if (!$controlEscolarRole->hasPermissionTo('eliminar reuniones')) {
            $controlEscolarRole->givePermissionTo('eliminar reuniones');
            $this->info("✅ Permiso 'eliminar reuniones' agregado al rol 'control_escolar'");
        } else {
            $this->info("ℹ️ El rol 'control_escolar' ya tiene el permiso 'eliminar reuniones'");
        }

        // Mostrar todos los permisos del rol
        $this->info("\n📋 Permisos actuales del rol 'control_escolar':");
        foreach ($controlEscolarRole->permissions as $perm) {
            $this->line("  - {$perm->name}");
        }

        // Verificar usuarios con este rol
        $users = \App\Models\User::role('control_escolar')->get();
        $this->info("\n👥 Usuarios con rol 'control_escolar': {$users->count()}");
        foreach ($users as $user) {
            $this->line("  - {$user->name} ({$user->email})");
        }

        $this->info("\n✨ Reparación completada exitosamente!");
        return 0;
    }
}
