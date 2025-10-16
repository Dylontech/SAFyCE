<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $table = 'badges';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'tipo',
        'criterio',
        'activo'
    ];

    protected $casts = [
        'criterio' => 'array',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Tipos de badges
     */
    const TIPOS = [
        'academico' => 'Académico',
        'participacion' => 'Participación',
        'social' => 'Social',
        'creatividad' => 'Creatividad',
        'logro' => 'Logro'
    ];

    /**
     * Relación muchos a muchos con Perfiles
     */
    public function perfiles()
    {
        return $this->belongsToMany(Perfil::class, 'perfil_badges')
                    ->withPivot('obtenido_en')
                    ->withTimestamps();
    }

    /**
     * Verificar si un perfil cumple los criterios para obtener este badge
     */
    public function cumpleCriterios($perfil)
    {
        $criterio = $this->criterio;
        
        switch ($this->tipo) {
            case 'academico':
                return $this->verificarCriteriosAcademicos($perfil, $criterio);
            case 'participacion':
                return $this->verificarCriteriosParticipacion($perfil, $criterio);
            case 'social':
                return $this->verificarCriteriosSociales($perfil, $criterio);
            case 'creatividad':
                return $this->verificarCriteriosCreatividad($perfil, $criterio);
            default:
                return false;
        }
    }

    /**
     * Verificar criterios académicos
     */
    private function verificarCriteriosAcademicos($perfil, $criterio)
    {
        // Ejemplo: calificaciones promedio, tareas completadas
        if (isset($criterio['tareas_completadas'])) {
            $tareasCompletadas = $perfil->alumno->calificaciones()
                                              ->where('calificacion', '>=', 6)
                                              ->count();
            return $tareasCompletadas >= $criterio['tareas_completadas'];
        }
        
        return false;
    }

    /**
     * Verificar criterios de participación
     */
    private function verificarCriteriosParticipacion($perfil, $criterio)
    {
        if (isset($criterio['publicaciones_minimas'])) {
            $publicaciones = $perfil->publicaciones()->count();
            return $publicaciones >= $criterio['publicaciones_minimas'];
        }
        
        if (isset($criterio['comentarios_minimos'])) {
            $comentarios = Comentario::where('perfil_id', $perfil->id)->count();
            return $comentarios >= $criterio['comentarios_minimos'];
        }
        
        return false;
    }

    /**
     * Verificar criterios sociales
     */
    private function verificarCriteriosSociales($perfil, $criterio)
    {
        if (isset($criterio['reacciones_recibidas'])) {
            $reaccionesRecibidas = Reaccion::whereIn('publicacion_id', 
                $perfil->publicaciones()->pluck('id')
            )->count();
            return $reaccionesRecibidas >= $criterio['reacciones_recibidas'];
        }
        
        return false;
    }

    /**
     * Verificar criterios de creatividad
     */
    private function verificarCriteriosCreatividad($perfil, $criterio)
    {
        if (isset($criterio['publicaciones_con_imagen'])) {
            $publicacionesConImagen = $perfil->publicaciones()
                                           ->whereNotNull('archivos')
                                           ->count();
            return $publicacionesConImagen >= $criterio['publicaciones_con_imagen'];
        }
        
        return false;
    }

    /**
     * Asignar badge a un perfil
     */
    public function asignarAPerfil($perfilId)
    {
        if (!$this->perfiles()->where('perfil_id', $perfilId)->exists()) {
            $this->perfiles()->attach($perfilId, ['obtenido_en' => now()]);
            return true;
        }
        return false;
    }

    /**
     * Badges por defecto del sistema
     */
    public static function badgesDefecto()
    {
        return [
            [
                'nombre' => 'Primera Publicación',
                'descripcion' => 'Realizó su primera publicación',
                'icono' => '📝',
                'tipo' => 'participacion',
                'criterio' => ['publicaciones_minimas' => 1]
            ],
            [
                'nombre' => 'Conversador',
                'descripcion' => 'Ha comentado en 10 publicaciones',
                'icono' => '💬',
                'tipo' => 'social',
                'criterio' => ['comentarios_minimos' => 10]
            ],
            [
                'nombre' => 'Popular',
                'descripcion' => 'Ha recibido 50 reacciones en sus publicaciones',
                'icono' => '⭐',
                'tipo' => 'social',
                'criterio' => ['reacciones_recibidas' => 50]
            ],
            [
                'nombre' => 'Creativo',
                'descripcion' => 'Ha subido 5 publicaciones con imágenes',
                'icono' => '🎨',
                'tipo' => 'creatividad',
                'criterio' => ['publicaciones_con_imagen' => 5]
            ]
        ];
    }
}