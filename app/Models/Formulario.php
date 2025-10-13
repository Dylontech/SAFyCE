<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    use HasFactory;

    protected $table = 'formularios';

    protected $fillable = [
        'alumno_id',
        'nombre',
        'control',
        'especialidad',
        'grupo',
        'semestre',
        'fecha',
        'curp',
        'tipo_servicio',
        'status',
        'comentario',
        'comentario_financiero',   // Añadido
        'liga_de_pago',            // Añadido
        'comprobante_alumno',      // Añadido
        'comprobante',             // Añadido
        'comprobante_oficial',     // Añadido
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}
