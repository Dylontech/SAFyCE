<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComprobanteAsistencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comprobantes_asistencias';

    protected $fillable = [
        'asistencia_id',
        'alumno_id',
        'archivo',
        'estado',
        'comentario',
        'revisado_por',
    ];

    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class, 'asistencia_id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function revisor()
    {
        return $this->belongsTo(\App\Models\User::class, 'revisado_por');
    }
}
