<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class VerifyStudentSetup extends Command
{
    protected $signature = 'verify:student-setup';
    protected $description = 'Verify student portal setup';

    public function handle()
    {
        $this->info('=== Verificación del Portal Estudiantil ===');
        
        // Verificar roles
        $this->info('1. Verificando roles...');
        $webRoles = Role::where('guard_name', 'web')->pluck('name')->toArray();
        $alumnoRoles = Role::where('guard_name', 'alumno')->pluck('name')->toArray();
        
        $this->info('Roles en guard web: ' . implode(', ', $webRoles));
        $this->info('Roles en guard alumno: ' . implode(', ', $alumnoRoles));
        
        // Verificar si existe rol alumno en guard web
        $alumnoWebRole = Role::where('name', 'alumno')->where('guard_name', 'web')->first();
        if ($alumnoWebRole) {
            $this->info('✓ Rol alumno existe en guard web');
            $permissions = $alumnoWebRole->permissions->pluck('name')->toArray();
            $this->info('  Permisos: ' . implode(', ', $permissions));
        } else {
            $this->error('✗ Rol alumno NO existe en guard web');
        }
        
        // Verificar usuarios con rol alumno
        $this->info('2. Verificando usuarios con rol alumno...');
        $alumnosCount = User::role('alumno')->count();
        $this->info("Usuarios con rol alumno en guard web: {$alumnosCount}");
        
        // Verificar permisos específicos
        $this->info('3. Verificando permisos específicos...');
        $requiredPermissions = ['ver horarios', 'ver salas'];
        foreach ($requiredPermissions as $permission) {
            $exists = Permission::where('name', $permission)->where('guard_name', 'web')->exists();
            if ($exists) {
                $this->info("✓ Permiso '{$permission}' existe");
            } else {
                $this->error("✗ Permiso '{$permission}' NO existe");
            }
        }
        
        $this->info('=== Verificación completada ===');
    }
}
