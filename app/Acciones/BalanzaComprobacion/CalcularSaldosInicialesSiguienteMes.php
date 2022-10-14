<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Contafacil\BalanzaComprobacion\ViewModels\BalanzaComprobacionViewModel;
use App\Models\BalanzaComprobacionCliente;
use App\Models\Cliente;
use Carbon\Carbon;

// TODO: eliminar clase no usada
class CalcularSaldosInicialesSiguienteMes
{
    /**
     * Acción para calcular los saldos del mes indicado y siguiente.
     *
     * - Se calcula la balanza de comprobación del mes indicado.
     * - Se crean o actualizan los saldos finales del mes indicado
     * - Se crean o actualizan los saldos iniciales del siguiente mes.
     */
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
            // Actualizar el saldo final del mes actual
            BalanzaComprobacionCliente::updateOrCreate(
                [
                    'cliente_id' => $cliente->id,
                    'fecha'      => $fechaInicio->format('Y-m-d'),
                    'balanza_comprobacion_id' => $linea['id'],
                ],
                [
                    'cargo'                   => $linea['cargo'],
                    'abono'                   => $linea['abono'],
                    'saldo_final'             => $linea['saldo_final'],
                ]
            );

            // Agregar la linea a la balanza de comprobacion del siguiente mes
            BalanzaComprobacionCliente::updateOrCreate(
                [
                    'fecha'                   => $siguienteMes->format('Y-m-d'),
                    'cliente_id'              => $cliente->id,
                    'balanza_comprobacion_id' => $linea['id'],
                ],
                [
                    'saldo_inicial'           => $linea['saldo_final'],
                ]
            );
        }
    }
}
