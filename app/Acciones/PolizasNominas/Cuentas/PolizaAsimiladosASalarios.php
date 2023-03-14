<?php

namespace App\Acciones\PolizasNominas\Cuentas;

use App\Models\Cliente;
use Carbon\Carbon;

class PolizaAsimiladosASalarios
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): float
    {
        return ResolverPolizaNominaAccion::ejecutar(
            $cliente,
            'asimilados_a_salarios',
            $fecha,
            'cargo'
        );
    }
}
