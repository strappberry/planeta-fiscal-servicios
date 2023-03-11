<?php

namespace App\Acciones\SaldosAFavor;

use App\Models\SaldoAFavor;

class EliminarSaldoAFavorAccion
{
    public static function ejecutar(SaldoAFavor $saldoAFavor): bool
    {
        if ($saldoAFavor->acreditamientos()->count() > 0) {
            return false;
        }

        return $saldoAFavor->delete();
    }
}
