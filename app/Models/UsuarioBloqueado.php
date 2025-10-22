<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UsuarioBloqueado extends Model
{
    use HasFactory;

    protected $table = 'usuarios_bloqueados';

    protected $fillable = [
        'alumno_id',
        'bloqueado_por',
        'tipo_bloqueo',
        'motivo',
        'detalles',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'fecha_levantamiento' => 'datetime',
    ];

    /**
     * Relación con el alumno bloqueado
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    /**
     * Relación con el usuario que bloqueó
     */
    public function bloqueadoPor()
    {
        return $this->belongsTo(User::class, 'bloqueado_por');
    }

    /**
     * Relación con el usuario que levantó el bloqueo
     */
    public function levantadoPor()
    {
        return $this->belongsTo(User::class, 'levantado_por');
    }

    /**
     * Verificar si el bloqueo está activo
     */
    public function estaActivo()
    {
        if (!$this->activo) {
            return false;
        }

        // Si es temporal, verificar si ya expiró
        if ($this->tipo_bloqueo === 'temporal' && $this->fecha_fin) {
            if (now()->greaterThan($this->fecha_fin)) {
                $this->update(['activo' => false]);
                return false;
            }
        }

        return true;
    }

    /**
     * Levantar el bloqueo
     */
    public function levantar($moderadorId, $razon = null)
    {
        $this->update([
            'activo' => false,
            'fecha_levantamiento' => now(),
            'levantado_por' => $moderadorId,
            'razon_levantamiento' => $razon
        ]);

        // Registrar en el historial
        HistorialModeracion::create([
            'moderador_id' => $moderadorId,
            'alumno_afectado' => $this->alumno_id,
            'contenido_afectado_type' => 'App\\Models\\UsuarioBloqueado',
            'contenido_afectado_id' => $this->id,
            'accion' => 'desbloquear_usuario',
            'motivo' => $razon ?: 'Bloqueo levantado',
            'ip_moderador' => request()->ip()
        ]);
    }

    /**
     * Obtener días restantes de bloqueo
     */
    public function diasRestantes()
    {
        if ($this->tipo_bloqueo === 'permanente' || !$this->fecha_fin) {
            return null; // Permanente
        }

        $dias = now()->diffInDays($this->fecha_fin, false);
        return max(0, $dias);
    }

    /**
     * Scope para bloqueos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true)
                    ->where(function($q) {
                        $q->where('tipo_bloqueo', 'permanente')
                          ->orWhere('fecha_fin', '>', now());
                    });
    }

    /**
     * Verificar si un alumno está bloqueado
     */
    public static function estaAlumnoBloqueado($alumnoId)
    {
        return static::where('alumno_id', $alumnoId)
                     ->activos()
                     ->exists();
    }
}
