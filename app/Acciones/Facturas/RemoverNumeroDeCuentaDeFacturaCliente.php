<?php

namespace App\Acciones\Facturas;

use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;

class RemoverNumeroDeCuentaDeFacturaCliente
{
    /**
     * Dada una factura de cliente se remueve el número de cuenta indicado.
     * Si el número de cuenta contiene exclusiones de otros numeros de cuenta se desvincularan las correcciones
     * hechas durante la vinculación.
     */
    public static function ejecutar(FacturaCliente $facturaCliente, NumeroCuenta $numeroCuenta)
    {
        $facturaCliente->numerosCuentas()->detach($numeroCuenta->id);

        if ($numeroCuenta->exclusiones) {
            foreach($numeroCuenta->exclusiones as $exclusion) {
                $numeroCuentaExcluido = NumeroCuenta::buscarExclusion($exclusion)->first();
                if ($numeroCuentaExcluido) {
                    $facturaCliente->numerosCuentas()->detach($numeroCuentaExcluido->id);
                }
            }
        }
    }
}
