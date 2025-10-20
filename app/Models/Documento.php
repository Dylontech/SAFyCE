<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'alumno_id',
        'document_type',
        'file_path',
        'original_name',
        'status',
        'reviewer_comment',
        'reviewed_by',
        'reviewed_at'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static function requiredTypes()
    {
        return [
            'acta_nacimiento_original',
            'acta_nacimiento_copia',
            'certificado_secundaria_original',
            'certificado_secundaria_copia',
            'curp',
            'comprobante_domicilio',
            'fotografias',
            'boleta_calificaciones',
            'carta_buena_conducta',
            'certificado_medico',
            'identificacion_padres'
        ];
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
