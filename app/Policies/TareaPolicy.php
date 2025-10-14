<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tarea;
use Illuminate\Auth\Access\HandlesAuthorization;

class TareaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('maestro');
    }

    public function view(User $user, Tarea $tarea)
    {
        return $user->hasRole('maestro') && $tarea->maestro_id === $user->id;
    }

    public function create(User $user)
    {
        return $user->hasRole('maestro');
    }

    public function update(User $user, Tarea $tarea)
    {
        return $user->hasRole('maestro') && $tarea->maestro_id === $user->id;
    }

    public function delete(User $user, Tarea $tarea)
    {
        return $user->hasRole('maestro') && $tarea->maestro_id === $user->id;
    }
}
