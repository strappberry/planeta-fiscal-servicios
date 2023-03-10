<?php

namespace App\Acciones\CamposEditables;

use App\Models\Cliente;
use Carbon\Carbon;

class ImpuestoFederalOtrosGastosAccion
{
    const CAMPO = 'otros_gastos';

    public static function resolver(Cliente $cliente, Carbon $fecha): float
    {
        $campoEditable = $cliente->camposEditables()
            ->mesTrabajo($fecha)
            ->impuestosFederales()
            ->campo(self::CAMPO)
            ->first();

        if (!$campoEditable) return 0;

        return (float) $campoEditable->valor;
    }
}
