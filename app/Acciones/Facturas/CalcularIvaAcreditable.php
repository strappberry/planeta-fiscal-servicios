<?php

namespace App\Acciones\Facturas;

use App\Models\EloquentCollections\FacturaClienteCollection;

class CalcularIvaAcreditable
{
    /**
     * Calcular el monto de IVA Acreditable
     *
     * 1. El iva acreditable se calcula de la sumatoria del IVA trasladado de los gastos
     * 2. Se verificará si se debe aplicar el iva acreditable a gastos
     *   - Si: El iva acreditable es el residuo de la resta de IVA acreditable - IVA acreditable a gastos.
     *   - No: El iva acreditable no se modifica.
     */
    public static function ejecutar(
        FacturaClienteCollection $ventas,
        FacturaClienteCollection $gastos,
        int $decimales = 2
    ): float {
        $ivaAcreditable = $gastos->sumatoriaTrasladosIva($decimales);

        if ($ventas->seDebeAplicarIvaAcreditableAGastos()) {
            $ivaAcreditableAGasto = $ivaAcreditable * $ventas->generarPorcentajeExentos(2);

            $ivaAcreditable -= $ivaAcreditableAGasto;
        }

        return round($ivaAcreditable, $decimales);
    }
}
