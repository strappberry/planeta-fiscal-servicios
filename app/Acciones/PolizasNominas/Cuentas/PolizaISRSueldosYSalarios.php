<?php

namespace App\Acciones\PolizasNominas\Cuentas;

use Carbon\Carbon;

class PolizaISRSueldosYSalarios
{
    public static function ejecutar(Carbon $fecha): float
    {
        return ResolverPolizaNominaAccion::ejecutar(
            'impuestos_retenidos_de_isr_por_sueldos_y_salarios',
            $fecha,
            'abono'
        );
    }
}
