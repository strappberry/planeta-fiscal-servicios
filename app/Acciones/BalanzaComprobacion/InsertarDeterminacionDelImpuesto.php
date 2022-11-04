<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Contafacil\Facturas\ViewModels\DeterminacionDelImpuestoViewModel;
use App\Models\Cliente;
use App\Models\DeterminacionImpuesto;
use Carbon\Carbon;

class InsertarDeterminacionDelImpuesto
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): DeterminacionImpuesto
    {
        $determinacionDelImpuesto = (new DeterminacionDelImpuestoViewModel($cliente, $fecha))->toArray();

        $determinacion = $cliente->determinacionDelImpuesto()->updateOrCreate(
            [
                'mes_trabajo' => $fecha->format('Y-m-d'),
            ],
            [
                'ingresos_acumulados'    => $determinacionDelImpuesto['ingresos_acumulados'],
                'deducciones_acumuladas' => $determinacionDelImpuesto['deducciones_acumuladas'],
                'pp_pagados'             => $determinacionDelImpuesto['pp_pagados'],
                'isr_actividad'          => $determinacionDelImpuesto['calculos_tarifa']['isr_actividad'],
                'determinacion'          => $determinacionDelImpuesto,
            ]
        );

        return $determinacion;
    }
}
