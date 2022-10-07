<?php

namespace App\Acciones\NumerosCuentas;

use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;

class ResolverFormulaNumeroCuentaFactura
{
    /**
     * Resolver la formula de una cuenta y generar el monto de cargo o abono apartir
     * de una factura de cliente
     *
     * @param NumeroCuenta $numeroCuenta
     * @param FacturaCliente $facturaCliente
     *
     * @return array
     */
    public static function ejecutar(NumeroCuenta $numeroCuenta, FacturaCliente $facturaCliente): float
    {
        $monto = 0;

        foreach ($numeroCuenta->formula as $lineaFormula) {
            switch ($lineaFormula['operacion']) {
                case 'suma':
                    $monto += $facturaCliente->factura->{$lineaFormula['clave_monto']};
                    break;
                case 'resta':
                    $monto -= $facturaCliente->factura->{$lineaFormula['clave_monto']};
                    break;

                default:
                    break;
            }
        }

        return $monto;
    }
}
