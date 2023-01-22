<?php

namespace App\Acciones\BalanzaComprobacion;

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
    ): DeterminacionImpuesto {
        $determinacionImpuesto = ResolverDeterminacionImpuestosDB::ejecutar($cliente, $fecha);

        $camposEditables = $determinacionImpuesto->campos_editables;
        $camposEditables[$regimen] = (isset($camposEditables[$regimen])) ?
            array_merge($camposEditables[$regimen], $campos) :
            $campos;

        $determinacionImpuesto->campos_editables = $camposEditables;
        $determinacionImpuesto->save();

        return $determinacionImpuesto;
    }
}
