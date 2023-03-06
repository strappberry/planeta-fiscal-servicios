<?php

namespace App\Contafacil\Compartido\Datos;

class ImpuestosFederalesDatos
{
    const CUENTAS = [
        [
            'cuenta'      => '213-01',
            'clave'       => 'iva_por_pagar',
            'descripcion' => 'IVA por pagar',
            'columna'     => 'cargo',
        ],
        [
            'cuenta'      => '213-03',
            'clave'       => 'isr_por_pagar',
            'descripcion' => 'ISR por pagar',
            'columna'     => 'cargo',
        ],
        [
            'cuenta'      => '216-01',
            'clave'       => 'impuestos_retenidos_isr_sueldos',
            'descripcion' => 'Impuestos retenidos de ISR por sueldos y salarios',
            'columna'     => 'cargo',
        ],
        [
            'cuenta'      => '216-02',
            'clave'       => 'impuestos_retenidos_isr_asimilados',
            'descripcion' => 'Impuestos retenidos de ISR por asimilados a salarios',
            'columna'     => 'cargo',
        ],
        [
            'cuenta'      => '216-03',
            'clave'       => 'impuestos_retenidos_isr_arrendamiento',
            'descripcion' => 'Impuestos retenidos de ISR Arrendamiento',
            'columna'     => 'cargo',
        ],
        [
            'cuenta'      => '216-04',
            'clave'       => 'impuestos_retenidos_isr_servicios_profesionales',
            'descripcion' => 'Impuestos retenidos de ISR Servicios profesionales',
            'columna'     => 'cargo',
        ],
        [
            'cuenta'      => '216-10',
            'clave'       => 'impuestos_retenidos_iva',
            'descripcion' => 'Impuestos retenidos de IVA',
            'columna'     => 'cargo',
        ],
        [
            'cuenta'      => '601-81',
            'clave'       => 'gastos_no_deducibles',
            'descripcion' => 'GASTOS NO DEDUCIBLE',
            'columna'     => 'cargo',
        ],
        [
            'cuenta'      => '601-84',
            'clave'       => 'otros_gastos',
            'descripcion' => 'Otros gastos',
            'columna'     => 'cargo',
        ],
        [
            'cuenta'      => '113-01-03',
            'clave'       => 'iva_a_favor',
            'descripcion' => 'IVA a favor',
            'columna'     => 'abono',
        ],
        [
            'cuenta'      => '113-02-03',
            'clave'       => 'isr_a_favor',
            'descripcion' => 'ISR a favor',
            'columna'     => 'abono',
        ],
        [
            'cuenta'      => '110-01',
            'clave'       => 'subsidio_al_empleo_por_aplicar',
            'descripcion' => 'Subsidio al empleo por aplicar',
            'columna'     => 'abono',
        ],
        [
            'cuenta'      => '102-01',
            'clave'       => 'bancos_nacional',
            'descripcion' => 'Bancos nacional',
            'columna'     => 'abono',
        ]
    ];
}
