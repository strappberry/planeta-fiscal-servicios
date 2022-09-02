<?php

namespace App\Acciones\NumerosCuentas;

use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;
use Illuminate\Support\Collection;

class ResolverFormulaNumeroCuenta
{
    /**
     * Resolver la formula de una cuenta y generar el monto de cargo o abono apartir
     * de una collección de facturas de cliente
     *
     * @param NumeroCuenta $numeroCuenta
     * @param FacturaCliente[] $facturasClientes
     *
     * @return array
     */
    public static function ejecutar(NumeroCuenta $numeroCuenta, Collection $facturasClientes): array
    {
        $resultado = [
            'cargo' => 0,
            'abono' => 0,
        ];
        $monto = 0;

        foreach ($facturasClientes as $facturaCliente) {
            foreach($numeroCuenta->formula as $lineaFormula) {
                switch($lineaFormula['operacion']) {
                    case 'suma':
                        $monto += $facturaCliente->factura->{$lineaFormula['clave_monto']};
                        break;
                    case 'resta':
                        $monto -= $facturaCliente->factura->{$lineaFormula['clave_monto']};
                        break;
                }
            }
        }

        $resultado[$numeroCuenta->columna_calculo] = $monto;

        return $resultado;
    }
}
