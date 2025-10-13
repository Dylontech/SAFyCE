<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class Carrusel
 *
 * @property $id
 * @property $Description
 * @property $link
 * @property $orden
 * @property $image
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Carrusel extends Model
{
    static $rules = [
        'Description' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240', // ← CAMBIADO A 10MB
    ];

    protected $perPage = 20;

    protected $fillable = ['Description', 'link', 'orden', 'image'];

    // Accessor to get the full URL of the image
    public function getUrlfotoAttribute()
    {
        return Storage::url($this->image);
    }
}