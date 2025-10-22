<?php

namespace App\Policies;

use App\Models\BibliotecaVirtual;
use App\Models\User;
use App\Models\Alumno;

class BibliotecaVirtualPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        // Permitir solo a administradores y coordinadores
        if ($user instanceof User) {
            return $user->hasRole(['admin', 'control_escolar']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, BibliotecaVirtual $bibliotecaVirtual): bool
    {
        // Todos los usuarios autenticados pueden ver recursos activos
        if ($user instanceof User) {
            return $user->hasRole(['admin', 'control_escolar', 'maestro']);
        }
        
        if ($user instanceof Alumno) {
            return true; // Los alumnos pueden ver la biblioteca virtual
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        if ($user instanceof User) {
            return $user->hasRole(['admin', 'control_escolar']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, BibliotecaVirtual $bibliotecaVirtual): bool
    {
        if ($user instanceof User) {
            return $user->hasRole(['admin', 'control_escolar']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, BibliotecaVirtual $bibliotecaVirtual): bool
    {
        if ($user instanceof User) {
            return $user->hasRole(['admin', 'control_escolar']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, BibliotecaVirtual $bibliotecaVirtual): bool
    {
        if ($user instanceof User) {
            return $user->hasRole(['admin']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, BibliotecaVirtual $bibliotecaVirtual): bool
    {
        if ($user instanceof User) {
            return $user->hasRole(['admin']);
        }
        
        return false;
    }
}
