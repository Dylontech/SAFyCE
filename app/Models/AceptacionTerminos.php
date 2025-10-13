<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AceptacionTerminos extends Model
{
    use HasFactory;

    protected $table = 'aceptaciones_terminos';

    protected $fillable = [
        'user_id',
        'version_terminos',
        'ip_address',
        'aceptado_en'
    ];

    protected $casts = [
        'aceptado_en' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}