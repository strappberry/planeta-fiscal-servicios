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
    const TIPO_FACTOR_EXENTO = 'Exento';
    const TIPO_FACTOR_TASA = 'Tasa';

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

    public function obtenerConceptos()
    {
        if (!isset($this->comprobante['Conceptos'])) {
            return [];
        }

        return $this->comprobante['Conceptos']['Concepto'];
    }

    public function obtenerDescripcionPrimerConcepto()
    {
        if (
            isset($this->comprobante['Conceptos']) &&
            isset($this->comprobante['Conceptos']['Concepto']) &&
            isset($this->comprobante['Conceptos']['Concepto'][0])
        ) {
            return $this->comprobante['Conceptos']['Concepto'][0]['Descripcion'];
        }

        return '';
    }

    public function obtenerDocumentosPagados()
    {
        $pagos = $this->obtenerPagosDelComplemento();
        $documentos = [];

        foreach ($pagos as $pago) {
            foreach ($pago['DoctoRelacionado'] as $documento) {
                $datosDocumento= [];

                foreach ($documento as $clave => $valor) {
                    $datosDocumento[$clave] = $valor;
                }

                array_push($documentos, $datosDocumento);
            }
        }

        return $documentos;
    }

    public function obtenerFormaDePago()
    {
        return $this->comprobante['FormaPago'];
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

    public function obtenerImpuestosTrasladosDeConceptos()
    {
        $conceptos = $this->obtenerConceptos();
        $impuestos = [];

        foreach ($conceptos as $concepto) {
            if (
                !isset($concepto['Impuestos']) &&
                !isset($concepto['Impuestos']['Traslados']) &&
                !isset($concepto['Impuestos']['Traslados']['Traslado'])
            ) {
                continue;
            }

            foreach ($concepto['Impuestos']['Traslados']['Traslado'] as $impuesto) {
                array_push($impuestos, $impuesto);
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

    public function obtenerImpuestosRetenidosDeConceptos()
    {
        $conceptos = $this->obtenerConceptos();
        $impuestos = [];

        foreach ($conceptos as $concepto) {
            if (
                !isset($concepto['Impuestos']) &&
                !isset($concepto['Impuestos']['Retenciones']) &&
                !isset($concepto['Impuestos']['Retenciones']['Retencion'])
            )  {
                continue;
            }

            foreach ($concepto['Impuestos']['Retenciones']['Retencion'] as $impuesto) {
                array_push($impuestos, $impuesto);
            }
        }

        return $impuestos;
    }

    public function obtenerMetodoDePago()
    {
        return $this->comprobante['MetodoPago'] ?? '';
    }

    public function obtenerPagosDelComplemento()
    {
        if (
            !isset($this->comprobante['Complemento']) &&
            !isset($this->comprobante['Complemento']['Pagos'])
        ) return [];

        return $this->comprobante['Complemento']['Pagos']['Pago'];
    }

    public function obtenerRegimenEmisor()
    {
        return $this->comprobante['Emisor']['RegimenFiscal'];
    }

    public function obtenerUsoCfdi()
    {
        return $this->comprobante['Receptor']['UsoCFDI'];
    }

    
}
