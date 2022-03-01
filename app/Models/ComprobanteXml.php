<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteXml extends Model
{
    use HasFactory;

    const IMPUESTO_ISR = '001';
    const IMPUESTO_IVA = '002';
    const IMPUESTO_IEPS = '003';

    const OTRO_PAGO_SUBSIDIO_AL_EMPLEO = '002';

    protected $fillable = [
        'comprobante',
        'factura_id',
    ];

    protected $casts = [
        'comprobante' => 'array',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
