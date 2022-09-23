<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Contafacil\BalanzaComprobacion\ViewModels\BalanzaComprobacionViewModel;
use App\Models\BalanzaComprobacionCliente;
use App\Models\Cliente;
use Carbon\Carbon;

class CalcularSaldosInicialesSiguienteMes
{
    public static function ejecutar(
        Carbon $fecha,
        Cliente $cliente
    ) {
        $fechaInicio  = $fecha->copy()->startOfMonth();
        $fechaFin     = $fecha->copy()->endOfMonth();
        $siguienteMes = $fechaInicio->copy()->addMonth();

        $modelo = (new BalanzaComprobacionViewModel(
            $fechaInicio,
            $fechaFin,
            $cliente
        ))->toArray();

        foreach ($modelo['balanza_comprobacion'] as $linea) {
            BalanzaComprobacionCliente::updateOrCreate(
                [
                    'fecha'                   => $siguienteMes->format('Y-m-d'),
                    'cliente_id'              => $cliente->id,
                    'balanza_comprobacion_id' => $linea['id'],
                ],
                [
                    'fecha'                   => $siguienteMes->format('Y-m-d'),
                    'cliente_id'              => $cliente->id,
                    'balanza_comprobacion_id' => $linea['id'],
                    'saldo_inicial'           => $linea['saldo_final'],
                ]
            );
        }
    }
}
