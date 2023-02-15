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
}
