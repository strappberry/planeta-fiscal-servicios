<?php

namespace App\Acciones\SaldosAFavor;

use App\Models\Cliente;
use Carbon\Carbon;

class SaldosFavorOriginadosEnSubsidioAlEmpleoPorAplicar
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): float
    {
        $saldos = $cliente
            ->saldosAFavor()
            ->porOrigen('subsidio_al_empleo_por_aplicar')
            ->get();

        $importeSaldo = $saldos->reduce(function ($acumulado, $saldo) use ($fecha) {
            $importe = $saldo->acreditamientos()->mes($fecha)->sumarImporte();

            return $acumulado + $importe;
        }, 0);

        return $importeSaldo;
    }
}
