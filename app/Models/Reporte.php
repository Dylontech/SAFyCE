<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'reportable_type',
        'reportable_id',
        'reportado_por',
        'tipo_reporte',
        'descripcion',
        'evidencias',
        'estado',
        'asignado_a',
        'respuesta_moderador',
        'accion_tomada',
        'fecha_revision'
    ];

    protected $casts = [
        'evidencias' => 'array',
        'fecha_revision' => 'datetime',
    ];

    /**
     * Tipos de reportes disponibles
     */
    const TIPOS_REPORTE = [
        'contenido_inapropiado' => 'Contenido Inapropiado',
        'acoso_bullying' => 'Acoso o Bullying',
        'spam' => 'Spam o Contenido Repetitivo',
        'informacion_falsa' => 'Información Falsa',
        'violencia' => 'Contenido Violento',
        'contenido_sexual' => 'Contenido Sexual Inapropiado',
        'drogas_alcohol' => 'Drogas o Alcohol',
        'otros' => 'Otros'
    ];

    /**
     * Acciones disponibles
     */
    const ACCIONES_DISPONIBLES = [
        'sin_accion' => 'No Requiere Acción',
        'advertencia' => 'Advertencia al Usuario',
        'eliminacion_contenido' => 'Eliminar Contenido',
        'bloqueo_temporal' => 'Bloqueo Temporal',
        'bloqueo_permanente' => 'Bloqueo Permanente'
    ];

    /**
     * Relación polimórfica con el contenido reportado
     */
    public function reportable()
    {
        return $this->morphTo();
    }

    /**
     * Relación con el alumno que reportó
     */
    public function reportadoPor()
    {
        return $this->belongsTo(Alumno::class, 'reportado_por');
    }

    /**
     * Relación con el moderador asignado
     */
    public function asignadoA()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    /**
     * Asignar reporte a un moderador
     */
    public function asignar($moderadorId)
    {
        $this->update([
            'asignado_a' => $moderadorId,
            'estado' => 'en_revision'
        ]);
    }

    /**
     * Resolver reporte
     */
    public function resolver($moderadorId, $accion, $respuesta)
    {
        $this->update([
            'estado' => 'resuelto',
            'asignado_a' => $moderadorId,
            'accion_tomada' => $accion,
            'respuesta_moderador' => $respuesta,
            'fecha_revision' => now()
        ]);

        // Registrar en historial
        HistorialModeracion::create([
            'moderador_id' => $moderadorId,
            'contenido_afectado_type' => static::class,
            'contenido_afectado_id' => $this->id,
            'accion' => 'revision_reporte',
            'motivo' => "Reporte resuelto con acción: {$accion}",
            'detalles' => $respuesta,
            'ip_moderador' => request()->ip()
        ]);
    }

    /**
     * Rechazar reporte
     */
    public function rechazar($moderadorId, $razon)
    {
        $this->update([
            'estado' => 'rechazado',
            'asignado_a' => $moderadorId,
            'respuesta_moderador' => $razon,
            'fecha_revision' => now()
        ]);
    }

    /**
     * Scope para reportes pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para reportes en revisión
     */
    public function scopeEnRevision($query)
    {
        return $query->where('estado', 'en_revision');
    }

    /**
     * Scope para reportes por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_reporte', $tipo);
    }

    /**
     * Obtener información del contenido reportado
     */
    public function getContenidoDetalleAttribute()
    {
        if (!$this->reportable) {
            return 'Contenido eliminado';
        }

        switch ($this->reportable_type) {
            case 'App\\Models\\Publicacion':
                return 'Publicación: ' . \Str::limit($this->reportable->contenido, 50);
            case 'App\\Models\\Comentario':
                return 'Comentario: ' . \Str::limit($this->reportable->contenido, 50);
            case 'App\\Models\\Perfil':
                return 'Perfil de: ' . $this->reportable->alumno->Nombre;
            default:
                return 'Contenido no identificado';
        }
    }

    /**
     * Obtener prioridad del reporte
     */
    public function getPrioridadAttribute()
    {
        $prioridadAlta = ['acoso_bullying', 'violencia', 'contenido_sexual'];
        
        if (in_array($this->tipo_reporte, $prioridadAlta)) {
            return 'alta';
        }
        
        return 'normal';
    }
}
