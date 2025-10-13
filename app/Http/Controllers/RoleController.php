<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todos los roles (incluyendo tester)
        $roles = Role::with('users')->get();
        $users = User::with('roles')->paginate(10);

        // Buscar usuarios por nombre
        $search = $request->input('search');
        if ($search) {
            $users = User::where('name', 'like', '%' . $search . '%')
                        ->with('roles')
                        ->paginate(10);
        }

        return view('roles.index', compact('roles', 'users', 'search'));
    }

    public function assignRoles(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $userIds = $request->input('user_ids');
        $assignedCount = 0;
        $alreadyAssignedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($userIds as $userId) {
                $user = User::findOrFail($userId);
                
                // Verificar si el rol ya ha sido asignado
                if (!$user->hasRole($role->name)) {
                    $user->assignRole($role->name);
                    $assignedCount++;
                } else {
                    $alreadyAssignedCount++;
                }
            }

            DB::commit();

            // Mensajes de éxito según el resultado
            if ($assignedCount > 0 && $alreadyAssignedCount > 0) {
                $message = "Rol asignado exitosamente a {$assignedCount} usuario(s). {$alreadyAssignedCount} usuario(s) ya tenían este rol.";
            } elseif ($assignedCount > 0) {
                $message = "Rol asignado exitosamente a {$assignedCount} usuario(s).";
            } else {
                $message = "Todos los usuarios seleccionados ya tenían el rol asignado.";
            }

            return redirect()->route('roles.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('roles.index')
                ->with('error', 'Ocurrió un error al asignar los roles. Por favor, intenta nuevamente.');
        }
    }
}