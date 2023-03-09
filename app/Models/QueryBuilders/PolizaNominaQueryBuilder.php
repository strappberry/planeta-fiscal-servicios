<?php

namespace App\Models\QueryBuilders;

use App\Models\PolizaNomina;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class PolizaNominaQueryBuilder extends Builder
{
    public function enClaves(array $claves): Builder
    {
        return $this
            ->whereIn('clave', $claves)
            ;
    }

    public function mesTrabajo(Carbon $fecha): Builder
    {
        return $this
            ->whereMonth('mes_trabajo', $fecha->copy()->startOfMonth())
            ;
    }

    public function porClaveYFecha(string $clave, Carbon $fecha): PolizaNomina
    {
        return $this
            ->mesTrabajo($fecha)
            ->where('clave', $clave)
            ->first()
            ;
    }

    public function sumarDeducibleIsr(): float
    {
        return $this->sum('deducible_isr');
    }
}
