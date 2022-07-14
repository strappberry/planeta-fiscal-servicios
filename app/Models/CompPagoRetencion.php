<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompPagoRetencion extends Model
{
    use HasFactory;

    protected $fillable = [
        'impuesto',
        'importe',
        'comp_pago_pago_id',
    ];

    protected $casts = [
        'importe' => 'float',
    ];

    /**
     * Pago al que pertenece la retención.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pago()
    {
        return $this->belongsTo(CompPagoPago::class);
    }
}
