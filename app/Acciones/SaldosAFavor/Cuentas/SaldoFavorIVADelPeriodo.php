<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use Carbon\Carbon;

class SaldoFavorIVADelPeriodo
{
    public static function ejecutar(Carbon $fecha, int $decimales = 2): float
    {
        return ResolverSaldoAFavorAccion::ejecutar(
            'iva_del_periodo',
            $fecha,
            $decimales
        );
    }
}
