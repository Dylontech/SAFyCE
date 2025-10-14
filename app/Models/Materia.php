<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Materia
 *
 * @property $id
 * @property $materia
 * @property $semestre
 * @property $especialidad
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Materia extends Model
{
    
    static $rules = [
		'materia' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['materia','semestre','especialidad'];

    /**
     * Relación con horarios
     */
    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    /**
     * Relación con tareas
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * Relación con calificaciones
     */
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }

    /**
     * Obtener maestros que imparten esta materia
     */
    public function maestros()
    {
        return User::whereHas('horarios', function ($query) {
            $query->where('materia_id', $this->id);
        })->distinct();
    }

    /**
     * Scope para filtrar por especialidad
     */
    public function scopeEspecialidad($query, $especialidad)
    {
        return $query->where('especialidad', $especialidad);
    }

    /**
     * Scope para filtrar por semestre
     */
    public function scopeSemestre($query, $semestre)
    {
        return $query->where('semestre', $semestre);
    }



}
