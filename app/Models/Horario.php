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
        'maestro_id' => 'required|exists:users,id',
        'sala_id' => 'required|exists:salas,id',
        'grupo' => 'required|string|max:10',
        'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        'semestre' => 'required|in:1,2,3,4,5,6,7,8',
        'periodo_escolar' => 'required|string|max:20',
        'estado' => 'in:activo,suspendido,finalizado',
    ];

    protected $perPage = 20;

    protected $fillable = [
        'materia_id',
        'maestro_id',
        'sala_id',
        'grupo',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'semestre',
        'periodo_escolar',
        'estado'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
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
        return $this->belongsTo(User::class, 'maestro_id');
    }

    /**
     * Relación con sala
     */
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    /**
     * Scope para horarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para filtrar por periodo escolar
     */
    public function scopePeriodo($query, $periodo)
    {
        return $query->where('periodo_escolar', $periodo);
    }

    /**
     * Scope para filtrar por grupo
     */
    public function scopeGrupo($query, $grupo)
    {
        return $query->where('grupo', $grupo);
    }

    /**
     * Scope para filtrar por día de la semana
     */
    public function scopeDia($query, $dia)
    {
        return $query->where('dia_semana', $dia);
    }
}
