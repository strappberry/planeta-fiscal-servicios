<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use App\Models\SaldoFavorAcreditamiento;
use Carbon\Carbon;

class ResolverSaldoAFavorAccion
{
    public static function ejecutar(string $concepto, Carbon $fecha, int $decimales = 2): float
    {
        return SaldoFavorAcreditamiento::query()
            ->conceptoFecha($concepto, $fecha)
            ->sumarImporte($decimales);
    }
}
