<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialModeracion extends Model
{
    use HasFactory;

    protected $table = 'historial_moderacion';

    protected $fillable = [
        'moderador_id',
        'alumno_afectado',
        'contenido_afectado_type',
        'contenido_afectado_id',
        'accion',
        'motivo',
        'detalles',
        'datos_adicionales',
        'ip_moderador'
    ];

    protected $casts = [
        'datos_adicionales' => 'array',
    ];

    /**
     * Acciones disponibles
     */
    const ACCIONES = [
        'eliminar_publicacion' => 'Eliminar Publicación',
        'eliminar_comentario' => 'Eliminar Comentario',
        'bloquear_usuario' => 'Bloquear Usuario',
        'desbloquear_usuario' => 'Desbloquear Usuario',
        'advertencia' => 'Enviar Advertencia',
        'revision_reporte' => 'Revisar Reporte'
    ];

    /**
     * Relación con el moderador
     */
    public function moderador()
    {
        return $this->belongsTo(User::class, 'moderador_id');
    }

    /**
     * Relación con el alumno afectado
     */
    public function alumnoAfectado()
    {
        return $this->belongsTo(Alumno::class, 'alumno_afectado');
    }

    /**
     * Relación con el contenido afectado (simulando morphTo)
     */
    public function contenidoAfectado()
    {
        if (!$this->contenido_afectado_type || !$this->contenido_afectado_id) {
            return null;
        }
        
        $relatedClass = $this->contenido_afectado_type;
        return $relatedClass::find($this->contenido_afectado_id);
    }
    
    /**
     * Obtener el tipo de contenido afectado legible
     */
    public function getTipoContenidoAttribute()
    {
        if (!$this->contenido_afectado_type) {
            return 'No especificado';
        }
        
        $classMap = [
            'App\\Models\\Publicacion' => 'Publicación',
            'App\\Models\\Comentario' => 'Comentario',
            'App\\Models\\Perfil' => 'Perfil',
            'App\\Models\\UsuarioBloqueado' => 'Bloqueo'
        ];
        
        return $classMap[$this->contenido_afectado_type] ?? class_basename($this->contenido_afectado_type);
    }

    /**
     * Registrar acción de moderación
     */
    public static function registrarAccion($moderadorId, $accion, $motivo, $detalles = null, $alumnoAfectado = null, $contenidoAfectado = null, $datosAdicionales = null)
    {
        return static::create([
            'moderador_id' => $moderadorId,
            'alumno_afectado' => $alumnoAfectado,
            'contenido_afectado_type' => $contenidoAfectado ? get_class($contenidoAfectado) : null,
            'contenido_afectado_id' => $contenidoAfectado ? $contenidoAfectado->id : null,
            'accion' => $accion,
            'motivo' => $motivo,
            'detalles' => $detalles,
            'datos_adicionales' => $datosAdicionales,
            'ip_moderador' => request()->ip()
        ]);
    }

    /**
     * Scope por moderador
     */
    public function scopePorModerador($query, $moderadorId)
    {
        return $query->where('moderador_id', $moderadorId);
    }

    /**
     * Scope por acción
     */
    public function scopePorAccion($query, $accion)
    {
        return $query->where('accion', $accion);
    }

    /**
     * Scope por alumno afectado
     */
    public function scopePorAlumno($query, $alumnoId)
    {
        return $query->where('alumno_afectado', $alumnoId);
    }

    /**
     * Obtener estadísticas de moderación
     */
    public static function estadisticasPorModerador($moderadorId, $dias = 30)
    {
        return static::where('moderador_id', $moderadorId)
                     ->where('created_at', '>=', now()->subDays($dias))
                     ->selectRaw('accion, COUNT(*) as total')
                     ->groupBy('accion')
                     ->pluck('total', 'accion');
    }

    /**
     * Obtener resumen de actividad reciente
     */
    public static function actividadReciente($limite = 20)
    {
        return static::with(['moderador', 'alumnoAfectado'])
                     ->orderBy('created_at', 'desc')
                     ->limit($limite)
                     ->get();
    }
}
