<?php

namespace App\Acciones\PolizasNominas\Cuentas;

use Carbon\Carbon;

class PolizaISRAsimiladosYSalarios
{
    public static function ejecutar(Carbon $fecha): float
    {
        return ResolverPolizaNominaAccion::ejecutar(
            'impuestos_retenidos_de_isr_por_asimilados_a_salarios',
            $fecha,
            'abono'
        );
    }
}
