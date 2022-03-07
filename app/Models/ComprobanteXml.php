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

    public function obtenerImpuestosTraslados()
    {
        $impuestos = [
            'iva' => 0,
            'isr' => 0,
            'ieps' => 0,
        ];

        if (
            !isset($this->comprobante['Impuestos']['Traslados']) &&
            !isset($this->comprobante['Impuestos']['Traslados']['Traslado'])
        ) return $impuestos;

        foreach($this->comprobante['Impuestos']['Traslados']['Traslado'] as $impuesto) {
            if ($impuesto['Impuesto'] == self::IMPUESTO_IVA) {
                $impuestos['iva'] += $impuesto['Importe'];
            } elseif ($impuesto['Impuesto'] == self::IMPUESTO_ISR) {
                $impuestos['isr'] += $impuesto['Importe'];
            } elseif ($impuesto['Impuesto'] == self::IMPUESTO_IEPS) {
                $impuestos['ieps'] += $impuesto['Importe'];
            }
        }

        return $impuestos;
    }

    public function obtenerImpuestosRetenidos()
    {
        $impuestos = [
            'iva' => 0,
            'isr' => 0,
            'ieps' => 0,
        ];

        if (
            !isset($this->comprobante['Impuestos']['Retenciones']) &&
            !isset($this->comprobante['Impuestos']['Retenciones']['Retencion'])
        ) return $impuestos;

        foreach($this->comprobante['Impuestos']['Retenciones']['Retencion'] as $impuesto) {
            if ($impuesto['Impuesto'] == self::IMPUESTO_IVA) {
                $impuestos['iva'] += $impuesto['Importe'];
            } elseif ($impuesto['Impuesto'] == self::IMPUESTO_ISR) {
                $impuestos['isr'] += $impuesto['Importe'];
            } elseif ($impuesto['Impuesto'] == self::IMPUESTO_IEPS) {
                $impuestos['ieps'] += $impuesto['Importe'];
            }
        }

        return $impuestos;
    }
}
