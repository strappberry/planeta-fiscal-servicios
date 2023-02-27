<?php

namespace App\Acciones\SaldosAFavor;

use App\Models\SaldoAFavor;

class AgregarAcreditamientoSaldoAFavor
{
    public static function ejecutar(SaldoAFavor $saldoAFavor, array $datos)
    {
        $saldoAFavor->acreditamientos()->create([
            'remanente_historico' => $saldoAFavor->remanente,
            'importe'  => $datos['importe'],
            'periodo'  => $datos['periodo'],
            'concepto' => $datos['concepto'],
        ]);
    }
}
