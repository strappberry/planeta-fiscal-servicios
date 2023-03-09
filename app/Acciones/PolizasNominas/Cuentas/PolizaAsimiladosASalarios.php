<?php

namespace App\Acciones\PolizasNominas\Cuentas;

use Carbon\Carbon;

class PolizaAsimiladosASalarios
{
    public static function ejecutar(Carbon $fecha): float
    {
        return ResolverPolizaNominaAccion::ejecutar(
            'asimilados_a_salarios',
            $fecha,
            'cargo'
        );
    }
}
