<?php

namespace App\Filter;

use Illuminate\Support\Facades\Auth;
use TakiElias\Tablar\Menu\Filters\FilterInterface;

class RolePermissionMenuFilter implements FilterInterface
{
    public function transform($item)
    {
        if (!$this->isVisible($item)) {
            return false;
        }

        return $item['header'] ?? $item;
    }

    protected function isVisible($item)
    {
        // Detectar usuario autenticado en cualquier guard
        $webUser = auth('web')->user();
        $alumnoUser = auth('alumno')->user();
        $user = $webUser ?? $alumnoUser;

        if (!$user) {
            return false;
        }

        // Verificar roles manualmente
        $roles = $item['roles'] ?? [];
        $permissions = $item['permissions'] ?? [];

        if (!empty($roles) && !$this->userHasAnyRole($user, $roles)) {
            return false;
        }

        if (!empty($permissions) && !$this->userHasAnyPermission($user, $permissions)) {
            return false;
        }

        return true;
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

    protected function userHasAnyPermission($user, $permissions)
    {
        if (!$user || !method_exists($user, 'can')) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }
        return false;
    }
}
