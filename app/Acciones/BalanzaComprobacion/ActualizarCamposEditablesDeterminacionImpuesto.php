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
    ): DeterminacionImpuesto {
        $determinacionImpuesto = ResolverDeterminacionImpuestosDB::ejecutar($cliente, $fecha);

        $camposEditables = $determinacionImpuesto->campos_editables;
        $camposEditables[$regimen] = (isset($camposEditables[$regimen])) ?
            array_merge($camposEditables[$regimen], $campos) :
            $campos;

        if (isset($camposEditables[$regimen][DeterminacionImpuestosEnum::CAMPO_COEFICIENTE_UTILIDAD])) {
            $determinacionImpuesto->{DeterminacionImpuesto::COEFICIENTE_UTILIDAD} = round(
                $camposEditables[$regimen][DeterminacionImpuestosEnum::CAMPO_COEFICIENTE_UTILIDAD],
                2
            );
        }

        $determinacionImpuesto->campos_editables = $camposEditables;
        $determinacionImpuesto->save();

        return $determinacionImpuesto;
    }
}
