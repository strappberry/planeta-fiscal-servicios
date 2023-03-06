<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Contafacil\Compartido\Datos\ImpuestosFederalesDatos;
use App\Contafacil\Compartido\Datos\SaldosAFavorDatos;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use App\Models\SaldoFavorAcreditamiento;
use Carbon\Carbon;

class ImpuestosFederalesViewModel extends ViewModel
{
    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha,
    ) {
    }

    public function cuentas(): array
    {
        $cuentas = [];

        foreach(ImpuestosFederalesDatos::CUENTAS as $numeroCuenta)
        {
            $cuenta = [
                'cuenta'      => $numeroCuenta['cuenta'],
                'clave'       => $numeroCuenta['clave'],
                'descripcion' => $numeroCuenta['descripcion'],
                'columna'     => $numeroCuenta['columna'],
                'cargo'       => 0,
                'abono'       => 0,
            ];

            // SUELDOS Y SALARIOS
            if ($numeroCuenta['cuenta'] === '216-01') {
                $saldo =  SaldoFavorAcreditamiento::porConceptoYFecha(
                    SaldosAFavorDatos::IMPUESTOS_RETENIDOS_SUELDOS_Y_SALARIOS,
                    $this->fecha
                );
                $cuenta['cargo'] = $saldo ? $saldo->importe : 0;
            }

            $cuentas[] = $cuenta;
        }

        return $cuentas;
    }
}
