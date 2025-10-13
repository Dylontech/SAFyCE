<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\User;

class SolicitudNotification extends Notification
{
    use Queueable;

    protected $alumno;

    public function __construct(User $alumno)
    {
        $this->alumno = $alumno;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'El alumno ' . $this->alumno->Nombre . ' ha realizado una solicitud.',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'El alumno ' . $this->alumno->Nombre . ' ha realizado una solicitud.',
        ]);
    }
}