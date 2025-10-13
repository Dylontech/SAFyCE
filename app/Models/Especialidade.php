<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Especialidade
 *
 * @property $id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Especialidade extends Model
{
    
    static $rules = [
        'nombre' => 'required|string|max:255|unique:especialidades,nombre',
        'descripcion' => 'nullable|string'
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion'
    ];



}
