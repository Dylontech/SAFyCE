<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'comentarios';

    protected $fillable = [
        'publicacion_id',
        'perfil_id',
        'contenido',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Publicación
     */
    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }

    /**
     * Relación con Perfil
     */
    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    /**
     * Scope para comentarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Actualizar contadores al crear/eliminar comentarios
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($comentario) {
            $comentario->publicacion->actualizarContadores();
        });

        static::deleted(function ($comentario) {
            $comentario->publicacion->actualizarContadores();
        });
    }

    /**
     * Verificar si el comentario puede ser editado
     */
    public function puedeSerEditado($perfilId)
    {
        return $this->perfil_id === $perfilId && 
               $this->created_at->diffInMinutes(now()) <= 15; // 15 minutos para editar
    }

    /**
     * Verificar si el comentario puede ser eliminado
     */
    public function puedeSerEliminado($perfilId)
    {
        return $this->perfil_id === $perfilId || 
               $this->publicacion->perfil_id === $perfilId; // El autor de la publicación también puede eliminar
    }
}