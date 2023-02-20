<?php

namespace App\Contafacil\Compartido\Datos;

class PolizasNominasDatos
{
    const SEGMENTO_SUELDOS_SALARIOS = 'sueldos_y_salarios_semiautomatico';
    const SEGMENTO_ASIMILADOS = 'asimilados_semiautomatico';
    const SEGMENTO_PROVISION_COSTOS_PATRONALES_EMA_EBA = 'provision_costos_patronales_ema_eba_semiautomatico';
    const SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA = 'provision_impuesto_sobre_nomina_semiautomatico';

    const SUELDOS_SALARIOS = [
        [
            'clave' => 'sueldos_salarios',
            'descripcion' => 'Sueldos y salarios',
            'cuenta' => '601-01',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '401'],
                ['accion' => 'suma', 'cuenta' => '409'],
                ['accion' => 'suma', 'cuenta' => '413'],
                ['accion' => 'suma', 'cuenta' => '420'],
                ['accion' => 'suma', 'cuenta' => '430'],
                ['accion' => 'suma', 'cuenta' => '431'],
                ['accion' => 'suma', 'cuenta' => '447'],
                ['accion' => 'suma', 'cuenta' => '523'],
                ['accion' => 'suma', 'cuenta' => '598'],
                ['accion' => 'resta', 'cuenta' => '604'],
                ['accion' => 'resta', 'cuenta' => '605'],
                ['accion' => 'resta', 'cuenta' => '606'],
                ['accion' => 'resta', 'cuenta' => '607'],
                ['accion' => 'resta', 'cuenta' => '608'],
                ['accion' => 'resta', 'cuenta' => '609'],
                ['accion' => 'resta', 'cuenta' => '610'],
                ['accion' => 'resta', 'cuenta' => '611'],
                ['accion' => 'resta', 'cuenta' => '612'],
                ['accion' => 'resta', 'cuenta' => '613'],
                ['accion' => 'resta', 'cuenta' => '637'],
                ['accion' => 'resta', 'cuenta' => '650'],
                ['accion' => 'resta', 'cuenta' => '651'],
                ['accion' => 'resta', 'cuenta' => '652'],
                ['accion' => 'resta', 'cuenta' => '653'],
                ['accion' => 'resta', 'cuenta' => '670'],
                ['accion' => 'resta', 'cuenta' => '671'],
                ['accion' => 'resta', 'cuenta' => '672'],
                ['accion' => 'resta', 'cuenta' => '673'],
                ['accion' => 'resta', 'cuenta' => '674'],
                ['accion' => 'resta', 'cuenta' => '676'], // No estaba en excel
                ['accion' => 'resta', 'cuenta' => '679'],
                ['accion' => 'resta', 'cuenta' => '680'],
                ['accion' => 'resta', 'cuenta' => '681'],
            ],
        ],
        [
            'clave' => 'tiempos_extras',
            'descripcion' => 'Tiempos extras',
            'cuenta' => '601-03',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '432'],
                ['accion' => 'suma', 'cuenta' => '433'],
            ],
        ],
        [
            'clave' => 'vacaciones',
            'descripcion' => 'Vacaciones',
            'cuenta' => '601-06',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '403'],
                ['accion' => 'suma', 'cuenta' => '421'],
                ['accion' => 'suma', 'cuenta' => '510'],
            ],
        ],
        [
            'clave' => 'prima_vacacional',
            'descripcion' => 'Prima vacacional',
            'cuenta' => '601-07',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '501'],
            ],
        ],
        [
            'clave' => 'prima_dominical',
            'descripcion' => 'Prima dominical',
            'cuenta' => '601-08',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '450'],
            ],
        ],
        [
            'clave' => 'dias_festivos',
            'descripcion' => 'Días festivos',
            'cuenta' => '601-09',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '434'],
            ],
        ],
        [
            'clave' => 'gratificaciones',
            'descripcion' => 'Gratificaciones',
            'cuenta' => '601-10',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '516'],
            ],
        ],
        [
            'clave' => 'prima_antiguedad',
            'descripcion' => 'Primas de antigüedad',
            'cuenta' => '601-11',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'sumar', 'cuenta' => '513'],
            ],
        ],
        [
            'clave' => 'aguinaldo',
            'descripcion' => 'Aguinaldo',
            'cuenta' => '601-12',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '502'],
                ['accion' => 'resta', 'cuenta' => '688'],
            ],
        ],
        [
            'clave' => 'indemnizaciones',
            'descripcion' => 'Indemnizaciones',
            'cuenta' => '601-13',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '514'],
                ['accion' => 'suma', 'cuenta' => '515'],
                ['accion' => 'suma', 'cuenta' => '517'],
                ['accion' => 'suma', 'cuenta' => '518'],
            ],
        ],
        [
            'clave' => 'ptu',
            'descripcion' => 'PTU',
            'cuenta' => '601-21',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '503'],
            ],
        ],
        [
            'clave' => 'prevision_social',
            'descripcion' => 'Previsión social',
            'cuenta' => '601-23',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'sumar', 'cuenta' => '436'],
                ['accion' => 'sumar', 'cuenta' => '437'],
                ['accion' => 'sumar', 'cuenta' => '439'],
                ['accion' => 'sumar', 'cuenta' => '451'],
                ['accion' => 'sumar', 'cuenta' => '461'],
                ['accion' => 'sumar', 'cuenta' => '504'],
                ['accion' => 'sumar', 'cuenta' => '505'],
                ['accion' => 'sumar', 'cuenta' => '506'],
                ['accion' => 'sumar', 'cuenta' => '522'],
                ['accion' => 'sumar', 'cuenta' => '545'],
                ['accion' => 'resta', 'cuenta' => '633'],
                ['accion' => 'resta', 'cuenta' => '634'],
                ['accion' => 'resta', 'cuenta' => '645'],
            ],
        ],
        [
            'clave' => 'otras_prestaciones_al_personal',
            'descripcion' => 'Otras prestaciones al personal',
            'cuenta' => '601-25',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '462'],
                ['accion' => 'suma', 'cuenta' => '463'],
                ['accion' => 'suma', 'cuenta' => '464'],
                ['accion' => 'suma', 'cuenta' => '465'],
                ['accion' => 'suma', 'cuenta' => '466'],
                ['accion' => 'suma', 'cuenta' => '467'],
                ['accion' => 'suma', 'cuenta' => '469'],
                ['accion' => 'suma', 'cuenta' => '480'],
                ['accion' => 'suma', 'cuenta' => '521'],
                ['accion' => 'suma', 'cuenta' => '526'],
            ],
        ],
        [
            'clave' => 'deudores_diversos_cargo',
            'descripcion' => 'Deudores diversos',
            'cuenta' => '107-01',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '446'],
                ['accion' => 'suma', 'cuenta' => '487'],
                ['accion' => 'suma', 'cuenta' => '494'],
            ],
        ],
        [
            'clave' => 'subsidio_al_empleo_por_aplicar',
            'descripcion' => 'Subsidio al empleo por aplicar',
            'cuenta' => '110-01',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '594'],
                ['accion' => 'suma', 'cuenta' => '595'],
                ['accion' => 'suma', 'cuenta' => '599'],
                ['accion' => 'resta', 'cuenta' => '709'],
                ['accion' => 'resta', 'cuenta' => '712'],
            ],
        ],
        [
            'clave' => 'impuestos_retenidos_de_isr_por_sueldos_y_salarios',
            'descripcion' => 'Impuestos retenidos de ISR por sueldos y salarios',
            'cuenta' => '216-01',
            'columna' => 'abono',
            'formula' => [
                ['accion' => 'resta', 'cuenta' => '488'],
                ['accion' => 'resta', 'cuenta' => '520'],
                ['accion' => 'resta', 'cuenta' => '597'],
                ['accion' => 'suma', 'cuenta' => '620'],
                ['accion' => 'suma', 'cuenta' => '621'], // no esta en excel
                ['accion' => 'suma', 'cuenta' => '622'],
                ['accion' => 'suma', 'cuenta' => '623'],
                ['accion' => 'suma', 'cuenta' => '624'],
                ['accion' => 'suma', 'cuenta' => '625'],
                ['accion' => 'suma', 'cuenta' => '642'],
            ],
        ],
        [
            'clave' => 'provision_de_infonavit_por_pagar',
            'descripcion' => 'Provisión de infonavit por pagar',
            'cuenta' => '211-03',
            'columna' => 'abono',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '632'],
            ],
        ],
        [
            'clave' => 'retenciones_de_imss_a_los_trabajadores',
            'descripcion' => 'Retenciones de IMSS a los trabajadores',
            'cuenta' => '216-11',
            'columna' => 'abono',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '630'],
            ],
        ],
        [
            'clave' => 'deudores_diversos_abono',
            'descripcion' => 'Deudores diversos',
            'cuenta' => '107-01',
            'columna' => 'abono',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '656'],
                ['accion' => 'suma', 'cuenta' => '657'],
                ['accion' => 'suma', 'cuenta' => '658'],
                ['accion' => 'suma', 'cuenta' => '676'],
                ['accion' => 'suma', 'cuenta' => '677'],
                ['accion' => 'suma', 'cuenta' => '678'],
            ],
        ],
        // [
        //     'clave' => 'provision_de_sueldos_y_salarios_por_pagar',
        //     'descripcion' => 'Provisión de sueldos y salarios por pagar',
        //     'cuenta' => '210-01',
        //     'formula' => [],
        // ],
    ];

    const ASIMILADOS = [
        [
            'clave' => 'asimilados_a_salarios',
            'descripcion' => 'Asimilados a salarios',
            'cuenta' => '601-31',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '481'],
            ],
        ],
        [
            'clave' => 'impuestos_retenidos_de_isr_por_asimilados_a_salarios',
            'descripcion' => 'Impuestos retenidos de ISR por asimilados a salarios',
            'cuenta' => '216-02',
            'columna' => 'abono',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '621'],
            ],
        ],
    ];

    const PROVISION_COSTOS_PATRONALES_EMA_EBA = [
        [
            'clave' => 'cuotas_al_imss_uno',
            'descripcion' => 'Cuotas al IMSS ',
            'cuenta' => '601-26',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '802'],
                ['accion' => 'suma', 'cuenta' => '803'],
                ['accion' => 'suma', 'cuenta' => '804'],
                ['accion' => 'suma', 'cuenta' => '805'],
                ['accion' => 'suma', 'cuenta' => '813'],
                ['accion' => 'suma', 'cuenta' => '809'],
                ['accion' => 'suma', 'cuenta' => '810'],
            ],
        ],
        [
            'clave' => 'cuotas_al_imss_dos',
            'descripcion' => 'Cuotas al IMSS ',
            'cuenta' => '601-26',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '811'],
            ],
        ],
        [
            'clave' => 'aportaciones_al_sar_uno',
            'descripcion' => 'Aportaciones al SAR ',
            'cuenta' => '601-28',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '808'],
            ],
        ],
        [
            'clave' => 'aporaciones_al_infonavit',
            'descripcion' => 'Aportaciones al infonavit ',
            'cuenta' => '601-27',
            'columna' => 'cargo',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '814'],
            ],
        ],
        [ // Debe tener las mismas cuentas que  cuotas_al_imss_uno
            'clave' => 'provision_de_imss_patronal_por_pagar_uno',
            'descripcion' => 'Provisión de IMSS patronal por pagar ',
            'cuenta' => '211-01',
            'columna' => 'abono',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '802'],
                ['accion' => 'suma', 'cuenta' => '803'],
                ['accion' => 'suma', 'cuenta' => '804'],
                ['accion' => 'suma', 'cuenta' => '805'],
                ['accion' => 'suma', 'cuenta' => '813'],
                ['accion' => 'suma', 'cuenta' => '809'],
                ['accion' => 'suma', 'cuenta' => '810'],
            ],
        ],
        [ // Debe tener las mismas cuentas que  cuotas_al_imss_dos
            'clave' => 'provision_de_imss_patronal_por_pagar_dos',
            'descripcion' => 'Provisión de IMSS patronal por pagar ',
            'cuenta' => '211-01',
            'columna' => 'abono',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '811'],
            ],
        ],
        [ // Debe tener las mismas cuentas que  aportaciones_al_sar_uno
            'clave' => 'provision_de_sar_por_pagar',
            'descripcion' => 'Provisión de SAR por pagar',
            'cuenta' => '211-02',
            'columna' => 'abono',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '808'],
            ],
        ],
        [ // Debe tener las mismas cuentas que  aporaciones_al_infonavit
            'clave' => 'provision_de_infonavit_por_pagar',
            'descripcion' => 'Provisión de infonavit por pagar',
            'cuenta' => '211-03',
            'columna' => 'abono',
            'formula' => [
                ['accion' => 'suma', 'cuenta' => '814'],
            ],
        ],
    ];
}
