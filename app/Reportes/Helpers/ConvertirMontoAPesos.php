<?php
namespace App\Reportes\Helpers;

class ConvertirMontoAPesos {

    public static function convertir(
        $monto,
        $moneda,
        $tipoCambio = 1
    ) {
        if (strtolower($moneda) == 'mxn' || empty($moneda)) {
            return $monto;
        }

        return $monto * $tipoCambio;
    }

}
