<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asistencias';

    protected $fillable = [
        'alumno_id',
        'grupo_id',
        'maestro_id',
        'nivel',
        'fecha',
        'hora_entrada',
        'codigo_barra',
        'estado',
        'motivo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime:H:i:s',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function maestro()
    {
        return $this->belongsTo(\App\Models\User::class, 'maestro_id');
    }

    public function comprobantes()
    {
        return $this->hasMany(ComprobanteAsistencia::class, 'asistencia_id');
    }
}
