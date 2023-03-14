<?php

namespace App\Acciones\SaldosAFavor\Cuentas;

use App\Models\Cliente;
use App\Models\SaldoFavorAcreditamiento;
use Carbon\Carbon;

class ResolverSaldoAFavorAccion
{
    public static function ejecutar(Cliente $cliente, string $concepto, Carbon $fecha, int $decimales = 2): float
    {
        return $cliente
            ->acreditamientosCompensaciones()
            ->conceptoFecha($concepto, $fecha)
            ->sumarImporte($decimales);
    }
}
