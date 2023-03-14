<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use App\Models\Cliente;
use Carbon\Carbon;

class SaldoFavorIVARetenciones
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): float
    {
        return ResolverSaldoAFavorAccion::ejecutar(
            $cliente,
            'iva_retenciones',
            $fecha
        );
    }
}
