<?php

namespace App\Acciones\Facturas;

use App\Models\EloquentCollections\FacturaClienteCollection;

class CalcularIvaAcreditableAGasto
{
    /**
     * Calcular el monto del IVA Acreditable a gastos
     *
     * 1. Se verifica si se debe aplicar el iva acreditable a gastos
     *   - Si: El iva acreditable a gastos es el resultado de la multiplicación de
     *       IVA acreditable * Porcentaje de exentos
     */
    public static function ejecutar(
        FacturaClienteCollection $ventas,
        FacturaClienteCollection $gastos,
        int $decimales = 2
    ): float {
        $ivaAcreditableAGasto = 0;

        if ($ventas->seDebeAplicarIvaAcreditableAGastos()) {
            $ivaAcreditable = $gastos->sumatoriaTrasladosIva($decimales);
            $ivaAcreditableAGasto = $ivaAcreditable * $ventas->generarPorcentajeExentos(2);
        }

        return round($ivaAcreditableAGasto, $decimales);
    }
}
