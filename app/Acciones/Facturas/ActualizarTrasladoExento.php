<?php

namespace App\Acciones\Facturas;

use App\Models\Factura;

class ActualizarTrasladoExento
{
    /**
     * - Verificar si la factura tiene elemento comprobante xml
     * - Calcular monto de los traslados exentos, se tienen que calcular desde
     * los nodos de impuestos del concepto ya que estos no se reflejan en los
     * nodos de impuestos de la factura.
     *
     * @return void
     */
    public static function ejecutar(Factura $factura)
    {
        $comprobanteXml = $factura->comprobanteXml;
        if (!$comprobanteXml) {
            return;
        }

        $conceptos = $comprobanteXml->comprobante['Conceptos']['Concepto'];
        $montoTrasladosExentos = 0;

        foreach ($conceptos as $concepto) {
            if (
                isset($concepto['Impuestos']) &&
                isset($concepto['Impuestos']['Traslados']) &&
                isset($concepto['Impuestos']['Traslados']['Traslado'])
            ) {
                $traslados = $concepto['Impuestos']['Traslados']['Traslado'];
                foreach ($traslados as $traslado) {
                    $montoTrasladosExentos = $traslado['Base'];
                }
            }
        }

        $factura->traslados_exentos = $montoTrasladosExentos;
        $factura->save();
    }
}
