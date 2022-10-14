<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Contafacil\BalanzaComprobacion\ViewModels\BalanzaComprobacionViewModel;
use App\Models\BalanzaComprobacionCliente;
use App\Models\MesTrabajo;

class InsertarSaldosFinalesBalanzaComprobacion
{
    public static function ejecutar(MesTrabajo $mesTrabajo, BalanzaComprobacionViewModel $balanzaMes)
    {
        $balanzaMes = $balanzaMes->toArray();
        $cliente    = $mesTrabajo->cliente;
        $fecha      = $mesTrabajo->fecha->format('Y-m-d');

        foreach ($balanzaMes['balanza_comprobacion'] as $linea) {
            BalanzaComprobacionCliente::updateOrCreate(
                [
                    'cliente_id'              => $cliente->id,
                    'fecha'                   => $fecha,
                    'balanza_comprobacion_id' => $linea['id'],
                ],
                [
                    'cargo'                   => $linea['cargo'],
                    'abono'                   => $linea['abono'],
                    'saldo_final'             => $linea['saldo_final'],
                ]
            );
        }
    }
}
