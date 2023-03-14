<?php

namespace App\Acciones\PolizasNominas\Cuentas;

use App\Models\Cliente;
use App\Models\PolizaNomina;
use Carbon\Carbon;

class ResolverPolizaNominaAccion
{
    public static function ejecutar(Cliente $cliente, string $clave, Carbon $fecha, string $columna): float
    {
        $poliza = $cliente->polizasNominas()->porClaveYFecha($clave, $fecha);

        if (!$poliza) return 0;

        return round($poliza->{$columna}, 2);
    }
}
