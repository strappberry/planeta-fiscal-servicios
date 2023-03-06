<?php

namespace App\Models\QueryBuilders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class PolizaNominaQueryBuilder extends Builder
{
    public function porClaveYFecha(string $clave, Carbon $fecha)
    {
        return $this
            ->where('clave', $clave)
            ->whereMonth('mes_trabajo', $fecha->copy()->startOfMonth())
            ->first()
            ;
    }
}
