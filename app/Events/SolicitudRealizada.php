<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SolicitudRealizada
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alumno;

    public function __construct(User $alumno)
    {
        $this->alumno = $alumno;
    }
    
}