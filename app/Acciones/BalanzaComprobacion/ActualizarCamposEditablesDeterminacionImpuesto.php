<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Enums\DeterminacionImpuestosEnum;
use App\Models\Cliente;
use App\Models\DeterminacionImpuesto;
use Carbon\Carbon;

class ActualizarCamposEditablesDeterminacionImpuesto
{
    public static function ejecutar(
        Cliente $cliente,
        Carbon $fecha,
        $regimen,
        array $campos
    ) {
        foreach($campos as $campo => $valor) {
            $cliente->determinacionCamposEditables()->updateOrCreate(
                [
                    'mes_trabajo' => $fecha,
                    'regimen' => $regimen,
                    'clave' => $campo,
                ],
                [
                    'valor' => $valor,
                ]
            );
        }
    }
}
