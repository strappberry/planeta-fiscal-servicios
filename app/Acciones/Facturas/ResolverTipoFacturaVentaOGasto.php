<?php

namespace App\Acciones\Facturas;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\FacturaCliente;

class ResolverTipoFacturaVentaOGasto
{
    public static function ejecutar(Factura $factura, Cliente $cliente): string
    {
        if ($cliente->rfc === $factura->rfc_emisor) {
            return FacturaCliente::TIPO_VENTA;
        }
        else if ($cliente->rfc === $factura->rfc_receptor) {
            return FacturaCliente::TIPO_GASTO;
        }
        else {
            return '';
        }
    }
}
