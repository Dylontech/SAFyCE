<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Grupo
 *
 * @property $id
 * @property $semestre
 * @property $letra
 * @property $nombre_completo
 * @property $activo
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    /**
     * Reglas de validación
     */
    static $rules = [
        'semestre' => 'required|integer|min:1|max:12',
        'letra' => 'required|string|max:10',
        'nombre_completo' => 'required|string|max:50|unique:grupos,nombre_completo',
        'activo' => 'boolean'
    ];

    protected $perPage = 20;

    /**
     * Atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'semestre',
        'letra', 
        'nombre_completo',
        'activo'
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'activo' => 'boolean',
        'semestre' => 'integer'
    ];

    /**
     * Scopes
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorSemestre($query, $semestre)
    {
        return $query->where('semestre', $semestre);
    }

    /**
     * Relación con alumnos
     */
    public function alumnos()
    {
        return $this->hasMany(Alumno::class, 'Grupo', 'nombre_completo');
    }

    /**
     * Mutator para el nombre completo - se genera automáticamente
     */
    public function setNombreCompletoAttribute($value)
    {
        if (empty($value) && !empty($this->semestre) && !empty($this->letra)) {
            $this->attributes['nombre_completo'] = $this->semestre . strtolower($this->letra);
        } else {
            $this->attributes['nombre_completo'] = $value;
        }
    }

    /**
     * Obtener grupos por semestre
     */
    public static function getGruposPorSemestre($semestre = null)
    {
        $query = static::activos()->orderBy('semestre')->orderBy('letra');
        
        if ($semestre) {
            $query->porSemestre($semestre);
        }
        
        return $query->get();
    }
}
