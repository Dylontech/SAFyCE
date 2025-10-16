<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaccion extends Model
{
    use HasFactory;

    protected $table = 'reacciones';

    protected $fillable = [
        'publicacion_id',
        'perfil_id',
        'tipo'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Tipos de reacciones disponibles
     */
    const TIPOS = [
        'like' => '👍',
        'love' => '❤️',
        'wow' => '😮',
        'funny' => '😂',
        'sad' => '😢',
        'angry' => '😠'
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
     * Obtener emoji de la reacción
     */
    public function getEmojiAttribute()
    {
        return self::TIPOS[$this->tipo] ?? '👍';
    }

    /**
     * Validar que solo hay una reacción por perfil por publicación
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($reaccion) {
            // Eliminar reacción anterior del mismo perfil en la misma publicación
            self::where('publicacion_id', $reaccion->publicacion_id)
                ->where('perfil_id', $reaccion->perfil_id)
                ->delete();
        });

        static::created(function ($reaccion) {
            // Actualizar contador en la publicación
            $reaccion->publicacion->actualizarContadores();
        });

        static::deleted(function ($reaccion) {
            // Actualizar contador en la publicación
            $reaccion->publicacion->actualizarContadores();
        });
    }
}