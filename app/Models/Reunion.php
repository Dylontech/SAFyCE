<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reunion extends Model
{
    use HasFactory;

    protected $table = 'reuniones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha',
        'hora',
        'duracion',
        'plataforma',
        'enlace_reunion',
        'codigo_reunion',
        'sala_id',
        'user_id',
        'tipo',
        'estado',
        'max_participantes',
        'participantes_actuales'
    ];

    protected $casts = [
        'fecha' => 'date',
        'duracion' => 'integer',
        'max_participantes' => 'integer',
        'participantes_actuales' => 'integer'
    ];

    static $rules = [
        'titulo' => 'required|string|max:255',
        'fecha' => 'required|date|after_or_equal:today',
        'hora' => 'required',
        'duracion' => 'required|integer|min:15|max:480',
        'plataforma' => 'required|in:meet,zoom,teams,webex',
        'tipo' => 'required|in:clase,tutorial,reunion,examen,otro',
        'sala_id' => 'nullable|exists:salas,id'
    ];

    // Relaciones
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    public function scopeProximasActivas($query)
    {
        return $query->where('fecha', '>=', now()->toDateString())
                    ->where('estado', 'activa')
                    ->orderBy('fecha', 'asc')
                    ->orderBy('hora', 'asc');
    }

    public function scopeHoy($query)
    {
        return $query->where('fecha', now()->toDateString());
    }

    // Accessors
    public function getFechaHoraAttribute()
    {
        return Carbon::parse($this->fecha->format('Y-m-d') . ' ' . $this->hora);
    }

    public function getFechaHoraFormattedAttribute()
    {
        return Carbon::parse($this->fecha->format('Y-m-d') . ' ' . $this->hora)->format('d/m/Y H:i');
    }

    public function getFechaFormateadaAttribute()
    {
        return $this->fecha->format('d/m/Y');
    }

    public function getHoraFormateadaAttribute()
    {
        return Carbon::parse($this->hora)->format('H:i');
    }

    public function getDuracionFormateadaAttribute()
    {
        $horas = intval($this->duracion / 60);
        $minutos = $this->duracion % 60;
        
        if ($horas > 0) {
            return $horas . 'h ' . ($minutos > 0 ? $minutos . 'min' : '');
        }
        return $minutos . ' minutos';
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'activa' => 'success',
            'iniciada' => 'warning',
            'finalizada' => 'secondary',
            'cancelada' => 'danger',
            default => 'primary'
        };
    }

    public function getPlataformaIconoAttribute()
    {
        return match($this->plataforma) {
            'meet' => 'fab fa-google',
            'zoom' => 'fas fa-video',
            'teams' => 'fab fa-microsoft',
            'webex' => 'fas fa-video',
            default => 'fas fa-video'
        };
    }

    public function getPlataformaNombreAttribute()
    {
        return match($this->plataforma) {
            'meet' => 'Google Meet',
            'zoom' => 'Zoom',
            'teams' => 'Microsoft Teams',
            'webex' => 'Cisco Webex',
            default => ucfirst($this->plataforma)
        };
    }

    // Métodos auxiliares
    public function puedeUnirse()
    {
        if ($this->estado !== 'activa') {
            return false;
        }

        $fechaHoraReunion = Carbon::parse($this->fecha_hora);
        $ahora = now();
        
        // Permitir unirse 15 minutos antes y hasta 2 horas después
        return $ahora->between(
            $fechaHoraReunion->copy()->subMinutes(15),
            $fechaHoraReunion->copy()->addHours(2)
        );
    }

    public function estaEnCurso()
    {
        $fechaHoraReunion = Carbon::parse($this->fecha_hora);
        $ahora = now();
        
        return $ahora->between(
            $fechaHoraReunion,
            $fechaHoraReunion->copy()->addMinutes($this->duracion)
        );
    }

    public function haTerminado()
    {
        $fechaHoraReunion = Carbon::parse($this->fecha_hora);
        return now()->gt($fechaHoraReunion->copy()->addMinutes($this->duracion));
    }

    public function generarEnlaceReunion()
    {
        switch ($this->plataforma) {
            case 'meet':
                // Generar enlace de Google Meet
                $codigo = $this->codigo_reunion ?: 'meet-' . uniqid();
                return "https://meet.google.com/{$codigo}";
                
            case 'zoom':
                // Para Zoom necesitarías la API de Zoom
                return $this->enlace_reunion ?: "https://zoom.us/j/{$this->codigo_reunion}";
                
            case 'teams':
                // Para Teams necesitarías la API de Microsoft
                return $this->enlace_reunion ?: "https://teams.microsoft.com/l/meetup-join/{$this->codigo_reunion}";
                
            case 'webex':
                // Para Webex necesitarías la API de Cisco
                return $this->enlace_reunion ?: "https://webex.com/join/{$this->codigo_reunion}";
                
            default:
                return $this->enlace_reunion;
        }
    }
}
