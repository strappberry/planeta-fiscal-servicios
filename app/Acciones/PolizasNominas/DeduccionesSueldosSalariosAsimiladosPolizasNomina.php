<?php

namespace App\Acciones\PolizasNominas;

use App\Acciones\PolizasNominas\Cuentas\PolizaAsimiladosASalarios;
use App\Models\Cliente;
use App\Models\PolizaNomina;
use Carbon\Carbon;

/*
 | -------------------------------------------------------------------
 | Tabla deducciones: Sueldos, Salarios y Asimilados
 | -------------------------------------------------------------------
 | Este importe se usa para la suma de las deducciones en la
 | determinacion de impuestos
 */
class DeduccionesSueldosSalariosAsimiladosPolizasNomina
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): float
    {
        $deducibleISR = $cliente
            ->polizasNominas()
            ->mesTrabajo($fecha)
            ->sumarDeducibleIsr();

        $asimiladosASalarios = PolizaAsimiladosASalarios::ejecutar($cliente, $fecha);

        return round(
            ($deducibleISR ?? 0) + $asimiladosASalarios,
            2
        );
    }
}
