<?php

namespace App\Acciones\PolizasNominas\Cuentas;

use App\Models\Cliente;
use Carbon\Carbon;

class PolizaISRAsimiladosYSalarios
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): float
    {
        return ResolverPolizaNominaAccion::ejecutar(
            $cliente,
            'impuestos_retenidos_de_isr_por_asimilados_a_salarios',
            $fecha,
            'abono'
        );
    }
}
