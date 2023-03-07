<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use Carbon\Carbon;

class SaldoFavorISRArrendamiento
{
    public static function ejecutar(Carbon $fecha): float
    {
        return ResolverSaldoAFavorAccion::ejecutar(
            'impuestos_retenidos_isr_arrendamiento',
            $fecha
        );
    }
}
