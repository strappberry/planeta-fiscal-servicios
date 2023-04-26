<?php

namespace App\Acciones\Facturas;

use App\Models\FacturaCliente;

class ConderarParaDeclaracionMasivamente
{
    public static function ejecutar($ids, bool $considerar)
    {
        $facturas = FacturaCliente::whereIn('id', $ids)->get();

        foreach ($facturas as $factura) {
            if ($considerar && $factura->poliza_valida) {
                $factura->considerado = true;
            } else {
                $factura->considerado = false;
            }
            $factura->save();
        }
    }
}
