<?php

namespace App\Contafacil\Compartido\Datos;

class SaldosAFavorDatos
{
    const ORIGEN = [
        [
            'clave' => 'subsidio_al_empleo_por_aplicar',
            'descripcion' => 'Subsidio al empleo por aplicar',
        ],
        [
            'clave' => 'pago_indebido_isr',
            'descripcion' => 'Pago de lo indebido ISR',
        ],
        [
            'clave' => 'pago_indebido_iva',
            'descripcion' => 'Pago de lo indebido IVA',
        ],
        [
            'clave' => 'pago_indebido_ret_isr_sueldos',
            'descripcion' => 'P de lo indeb Ret de ISR por sueldos y salarios',
        ],
        [
            'clave' => 'pago_indebido_ret_isr_asimilados',
            'descripcion' => 'P de lo indeb Ret de ISR por asimilados a salarios',
        ],
        [
            'clave' => 'pago_indebido_ret_isr_arrendamiento',
            'descripcion' => 'P de lo indeb Ret de ISR por arrendamiento',
        ],
        [
            'clave' => 'pago_indebido_ret_isr_servicios_profesionales',
            'descripcion' => 'P de lo indeb Ret de ISR por servicios profesionales',
        ],
        [
            'clave' => 'pago_indebido_ret_isr_dividendos',
            'descripcion' => 'P de lo indeb Ret de ISR por dividendos',
        ],
        [
            'clave' => 'pago_indebido_ret_isr_intereses',
            'descripcion' => 'P de lo indeb Ret de ISR por intereses',
        ],
        [
            'clave' => 'pago_indebido_ret_isr_pagos_extrajero',
            'descripcion' => 'P de lo indeb Ret de ISR por pagos al extranjero',
        ],
        [
            'clave' => 'pago_indebido_ret_isr_venta_acciones',
            'descripcion' => 'P de lo indeb Ret de ISR por venta de acciones',
        ],
        [
            'clave' => 'pago_indebido_ret_isr_venta_partes_sociales',
            'descripcion' => 'P de lo indeb Ret de ISR por venta de partes sociales',
        ],
        [
            'clave' => 'iva_a_favor',
            'descripcion' => 'IVA a favor',
        ],
        [
            'clave' => 'isr_a_favor',
            'descripcion' => 'ISR a favor',
        ],
    ];

    const TIPOS = [
        ['clave' => 'normal', 'descripcion' => 'Normal'],
        ['clave' => 'complementaria', 'descripcion' => 'Complementaria'],
    ];

    const CONCEPTOS_ACREDITAMIENTO = [
        [
            'clave' => 'impuestos_retenidos_isr_sueldos_salarios',
            'descripcion' => 'Impuestos retenidos de ISR por sueldos y salarios',
        ],
        [
            'clave' => 'impuestos_retenidos_isr_asimilados_salarios',
            'descripcion' => 'Impuestos retenidos de ISR por asimilados a salarios',
        ],
        [
            'clave' => 'impuestos_retenidos_isr_arrendamiento',
            'descripcion' => 'Impuestos retenidos de ISR por arrendamiento',
        ],
        [
            'clave' => 'impuestos_retenidos_isr_servicios_profesionales',
            'descripcion' => 'Impuestos retenidos de ISR por servicios profesionales',
        ],
        [
            'clave' => 'impuestos_retenidos_isr_dividendos',
            'descripcion' => 'Impuestos retenidos de ISR por dividendos',
        ],
        [
            'clave' => 'impuestos_retenidos_isr_intereses',
            'descripcion' => 'Impuestos retenidos de ISR por intereses',
        ],
        [
            'clave' => 'impuestos_retenidos_isr_pagos_extrajero',
            'descripcion' => 'Impuestos retenidos de ISR por pagos al extranjero',
        ],
        [
            'clave' => 'impuestos_retenidos_isr_venta_acciones',
            'descripcion' => 'Impuestos retenidos de ISR por venta de acciones',
        ],
        [
            'clave' => 'impuestos_retenidos_isr_venta_partes_sociales',
            'descripcion' => 'Impuestos retenidos de ISR por venta de partes sociales',
        ],
        [
            'clave' => 'iva_retenciones',
            'descripcion' => 'IVA RETENCIONES',
        ],
        [
            'clave' => 'isr_a_cargo_del_periodo',
            'descripcion' => 'ISR A CARGO DEL PERIODO',
        ],
        [
            'clave' => 'iva_del_periodo',
            'descripcion' => 'IVA  DEL PERIODO',
        ],
    ];
}
