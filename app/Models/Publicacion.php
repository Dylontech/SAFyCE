<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    use HasFactory;

    protected $table = 'publicaciones';

    protected $fillable = [
        'perfil_id',
        'contenido',
        'tipo',
        'archivos',
        'etiquetas',
        'activa',
        'total_reacciones',
        'total_comentarios'
    ];

    protected $casts = [
        'archivos' => 'array',
        'etiquetas' => 'array',
        'activa' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Perfil
     */
    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    /**
     * Relación con reacciones
     */
    public function reacciones()
    {
        return $this->hasMany(Reaccion::class);
    }

    /**
     * Relación con comentarios
     */
    public function comentarios()
    {
        return $this->hasMany(Comentario::class)->orderBy('created_at', 'asc');
    }

    /**
     * Verificar si un perfil ya reaccionó a esta publicación
     */
    public function yaReacciono($perfilId)
    {
        return $this->reacciones()->where('perfil_id', $perfilId)->exists();
    }

    /**
     * Obtener la reacción de un perfil específico
     */
    public function reaccionDePerfil($perfilId)
    {
        return $this->reacciones()->where('perfil_id', $perfilId)->first();
    }

    /**
     * Contar reacciones por tipo
     */
    public function contarReaccionesPorTipo()
    {
        return $this->reacciones()
                    ->selectRaw('tipo, COUNT(*) as total')
                    ->groupBy('tipo')
                    ->pluck('total', 'tipo')
                    ->toArray();
    }

    /**
     * Scope para publicaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para publicaciones por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para publicaciones recientes
     */
    public function scopeRecientes($query, $dias = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }

    /**
     * Actualizar contadores
     */
    public function actualizarContadores()
    {
        $this->update([
            'total_reacciones' => $this->reacciones()->count(),
            'total_comentarios' => $this->comentarios()->count()
        ]);
    }
}