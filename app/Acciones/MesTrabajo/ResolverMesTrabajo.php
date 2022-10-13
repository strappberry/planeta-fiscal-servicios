<?php

namespace App\Acciones\MesTrabajo;

use App\Models\Cliente;
use App\Models\MesTrabajo;
use Carbon\Carbon;

class ResolverMesTrabajo
{
    /**
     * Dada una fecha y un cliente se obtienen los datos del mes de trabajo.
     * Si no se encuentra el mes de trabajo se crea uno nuevo con datos por default.
     */
    public static function ejecutar(Carbon $fecha, Cliente $cliente)
    {
        return MesTrabajo::firstOrCreate(
            [
                'fecha'      => $fecha->format('Y-m-d'),
                'cliente_id' => $cliente->id,
            ],
            [
                'bloqueado'         => false,
                'polizas_validadas' => false,
            ]
        );
    }
}
