<?php

namespace App\Acciones\Facturas;

use App\Models\Factura;

class ActualizarMontoComprobacion
{
    /**
     * Calcular el monto de comprobación de la factura
     *
     * Formula: Total - Subtotal + Tasa 0 + Exentos + Otros Impuestos - IVA + IEPS + RET IVA + RET ISR + Descuento
     *
     * @return void
     */
    public static function ejecutar(Factura $factura)
    {
        $monto = $factura->total
            - $factura->subtotal
            + $factura->tasa_cero
            + $factura->traslados_exentos
            + $factura->otros_impuestos
            + $factura->traslado_iva
            + $factura->traslado_ieps
            - $factura->retencion_iva
            + $factura->retencion_isr
            + $factura->descuento
            ;

        $factura->monto_comprobacion + $monto;
        $factura->save();
    }
}
