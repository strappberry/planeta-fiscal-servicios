<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use Carbon\Carbon;

class SaldoFavorISRServiciosProfesionales
{
    public static function ejecutar(Carbon $fecha): float
    {
        return ResolverSaldoAFavorAccion::ejecutar(
            'impuestos_retenidos_isr_servicios_profesionales',
            $fecha
        );
    }
}
