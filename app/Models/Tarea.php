<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Tarea
 *
 * @property $id
 * @property $titulo
 * @property $descripcion
 * @property $materia_id
 * @property $maestro_id
 * @property $grupo
 * @property $semestre
 * @property $fecha_asignacion
 * @property $fecha_entrega
 * @property $puntos_totales
 * @property $tipo
 * @property $estado
 * @property $instrucciones
 * @property $archivo_adjunto
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Tarea extends Model
{
    use HasFactory;

    static $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'materia_id' => 'required|exists:materias,id',
        'maestro_id' => 'required|exists:users,id',
        'grupo' => 'required|string|max:10',
        'semestre' => 'required|in:1,2,3,4,5,6,7,8',
        'fecha_entrega' => 'required|date|after:now',
        'puntos_totales' => 'required|integer|min:1|max:100',
        'tipo' => 'required|in:tarea,proyecto,examen,practica,ensayo',
        'estado' => 'in:activa,vencida,cancelada',
    ];

    protected $perPage = 20;

    protected $fillable = [
        'titulo',
        'descripcion',
        'materia_id',
        'maestro_id',
        'grupo',
        'semestre',
        'fecha_asignacion',
        'fecha_entrega',
        'puntos_totales',
        'tipo',
        'estado',
        'instrucciones',
        'archivo_adjunto'
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_entrega' => 'datetime',
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
     * Relación con calificaciones
     */
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }

    /**
     * Scope para tareas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope para tareas vencidas
     */
    public function scopeVencidas($query)
    {
        return $query->where('fecha_entrega', '<', now())
                    ->where('estado', 'activa');
    }

    /**
     * Scope para filtrar por grupo
     */
    public function scopeGrupo($query, $grupo)
    {
        return $query->where('grupo', $grupo);
    }

    /**
     * Scope para filtrar por semestre
     */
    public function scopeSemestre($query, $semestre)
    {
        return $query->where('semestre', $semestre);
    }

    /**
     * Verificar si la tarea está vencida
     */
    public function estaVencida()
    {
        return $this->fecha_entrega < now() && $this->estado === 'activa';
    }

    /**
     * Obtener días restantes para la entrega
     */
    public function diasRestantes()
    {
        if ($this->estaVencida()) {
            return 0;
        }
        
        return now()->diffInDays($this->fecha_entrega, false);
    }
}
