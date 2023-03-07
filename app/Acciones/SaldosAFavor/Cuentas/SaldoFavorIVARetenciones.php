<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use Carbon\Carbon;

class SaldoFavorIVARetenciones
{
    public static function ejecutar(Carbon $fecha): float
    {
        return ResolverSaldoAFavorAccion::ejecutar(
            'iva_retenciones',
            $fecha
        );
    }
}
