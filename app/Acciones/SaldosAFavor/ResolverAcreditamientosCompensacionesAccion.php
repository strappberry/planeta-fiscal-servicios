<?php

namespace App\Acciones\SaldosAFavor;

use App\Models\SaldoFavorAcreditamiento;
use Carbon\Carbon;

class ResolverAcreditamientosCompensacionesAccion
{
    public static function ejecutar(Carbon $fecha, string $clave, int $decimales = 2): float
    {
        $importe = SaldoFavorAcreditamiento::query()
            ->porConceptoYFecha($clave, $fecha)
            ->sum('importe');

        return round($importe, $decimales);
    }
}
