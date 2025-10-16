<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfiles';

    protected $fillable = [
        'alumno_id',
        'icono_personalizado',
        'biografia',
        'estado',
        'configuracion_privacidad',
        'materias_favoritas',
        'puntos_actividad'
    ];

    protected $casts = [
        'configuracion_privacidad' => 'array',
        'materias_favoritas' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    /**
     * Relación con publicaciones
     */
    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class);
    }

    /**
     * Relación con redes sociales
     */
    public function redesSociales()
    {
        return $this->hasMany(RedSocial::class);
    }

    /**
     * Relación con badges
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'perfil_badges')
                    ->withPivot('obtenido_en')
                    ->withTimestamps();
    }

    /**
     * Obtener publicaciones del feed (propias y de compañeros)
     */
    public function feedPublicaciones()
    {
        // Obtener IDs de compañeros del mismo grupo y especialidad
        $companeros = Alumno::where('Grupo', $this->alumno->Grupo)
                           ->where('especialidad', $this->alumno->especialidad)
                           ->pluck('id');

        // Obtener perfiles de esos compañeros
        $perfilesCompaneros = Perfil::whereIn('alumno_id', $companeros)->pluck('id');

        return Publicacion::whereIn('perfil_id', $perfilesCompaneros)
                         ->where('activa', true)
                         ->orderBy('created_at', 'desc');
    }

    /**
     * Verificar si puede ver el perfil de otro usuario
     */
    public function puedeVerPerfil($otroPerfilId)
    {
        $otroPerfil = Perfil::find($otroPerfilId);
        
        if (!$otroPerfil) {
            return false;
        }

        $privacidad = $otroPerfil->configuracion_privacidad;

        // Si es el mismo usuario
        if ($this->id === $otroPerfilId) {
            return true;
        }

        // Verificar configuración de privacidad
        switch ($privacidad['visibilidad_perfil'] ?? 'compañeros') {
            case 'publico':
                return true;
            case 'compañeros':
                return $this->alumno->Grupo === $otroPerfil->alumno->Grupo &&
                       $this->alumno->especialidad === $otroPerfil->alumno->especialidad;
            case 'privado':
                return false;
            default:
                return false;
        }
    }

    /**
     * Configuración de privacidad por defecto
     */
    public static function configuracionPrivacidadDefecto()
    {
        return [
            'visibilidad_perfil' => 'compañeros',
            'visibilidad_publicaciones' => 'compañeros',
            'permitir_comentarios' => true,
            'notificaciones_activas' => true
        ];
    }
}