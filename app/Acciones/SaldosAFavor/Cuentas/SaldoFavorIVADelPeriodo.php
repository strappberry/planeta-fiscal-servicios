<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use App\Models\Cliente;
use Carbon\Carbon;

class SaldoFavorIVADelPeriodo
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha, int $decimales = 2): float
    {
        return ResolverSaldoAFavorAccion::ejecutar(
            $cliente,
            'iva_del_periodo',
            $fecha,
            $decimales
        );
    }
}
