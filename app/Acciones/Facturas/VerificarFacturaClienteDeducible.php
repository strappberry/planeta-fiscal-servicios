<?php

namespace App\Acciones\Facturas;

use App\Models\FacturaCliente;

class VerificarFacturaClienteDeducible
{
    /**
     * Verificar si la factura tiene un numero de cuenta no deducible.
     *
     * Solo se verifiran facturas que no sean ventas.
     * Si lo tiene, se marca como no deducible.
     * Si no lo tiene, se marca como deducible.
     */
    public static function ejecutar(FacturaCliente $facturaCliente): void
    {
        if ($facturaCliente->tipo_factura === FacturaCliente::TIPO_VENTA) return;

        $cuentas = $facturaCliente->numerosCuentas()->get()->filter(function ($cuenta) {
            return $cuenta->deducible === false;
        });

        $facturaCliente->deducible = $cuentas->isEmpty();
        $facturaCliente->save();
    }
}
