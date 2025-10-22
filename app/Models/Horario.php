<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Horario
 *
 * @property $id
 * @property $materia_id
 * @property $maestro_id
 * @property $sala_id
 * @property $grupo
 * @property $dia_semana
 * @property $hora_inicio
 * @property $hora_fin
 * @property $semestre
 * @property $periodo_escolar
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Horario extends Model
{
    use HasFactory;

    static $rules = [
        'materia_id' => 'required|exists:materias,id',
        'user_id' => 'required|exists:users,id',
        'sala_id' => 'required|exists:salas,id',
        'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'observaciones' => 'nullable|string|max:1000',
    ];

    protected $perPage = 20;

    protected $fillable = [
        'materia_id',
        'user_id',
        'sala_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'fecha_inicio',
        'fecha_fin',
        'observaciones'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Relación con materia
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    /**
     * Relación con maestro (usuario)
     */
    public function maestro()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con sala
     */
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    /**
     * Scope para horarios activos (basado en fechas)
     */
    public function scopeActivos($query)
    {
        $hoy = now()->toDateString();
        return $query->where('fecha_inicio', '<=', $hoy)
                    ->where('fecha_fin', '>=', $hoy);
    }

    /**
     * Scope para filtrar por día de la semana
     */
    public function scopeDia($query, $dia)
    {
        return $query->where('dia_semana', $dia);
    }

    /**
     * Verifica si el horario está activo basado en las fechas
     */
    public function estaActivo()
    {
        $hoy = now()->toDateString();
        return $hoy >= $this->fecha_inicio && $hoy <= $this->fecha_fin;
    }

    /**
     * Verifica si hay conflicto con otro horario
     */
    public function tieneConflicto($diaSemana, $horaInicio, $horaFin, $fechaInicio, $fechaFin, $excludeId = null)
    {
        $query = static::where('user_id', $this->user_id)
                      ->where('dia_semana', $diaSemana)
                      ->where('fecha_inicio', '<=', $fechaFin)
                      ->where('fecha_fin', '>=', $fechaInicio)
                      ->where(function ($q) use ($horaInicio, $horaFin) {
                          $q->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                            ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                            ->orWhere(function ($subQ) use ($horaInicio, $horaFin) {
                                $subQ->where('hora_inicio', '<', $horaInicio)
                                     ->where('hora_fin', '>', $horaFin);
                            });
                      });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
