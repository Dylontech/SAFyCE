<?php

namespace App\Listeners;

use App\Events\SolicitudRealizada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Events\Notifications\SolicitudNotification;

class EnviarNotificacionDepartamento
{
    public function handle(SolicitudRealizada $event)
    {
        $departamento = User::role('departamento')->get();
        foreach ($departamento as $user) {
            $user->notify(new SolicitudNotification($event->alumno));
        }
    }
}