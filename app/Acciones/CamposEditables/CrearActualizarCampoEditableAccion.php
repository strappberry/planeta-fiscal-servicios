<?php

namespace App\Acciones\CamposEditables;

use App\Models\Cliente;
use Carbon\Carbon;

class CrearActualizarCampoEditableAccion
{
    public static function ejecutar(
        Cliente $cliente,
        Carbon $fecha,
        string $modulo,
        string $campo,
        string $valor,
    ) {
        return $cliente->camposEditables()
            ->updateOrCreate(
                [
                    'mes_trabajo' => $fecha,
                    'modulo'      => $modulo,
                    'campo'       => $campo,
                ],
                [
                    'valor' => $valor,
                ],
            );
    }
}
