<?php

namespace App\Acciones\PolizasNominas\Cuentas;

use App\Models\Cliente;
use Carbon\Carbon;

class PolizaISRSueldosYSalarios
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): float
    {
        return ResolverPolizaNominaAccion::ejecutar(
            $cliente,
            'impuestos_retenidos_de_isr_por_sueldos_y_salarios',
            $fecha,
            'abono'
        );
    }
}
