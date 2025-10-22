<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedSocial extends Model
{
    use HasFactory;

    protected $table = 'redes_sociales';

    protected $fillable = [
        'perfil_id',
        'plataforma',
        'usuario',
        'url',
        'visible'
    ];

    protected $casts = [
        'visible' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Plataformas de redes sociales soportadas
     */
    const PLATAFORMAS = [
        'instagram' => [
            'nombre' => 'Instagram',
            'icono' => 'fab fa-instagram',
            'color' => '#E4405F',
            'url_base' => 'https://instagram.com/'
        ],
        'twitter' => [
            'nombre' => 'Twitter/X',
            'icono' => 'fab fa-twitter',
            'color' => '#1DA1F2',
            'url_base' => 'https://twitter.com/'
        ],
        'facebook' => [
            'nombre' => 'Facebook',
            'icono' => 'fab fa-facebook',
            'color' => '#4267B2',
            'url_base' => 'https://facebook.com/'
        ],
        'tiktok' => [
            'nombre' => 'TikTok',
            'icono' => 'fab fa-tiktok',
            'color' => '#000000',
            'url_base' => 'https://tiktok.com/@'
        ],
        'youtube' => [
            'nombre' => 'YouTube',
            'icono' => 'fab fa-youtube',
            'color' => '#FF0000',
            'url_base' => 'https://youtube.com/@'
        ],
        'linkedin' => [
            'nombre' => 'LinkedIn',
            'icono' => 'fab fa-linkedin',
            'color' => '#0077B5',
            'url_base' => 'https://linkedin.com/in/'
        ],
        'github' => [
            'nombre' => 'GitHub',
            'icono' => 'fab fa-github',
            'color' => '#333333',
            'url_base' => 'https://github.com/'
        ],
        'discord' => [
            'nombre' => 'Discord',
            'icono' => 'fab fa-discord',
            'color' => '#7289DA',
            'url_base' => ''
        ]
    ];

    /**
     * Relación con Perfil
     */
    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    /**
     * Obtener información de la plataforma
     */
    public function getInfoPlataformaAttribute()
    {
        return self::PLATAFORMAS[$this->plataforma] ?? null;
    }

    /**
     * Obtener URL completa de la red social
     */
    public function getUrlCompletaAttribute()
    {
        if ($this->url) {
            return $this->url;
        }

        $info = $this->info_plataforma;
        if ($info && $info['url_base'] && $this->usuario) {
            return $info['url_base'] . $this->usuario;
        }

        return null;
    }

    /**
     * Scope para redes sociales visibles
     */
    public function scopeVisibles($query)
    {
        return $query->where('visible', true);
    }

    /**
     * Validar usuario de la plataforma
     */
    public function validarUsuario()
    {
        switch ($this->plataforma) {
            case 'instagram':
            case 'twitter':
            case 'tiktok':
            case 'github':
                // Validar que no contenga caracteres especiales excepto _ y -
                return preg_match('/^[a-zA-Z0-9_.-]+$/', $this->usuario);
            
            case 'discord':
                // Validar formato usuario#0000
                return preg_match('/^.+#\d{4}$/', $this->usuario);
            
            case 'facebook':
            case 'youtube':
            case 'linkedin':
                // Más flexible para estos
                return !empty($this->usuario);
            
            default:
                return true;
        }
    }

    /**
     * Mutator para limpiar el usuario
     */
    public function setUsuarioAttribute($value)
    {
        // Remover @ si está presente al inicio
        $this->attributes['usuario'] = ltrim($value, '@');
    }

    /**
     * Obtener icono de la plataforma
     */
    public function getIconoAttribute()
    {
        $info = $this->info_plataforma;
        return $info ? $info['icono'] : 'fas fa-link';
    }

    /**
     * Obtener color de la plataforma
     */
    public function getColorAttribute()
    {
        $info = $this->info_plataforma;
        return $info ? $info['color'] : '#6c757d';
    }
}