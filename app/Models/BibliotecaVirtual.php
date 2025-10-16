<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BibliotecaVirtual extends Model
{
    use HasFactory;

    protected $table = 'biblioteca_virtual';

    protected $fillable = [
        'nombre',
        'url',
        'descripcion',
        'icono',
        'categoria',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer'
    ];

    /**
     * Scope para obtener solo recursos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para ordenar por el campo orden
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Obtener las categorías disponibles
     */
    public static function getCategorias()
    {
        return [
            'bases_datos' => 'Bases de Datos',
            'revistas' => 'Revistas Científicas',
            'libros' => 'Libros Digitales',
            'medicina' => 'Medicina y Salud',
            'general' => 'Recursos Generales'
        ];
    }

    /**
     * Verificar si la URL es válida
     */
    public function getUrlValidaAttribute()
    {
        return filter_var($this->url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Obtener el icono predeterminado si no se especifica uno
     */
    public function getIconoDisplayAttribute()
    {
        return $this->icono ?: 'fas fa-external-link-alt';
    }
}
