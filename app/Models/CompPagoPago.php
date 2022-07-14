<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompPagoPago extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_pago',
        'forma_pago',
        'moneda',
        'monto',
        'tipo_cambio',
        'comp_pago_id',
    ];

    protected $casts = [
        'fecha_pago'  => 'date',
        'monto'       => 'float',
        'tipo_cambio' => 'float',
    ];

    /**
     * Complemento de pagos al que pertenece el pago.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function complementoPago()
    {
        return $this->belongsTo(CompPago::class);
    }

    /**
     * Documentos relacionados al pago.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documentosRelacionados()
    {
        return $this->hasMany(CompPagoDoctoRelacionado::class, 'comp_pago_pago_id');
    }

    /**
     * Percepciones del pago.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function traslados()
    {
        return $this->hasMany(CompPagoTraslado::class);
    }

    /**
     * Retenciones del pago
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function retenciones()
    {
        return $this->hasMany(CompPagoRetencion::class);
    }
}
