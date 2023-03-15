<?php

namespace App\Models\QueryBuilders;

use App\Models\SaldoFavorAcreditamiento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SaldoFavorAcreditamientoQueryBuilder extends Builder
{
    public function conceptoFecha(string $concepto, Carbon $fecha): Builder
    {
        return $this
            ->where('concepto', $concepto)
            ->mes($fecha);
    }

    public function mes(Carbon $fecha): Builder
    {
        return $this
            ->whereMonth('periodo', $fecha->month)
            ->whereYear('periodo', $fecha->year);
    }

    public function sumarImporte(int $decimal = 2): float
    {
        return round($this->sum('importe'), $decimal);
    }

    public function ultimoAcreditamiento(): ?SaldoFavorAcreditamiento
    {
        return $this
            ->orderBy('id', 'desc')
            ->first();
    }
}
