<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompPagoTraslado extends Model
{
    use HasFactory;

    protected $fillable = [
        'impuesto',
        'tipo_factor',
        'base',
        'importe',
        'tasa_cuota',
        'comp_pago_pago_id',
    ];

    protected $casts = [
        'base'       => 'float',
        'importe'    => 'float',
        'tasa_cuota' => 'float',
    ];

    /**
     * Pago al que pertenece el traslado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pago()
    {
        return $this->belongsTo(CompPagoPago::class);
    }
}
