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
        $user = Auth::user();

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
        foreach ($roles as $role) {
            if (in_array($role, $user->roles->pluck('name')->toArray())) {
                return true;
            }
        }
        return false;
    }

    protected function userHasAnyPermission($user, $permissions)
    {
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }
        return false;
    }
}
