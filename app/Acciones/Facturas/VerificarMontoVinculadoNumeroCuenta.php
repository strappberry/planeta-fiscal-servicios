<?php

namespace App\Acciones\Facturas;

use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;

class VerificarMontoVinculadoNumeroCuenta
{

    public static function ejecutar(
        FacturaCliente $facturaCliente,
        NumeroCuenta $numeroCuenta
    ): float {
        $cuentaVinculada = $facturaCliente
            ->numerosCuentas()
            ->where('numero_cuenta_id', $numeroCuenta->id)
            ->first();

        if (!$cuentaVinculada) {
            return 0;
        }

        return $cuentaVinculada->relacion_numero_cuenta->monto;
    }
}
