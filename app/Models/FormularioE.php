<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioE extends Model
{
    use HasFactory;

    protected $table = 'formulario_e'; // Especificar el nombre correcto de la tabla

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'alumno_id',
        'nombre',
        'curp',
        'numero_control',
        'especialidad',
        'numero_lista',
        'grupo',
        'tipo_pago',
        'fecha_pago',
        'materias',
        'status',
        'comentario',
        'comentario_financiero',
        'liga_de_pago',
        'comprobante_alumno',
        'comprobante',
        'comprobante_oficial',
    ];

    /**
     * Relación con el modelo Alumno.
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    /**
     * Query scope para buscar por materias, tipo de pago y fecha de pago.
     */
    public function scopeFiltrarPorMateriaTipoYFecha($query, $materias, $tipoPago, $fechaPago)
    {
        return $query->where('materias', 'like', "%$materias%")
                     ->where('tipo_pago', $tipoPago)
                     ->where('fecha_pago', $fechaPago);
    }
}
