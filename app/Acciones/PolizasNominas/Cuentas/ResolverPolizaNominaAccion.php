<?php

namespace App\Acciones\PolizasNominas\Cuentas;

use App\Models\PolizaNomina;
use Carbon\Carbon;

class ResolverPolizaNominaAccion
{
    public static function ejecutar(string $clave, Carbon $fecha, string $columna): float
    {
        $poliza = PolizaNomina::porClaveYFecha($clave, $fecha);

        if (!$poliza) return 0;

        return round($poliza->{$columna}, 2);
    }
}
