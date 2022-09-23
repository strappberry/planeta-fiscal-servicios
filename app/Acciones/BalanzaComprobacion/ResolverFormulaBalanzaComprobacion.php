<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Models\BalanzaComprobacion;

class ResolverFormulaBalanzaComprobacion
{
    public static function ejecutar(
        BalanzaComprobacion $balanzaComprobacion,
        float $saldoInicial,
        float $cargo,
        float $abono
    ): float {
        $saldoFinal = 0;

        $columnas = [
            'saldo_inicial' => $saldoInicial,
            'cargo'         => $cargo,
            'abono'         => $abono,
        ];

        foreach ($balanzaComprobacion->formula as $linea) {
            switch ($linea['operacion']) {
                case 'suma':
                    $saldoFinal += $columnas[$linea['columna']];
                    break;

                case 'resta':
                    $saldoFinal -= $columnas[$linea['columna']];
                    break;

                default:
                    break;
            }
        }

        return $saldoFinal;
    }
}
