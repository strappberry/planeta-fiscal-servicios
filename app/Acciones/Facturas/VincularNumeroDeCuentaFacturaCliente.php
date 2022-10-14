<?php

namespace App\Acciones\Facturas;

use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;

class VincularNumeroDeCuentaFacturaCliente
{
    /**
     * Dada una factura de cliente se vinculará un número de cuenta con un monto.
     * Si el número de cuenta ya existe se actualizara el monto.
     * Si el número de cuenta contiene exclusiones de otros numeros de cuenta sincronizarán
     * con monto 0.
     */
    public static function ejecutar(
        FacturaCliente $facturaCliente,
        NumeroCuenta $numeroCuenta,
        $monto
    ) {
        // Vincular el número de cuenta con el monto
        $facturaCliente->numerosCuentas()->syncWithoutDetaching([
            $numeroCuenta->id => [
                'monto' => $monto,
            ],
        ]);

        // Verificar si el número de cuenta tiene exclusiones
        if ($numeroCuenta->exclusiones) {
            foreach($numeroCuenta->exclusiones as $exclusion) {
                $numeroCuentaExcluido = NumeroCuenta::buscarExclusion($exclusion)->first();
                if ($numeroCuentaExcluido) {
                    $facturaCliente->numerosCuentas()->syncWithoutDetaching([
                        $numeroCuentaExcluido->id => [
                            'monto' => 0,
                        ],
                    ]);
                }
            }
        }
    }
}
