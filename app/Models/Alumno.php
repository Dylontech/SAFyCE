<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Alumno
 *
 * @property $id
 * @property $numero_control
 * @property $CURP
 * @property $especialidad
 * @property $semestre
 * @property $Grupo
 * @property $Nombre
 * @property $email
 * @property $estatus
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Alumno extends Model implements AuthenticatableContract
{
    use Authenticatable, HasRoles, HasFactory;

    /**
     * The guard used by this model for roles and permissions
     */
    protected $guard_name = 'alumno';

    static $rules = [
        'numero_control' => 'required',
        'CURP' => 'required',
        'especialidad' => 'required',
        'semestre' => 'required',
        'Grupo' => 'required',
        'Nombre' => 'required',
        'email' => 'required',
        'estatus' => 'required',
    ];

    protected $perPage = 20;

    protected $fillable = [
        'numero_control',
        'CURP',
        'especialidad',
        'semestre',  // Añadido antes de Grupo
        'Grupo',
        'Nombre',
        'email',
        'estatus',
        'remember_token'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($alumno) {
            $alumno->assignRole('alumno');
        });
    }

    /**
     * Get the user associated with the Alumno
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con calificaciones
     */
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }

    /**
     * Relación con la especialidad
     */
    public function especialidadModel()
    {
        return $this->belongsTo(Especialidade::class, 'especialidad', 'id');
    }

    /**
     * Obtener calificaciones por período escolar
     */
    public function calificacionesPorPeriodo($periodo)
    {
        return $this->calificaciones()->where('periodo_escolar', $periodo);
    }

    /**
     * Obtener promedio general del alumno
     */
    public function promedioGeneral($periodo = null)
    {
        $query = $this->calificaciones();
        
        if ($periodo) {
            $query->where('periodo_escolar', $periodo);
        }
        
        return $query->avg('calificacion') ?? 0;
    }

    /**
     * Obtener materias del alumno según su especialidad y semestre
     */
    public function materias()
    {
        return Materia::where('especialidad', $this->especialidad)
                     ->where('semestre', $this->semestre)
                     ->get();
    }

    /**
     * Relación con perfil
     */
    public function perfil()
    {
        return $this->hasOne(\App\Models\Perfil::class);
    }
}
