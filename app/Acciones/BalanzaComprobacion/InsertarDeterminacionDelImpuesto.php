<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Contafacil\Facturas\ViewModels\DeterminacionDelImpuestoActividadEmpresarialViewModel;
use App\Contafacil\Facturas\ViewModels\DeterminacionImpuestoRegimen612;
use App\Models\Cliente;
use App\Models\DeterminacionImpuesto;
use Carbon\Carbon;

class InsertarDeterminacionDelImpuesto
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): DeterminacionImpuesto
    {
        $determinacionImpuesto = ResolverDeterminacionDeImpuestos::ejecutar(
            $cliente, $fecha
        );

        $determinacion = $cliente->determinacionDelImpuesto()->updateOrCreate(
            ['mes_trabajo' => $fecha->format('Y-m-d')],
            array_merge(
                $determinacionImpuesto->datosDeterminacion(),
                [
                    'determinacion' => $determinacionImpuesto->toArray(),
                ]
            )
        );

        return $determinacion;
    }
}
