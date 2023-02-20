<?php

namespace App\Acciones\PolizasNominas;

use App\Contafacil\Compartido\Datos\PolizasNominasDatos;
use App\Models\Cliente;
use Carbon\Carbon;

class GuardarPolizasNominasAccion
{
    public static function ejecutar(
        Cliente $cliente,
        Carbon $fecha,
        array $datos
    ) {
        $segmentos = [
            PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS,
            PolizasNominasDatos::SEGMENTO_ASIMILADOS,
            PolizasNominasDatos::SEGMENTO_PROVISION_COSTOS_PATRONALES_EMA_EBA,
            PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA,
        ];

        foreach($segmentos as $segmento) {
            if (!isset($datos[$segmento])) continue;

            foreach($datos[$segmento] as $linea) {
                $cliente->polizasNominas()->updateOrCreate([
                    'mes_trabajo' => $fecha->format('Y-m-d'),
                    'segmento' => $segmento,
                    'clave' => $linea['clave'],
                ], [
                    'descripcion' => $linea['descripcion'] ?? '',
                    'cuenta' => $linea['cuenta'],
                    'columna' => $linea['columna'],
                    'cargo' => $linea['cargo'],
                    'abono' => $linea['abono'],
                ]);
            }
        }
    }
}
