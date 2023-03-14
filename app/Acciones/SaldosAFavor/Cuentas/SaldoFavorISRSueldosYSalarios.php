<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use App\Models\Cliente;
use Carbon\Carbon;

class SaldoFavorISRSueldosYSalarios
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): float
    {
        return ResolverSaldoAFavorAccion::ejecutar(
            $cliente,
            'impuestos_retenidos_isr_sueldos_salarios',
            $fecha
        );
    }
}
