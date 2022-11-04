<?php

namespace App\Acciones\MesTrabajo;

use App\Acciones\BalanzaComprobacion\InsertarDeterminacionDelImpuesto;
use App\Acciones\BalanzaComprobacion\InsertarSaldosFinalesBalanzaComprobacion;
use App\Acciones\BalanzaComprobacion\InsertarSaldosInicialesBalanzaComprobacion;
use App\Contafacil\BalanzaComprobacion\ViewModels\BalanzaComprobacionViewModel;
use App\Models\Cliente;
use App\Models\MesTrabajo;

class BloquearMesTrabajo
{
    /**
     * Dado un mes de trabajo se actualiza el campo bloqueado a true.
     *
     * Si el campo cascada es true, se calcularán los saldos inicilaes de los siguientes meses
     * hasta el ultimo mes de trabajo desbloqueado.
     */
    public static function ejecutar(
        MesTrabajo $mesTrabajo,
        Cliente $cliente,
        bool $cascada = false
    ) {
        // Se bloquea el mes de trabajo indicado.
        $mesTrabajo->bloqueado = true;
        $mesTrabajo->save();

        // Se calcula la balanza de comprobación del mes indicado.
        $balanzaMes = new BalanzaComprobacionViewModel(
            $mesTrabajo->fecha->copy()->startOfMonth(),
            $mesTrabajo->fecha->copy()->endOfMonth(),
            $cliente
        );

        // Se calculará la determinacion del impuesto y se guardara en base de datos.
        InsertarDeterminacionDelImpuesto::ejecutar($cliente, $mesTrabajo->fecha->copy()->startOfMonth());

        // Se insertan los saldos finales de la balanza del mes de trabajo indicado.
        InsertarSaldosFinalesBalanzaComprobacion::ejecutar($mesTrabajo, $balanzaMes);

        if ($cascada) {
            // Si la opción cascada es true, se obtiene el mes de trabajo siguiente
            $mesTrabajo = ResolverMesTrabajo::ejecutar(
                $mesTrabajo->fecha->copy()->addMonth()->startOfMonth(),
                $cliente
            );
            // Se insertan los saldos iniciales de la balanza al nuevo mes de trabajo
            InsertarSaldosInicialesBalanzaComprobacion::ejecutar($mesTrabajo, $balanzaMes);

            // Si el nuevo mes de trabajo esta bloqueado se insertaran saldos finales
            // y los saldos iniciales del siguiente mes de trabajo.
            while($mesTrabajo->bloqueado) {
                // Se calcula la balanza de comprobación del nuevo mes de trabajo.
                $balanzaMes = new BalanzaComprobacionViewModel(
                    $mesTrabajo->fecha->copy()->startOfMonth(),
                    $mesTrabajo->fecha->copy()->endOfMonth(),
                    $cliente
                );
                // Se insertan los saldos finales de la balanza del nuevo mes de trabajo.
                InsertarSaldosFinalesBalanzaComprobacion::ejecutar($mesTrabajo, $balanzaMes);

                // Se obtiene el mes de trabajo siguiente
                $mesTrabajo = ResolverMesTrabajo::ejecutar(
                    $mesTrabajo->fecha->copy()->addMonth()->startOfMonth(),
                    $cliente
                );
                // Se insertan los saldos iniciales de la balanza del nuevo mes de trabajo
                InsertarSaldosInicialesBalanzaComprobacion::ejecutar($mesTrabajo, $balanzaMes);
            } // Si el nuevo mes de trabajo esta bloqueado se repite el proceso.
        }
    }
}
