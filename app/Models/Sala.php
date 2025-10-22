<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Sala
 *
 * @property $id
 * @property $nombre
 * @property $codigo
 * @property $capacidad
 * @property $tipo
 * @property $descripcion
 * @property $estado
 * @property $ubicacion
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Sala extends Model
{
    use HasFactory;

    static $rules = [
        'nombre' => 'required|string|max:255|unique:salas',
        'codigo' => 'required|string|max:50|unique:salas',
        'capacidad' => 'required|integer|min:1',
        'tipo' => 'required|in:aula,laboratorio,taller,auditorio,sala_de_juntas',
        'estado' => 'in:disponible,ocupada,mantenimiento,fuera_de_servicio',
    ];

    protected $perPage = 20;

    protected $fillable = [
        'nombre',
        'codigo',
        'capacidad',
        'tipo',
        'descripcion',
        'estado',
        'ubicacion'
    ];

    /**
     * Relación con horarios
     */
    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    /**
     * Relación con reuniones
     */
    public function reuniones()
    {
        return $this->hasMany(Reunion::class);
    }

    /**
     * Verificar si la sala está disponible en un horario específico
     */
    public function estaDisponible($dia_semana, $hora_inicio, $hora_fin, $excluir_horario_id = null)
    {
        $query = $this->horarios()
            ->where('dia_semana', $dia_semana)
            ->activos()
            ->where(function ($q) use ($hora_inicio, $hora_fin) {
                $q->whereBetween('hora_inicio', [$hora_inicio, $hora_fin])
                  ->orWhereBetween('hora_fin', [$hora_inicio, $hora_fin])
                  ->orWhere(function ($q2) use ($hora_inicio, $hora_fin) {
                      $q2->where('hora_inicio', '<', $hora_inicio)
                         ->where('hora_fin', '>', $hora_fin);
                  });
            });

        if ($excluir_horario_id) {
            $query->where('id', '!=', $excluir_horario_id);
        }

        return $query->count() === 0;
    }
}
