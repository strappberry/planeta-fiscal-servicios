<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompPagoDoctoRelacionadoRetencion extends Model
{
    use HasFactory;

    protected $fillable = [
        'impuesto',
        'tipo_factor',
        'base',
        'importe',
        'tasa_cuota',
        'doc_rel_id',
    ];

    protected $casts = [
        'base'       => 'float',
        'importe'    => 'float',
        'tasa_cuota' => 'float',
    ];

    /**
     * Documento pagado al que pertenece la retención.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documentoRelacionado()
    {
        return $this->belongsTo(CompPagoDoctoRelacionado::class, 'doc_rel_id');
    }
}
