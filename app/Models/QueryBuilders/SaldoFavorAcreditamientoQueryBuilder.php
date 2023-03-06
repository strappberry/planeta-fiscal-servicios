<?php

namespace App\Models\QueryBuilders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SaldoFavorAcreditamientoQueryBuilder extends Builder
{
    public function porConceptoYFecha(string $clave, Carbon $fecha): Builder
    {
        return $this
            ->where('concepto', $clave)
            ->whereYear('periodo', $fecha->year)
            ->whereMonth('periodo', $fecha->month);
    }
}
