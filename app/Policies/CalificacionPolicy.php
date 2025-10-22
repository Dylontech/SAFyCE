<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Calificacion;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalificacionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('maestro');
    }

    public function view(User $user, Calificacion $calificacion)
    {
        return $user->hasRole('maestro') && $calificacion->maestro_id === $user->id;
    }

    public function create(User $user)
    {
        return $user->hasRole('maestro');
    }

    public function update(User $user, Calificacion $calificacion)
    {
        return $user->hasRole('maestro') && $calificacion->maestro_id === $user->id;
    }

    public function delete(User $user, Calificacion $calificacion)
    {
        return $user->hasRole('maestro') && $calificacion->maestro_id === $user->id;
    }
}
