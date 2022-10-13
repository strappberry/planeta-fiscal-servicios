<?php

namespace App\Acciones\Facturas;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\FacturaCliente;

class ResolverTipoFacturaVentaOGasto
{
    /**
     * Dada una factura y un cliente se determinara si la factura es de venta o gasto.
     * Si el rfc del emisor es igual al rfc del cliente se considera una factura de venta.
     * Si el rfc del receptor es igual al rfc del cliente se considera una factura de gasto.
     *
     * TODO: Verificar si se necesitan más consideraciones para determinar el tipo de factura.
     *
     */
    public static function ejecutar(Factura $factura, Cliente $cliente): string
    {
        if ($cliente->rfc === $factura->rfc_emisor) {
            return FacturaCliente::TIPO_VENTA;
        }
        else if ($cliente->rfc === $factura->rfc_receptor) {
            return FacturaCliente::TIPO_GASTO;
        }

        return '';
    }
}
