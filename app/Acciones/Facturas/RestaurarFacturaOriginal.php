<?php

namespace App\Acciones\Facturas;

use App\Models\FacturaCliente;
use App\Sat\Utilidades\FacturaArray;

class RestaurarFacturaOriginal
{
    public static function ejecutar(FacturaCliente $facturaCliente)
    {
        $comprobante = $facturaCliente->factura->comprobanteXml;
        if (!$comprobante) return $facturaCliente;
        $comprobante = $comprobante->comprobante;

        $datosFactura = FacturaArray::obtenerDatosParaFactura($comprobante);

        $facturaCliente->factura->total          = $datosFactura['total'];
        $facturaCliente->factura->subtotal       = $datosFactura['subtotal'];
        $facturaCliente->factura->descuento      = $datosFactura['descuento'];
        $facturaCliente->factura->retencion_isr  = $datosFactura['retencion_isr'];
        $facturaCliente->factura->retencion_iva  = $datosFactura['retencion_iva'];
        $facturaCliente->factura->retencion_ieps = $datosFactura['retencion_ieps'];
        $facturaCliente->factura->traslado_ieps  = $datosFactura['traslado_ieps'];
        $facturaCliente->factura->traslado_iva   = $datosFactura['traslado_iva'];
        $facturaCliente->factura->traslados_exentos = 0;
        $facturaCliente->factura->otros_impuestos   = 0;
        $facturaCliente->factura->tasa_cero         = 0;
        $facturaCliente->push();

        ActualizarMontoComprobacion::ejecutar($facturaCliente->factura);

        return $facturaCliente;
    }
}
