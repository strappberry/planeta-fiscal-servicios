<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use App\Models\SaldoFavorAcreditamiento;
use Carbon\Carbon;

class ResolverSaldoAFavorAccion
{
    public static function ejecutar(string $concepto, Carbon $fecha)
    {
        $saldoAFavor = SaldoFavorAcreditamiento::porConceptoYFecha($concepto, $fecha);

        if (!$saldoAFavor) return 0;

        return round($saldoAFavor->importe, 2);
    }
}
