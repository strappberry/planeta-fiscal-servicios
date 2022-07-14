<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompPagoDoctoRelacionado extends Model
{
    use HasFactory;

    protected $fillable = [
        'equivalencia',
        'folio',
        'serie',
        'uuid',
        'moneda',
        'objeto_impuesto',
        'numero_parcialidad',
        'importe_pagado',
        'importe_saldo_anterior',
        'importe_saldo_insoluto',
        'comp_pago_pago_id',
    ];

    /**
     * Pago al que pertenece el documento relacionado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pago()
    {
        return $this->belongsTo(CompPagoPago::class, 'comp_pago_pago_id');
    }

    /**
     * Traslados del documento relacionado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function traslados()
    {
        return $this->hasMany(CompPagoDoctoRelacionadoTraslado::class, 'doc_rel_id');
    }

    /**
     * Retenciones del documento relacionado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function retenciones()
    {
        return $this->hasMany(CompPagoDoctoRelacionadoRetencion::class, 'doc_rel_id');
    }
}
