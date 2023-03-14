<?php

namespace App\Acciones\PolizasNominas;

use App\Models\Cliente;
use App\Models\PolizaNomina;
use Carbon\Carbon;

/*
 | -------------------------------------------------------------------
 | Tabla deducciones: IMSS, INFONAVIT, SAR, ISN
 | -------------------------------------------------------------------
 | Este importe se usa para la suma de las deducciones en la
 | determinacion de impuestos
 */
class DeduccionesImssInfonavitSarIsnPolizasNomina
{
    public static function ejecutar(Cliente $cliente, Carbon $fecha): float
    {
        $total = $cliente
            ->polizasNominas()
            ->enClaves([
                'cuotas_al_imss_uno',
                'cuotas_al_imss_dos',
                'aportaciones_al_sar_uno',
                'aporaciones_al_infonavit',
                'contribuciones_pagadas_excepto_isr_ietu_impac_iva_e_ieps',
            ])
            ->mesTrabajo($fecha)
            ->sum('cargo');

        return round($total, 2);
    }
}
