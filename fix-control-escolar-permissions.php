<?php

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Script temporal para agregar permisos a control_escolar

// Crear el permiso si no existe
$permission = Permission::firstOrCreate([
    'name' => 'eliminar reuniones',
    'guard_name' => 'web'
]);

// Obtener el rol control_escolar
$controlEscolarRole = Role::where('name', 'control_escolar')->first();

if ($controlEscolarRole) {
    // Agregar el permiso al rol si no lo tiene
    if (!$controlEscolarRole->hasPermissionTo('eliminar reuniones')) {
        $controlEscolarRole->givePermissionTo('eliminar reuniones');
        echo "✅ Permiso 'eliminar reuniones' agregado al rol 'control_escolar'\n";
    } else {
        echo "ℹ️ El rol 'control_escolar' ya tiene el permiso 'eliminar reuniones'\n";
    }
    
    // Mostrar todos los permisos del rol
    echo "\n📋 Permisos actuales del rol 'control_escolar':\n";
    foreach ($controlEscolarRole->permissions as $perm) {
        echo "  - {$perm->name}\n";
    }
} else {
    echo "❌ No se encontró el rol 'control_escolar'\n";
}

echo "\n✨ Script completado.\n";
