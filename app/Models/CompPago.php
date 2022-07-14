<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Este modelo contiene los datos generales del complemento pago.
 * Pertenece a una factura.
 * Su relacion con pagos es una relacion 1:n. representa la información de cada pago individual.
 */
class CompPago extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'monto_total_pagos',
        'total_traslados_base_iva_16',
        'total_traslados_impuesto_iva_16',
        'total_retenciones_isr',
        'factura_id',
    ];

    protected $casts = [
        'monto_total_pagos'               => 'float',
        'total_traslados_base_iva_16'     => 'float',
        'total_traslados_impuesto_iva_16' => 'float',
        'total_retenciones_isr'           => 'float',
    ];

    /**
     * Factura a la que pertenece el complemento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    /**
     * Datos de los nodos pagos del complemento pago.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pagos()
    {
        return $this->hasMany(CompPagoPago::class);
    }

}
