<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the alumno associated with the User
     */
    public function alumno()
    {
        return $this->hasOne(Alumno::class);
    }

    /**
     * Relación con horarios (para maestros)
     */
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'maestro_id');
    }

    /**
     * Relación con tareas asignadas (para maestros)
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'maestro_id');
    }

    /**
     * Relación con calificaciones otorgadas (para maestros)
     */
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'maestro_id');
    }

    /**
     * Verificar si el usuario es maestro
     */
    public function esMaestro()
    {
        return $this->hasRole('maestro');
    }

    /**
     * Verificar si el usuario es alumno
     */
    public function esAlumno()
    {
        return $this->hasRole('alumno');
    }

    /**
     * Verificar si el usuario es admin
     */
    public function esAdmin()
    {
        return $this->hasRole('admin');
    }
}
