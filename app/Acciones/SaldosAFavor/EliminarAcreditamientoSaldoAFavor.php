<?php

namespace App\Acciones\SaldosAFavor;

use App\Models\SaldoAFavor;
use App\Models\SaldoFavorAcreditamiento;

class EliminarAcreditamientoSaldoAFavor
{
    public static function ejecutar(SaldoAFavor $saldoAFavor, SaldoFavorAcreditamiento $acreditamiento): bool
    {
        $ultimoAcreditamiento = $saldoAFavor->acreditamientos()->ultimoAcreditamiento();

        if ($ultimoAcreditamiento->id !== $acreditamiento->id) return false;

        return $acreditamiento->delete();
    }
}
