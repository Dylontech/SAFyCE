<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Calificacion
 *
 * @property $id
 * @property $alumno_id
 * @property $tarea_id
 * @property $materia_id
 * @property $maestro_id
 * @property $calificacion
 * @property $puntos_obtenidos
 * @property $puntos_totales
 * @property $tipo_evaluacion
 * @property $periodo_escolar
 * @property $parcial
 * @property $comentarios
 * @property $fecha_evaluacion
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Calificacion extends Model
{
    use HasFactory;

    static $rules = [
        'alumno_id' => 'required|exists:alumnos,id',
        'materia_id' => 'required|exists:materias,id',
        'maestro_id' => 'required|exists:users,id',
        'calificacion' => 'nullable|numeric|min:0|max:100',
        'tipo_evaluacion' => 'required|in:tarea,examen_parcial,examen_final,proyecto,participacion,practica',
        'periodo_escolar' => 'required|string|max:20',
        'parcial' => 'nullable|integer|min:1|max:3',
        'puntos_obtenidos' => 'nullable|integer|min:0',
        'puntos_totales' => 'nullable|integer|min:1',
    ];

    protected $perPage = 20;

    protected $fillable = [
        'alumno_id',
        'tarea_id',
        'materia_id',
        'maestro_id',
        'calificacion',
        'puntos_obtenidos',
        'puntos_totales',
        'tipo_evaluacion',
        'periodo_escolar',
        'parcial',
        'comentarios',
        'fecha_evaluacion',
        'archivo_entrega',
        'fecha_entrega_alumno',
        'estado_entrega'
    ];

    protected $casts = [
        'fecha_evaluacion' => 'datetime',
        'fecha_entrega_alumno' => 'datetime',
        'calificacion' => 'decimal:2',
    ];

    /**
     * Relación con alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    /**
     * Relación con tarea (opcional)
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

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
     * Scope para filtrar por período escolar
     */
    public function scopePeriodo($query, $periodo)
    {
        return $query->where('periodo_escolar', $periodo);
    }

    /**
     * Scope para filtrar por parcial
     */
    public function scopeParcial($query, $parcial)
    {
        return $query->where('parcial', $parcial);
    }

    /**
     * Scope para filtrar por tipo de evaluación
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_evaluacion', $tipo);
    }

    /**
     * Scope para calificaciones de un alumno específico
     */
    public function scopeAlumno($query, $alumno_id)
    {
        return $query->where('alumno_id', $alumno_id);
    }

    /**
     * Scope para calificaciones de una materia específica
     */
    public function scopeMateria($query, $materia_id)
    {
        return $query->where('materia_id', $materia_id);
    }

    /**
     * Obtener el porcentaje de la calificación
     */
    public function getPorcentajeAttribute()
    {
        if ($this->puntos_totales && $this->puntos_totales > 0) {
            return ($this->puntos_obtenidos / $this->puntos_totales) * 100;
        }
        
        return $this->calificacion;
    }

    /**
     * Determinar el estatus de la calificación (Aprobado/Reprobado)
     */
    public function getEstatusAttribute()
    {
        return $this->calificacion >= 70 ? 'Aprobado' : 'Reprobado';
    }

    /**
     * Obtener la calificación en letras
     */
    public function getCalificacionLetraAttribute()
    {
        if ($this->calificacion >= 90) return 'A';
        if ($this->calificacion >= 80) return 'B';
        if ($this->calificacion >= 70) return 'C';
        if ($this->calificacion >= 60) return 'D';
        return 'F';
    }
}
