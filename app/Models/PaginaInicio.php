<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaginaInicio extends Model
{
    use HasFactory;

    protected $table = 'pagina_inicio';
    protected $guarded = [];

    protected $casts = [
        'miembros_equipo' => 'array',
        'novedades' => 'array',
    ];

    protected $fillable = [
        'titulo_principal',
        'descripcion',
        'imagen_principal',
        'titulo_equipo',
        'miembros_equipo',
        'titulo_novedades',
        'novedades',
        'titulo_contacto',
        'email_contacto',
        'telefono_contacto',
        'direccion_contacto',
        'facebook',
        'whatsapp', // ← CAMBIADO
        'instagram',
        'about',
    ];

    public static function getConfig()
    {
        return self::firstOrCreate([], [
            'titulo_principal' => 'Sistema de Gestión Escolar',
            'descripcion' => 'Plataforma integral para la administración de procesos académicos y administrativos',
            'titulo_equipo' => 'Nuestro Equipo de Desarrollo',
            'miembros_equipo' => json_encode([]),
            'titulo_novedades' => 'Últimas Novedades',
            'novedades' => json_encode([]),
            'titulo_contacto' => 'Contáctanos',
            'email_contacto' => 'contacto@example.com',
            'telefono_contacto' => '+1 234 567 8900',
            'direccion_contacto' => 'Dirección de ejemplo',
            'facebook' => null,
            'whatsapp' => null, // ← CAMBIADO
            'instagram' => null,
            'about' => 'example',
        ]);
    }
}
