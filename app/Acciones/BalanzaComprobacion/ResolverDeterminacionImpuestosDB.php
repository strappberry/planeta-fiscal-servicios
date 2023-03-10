<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Enums\DeterminacionImpuestosEnum;
use App\Models\Cliente;
use App\Models\DeterminacionImpuesto;
use Carbon\Carbon;

class ResolverDeterminacionImpuestosDB
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): DeterminacionImpuesto
    {
        return $cliente->determinacionDelImpuesto()->firstOrCreate(
            ['mes_trabajo' => $fecha->format('Y-m-d')],
            [
                'determinacion'       => null,
                'deducciones'         => [],
                'calculos_iva_isr'    => [],
                'impuestos_federales' => [],
            ],
        );
    }
}
