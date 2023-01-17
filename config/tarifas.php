<?php

return [
    'tablas' => [
        /*
        |--------------------------------------------------------------------------
        | 612 - Personas Físicas con Actividades Empresariales y Profesionales
        |--------------------------------------------------------------------------
        */
        [
            'regimen' => '612',
            'titulo' => 'Personas Físicas con Actividades Empresariales y Profesionales',
            'desde' => 2018,
            'tablas' => [
                [
                    'clave' => 'provisional_enero',
                    'titulo' => 'Tarifa para el pago provisional del mes de enero',
                    'titulo_corto' => 'Provisional Enero',
                    'desde_mes' => 1,
                    'hasta_mes' => 1,
                ],
                [
                    'clave' => 'provisional_febrero',
                    'titulo' => 'Tarifa para el pago provisional del mes de febrero',
                    'titulo_corto' => 'Provisional Febrero',
                    'desde_mes' => 2,
                    'hasta_mes' => 2,
                ],
                [
                    'clave' => 'provisional_marzo',
                    'titulo' => 'Tarifa para el pago provisional del mes de marzo',
                    'titulo_corto' => 'Provisional Marzo',
                    'desde_mes' => 3,
                    'hasta_mes' => 3,
                ],
                [
                    'clave' => 'provisional_abril',
                    'titulo' => 'Tarifa para el pago provisional del mes de abril',
                    'titulo_corto' => 'Provisional Abril',
                    'desde_mes' => 4,
                    'hasta_mes' => 4,
                ],
                [
                    'clave' => 'provisional_mayo',
                    'titulo' => 'Tarifa para el pago provisional del mes de mayo',
                    'titulo_corto' => 'Provisional Mayo',
                    'desde_mes' => 5,
                    'hasta_mes' => 5,
                ],
                [
                    'clave' => 'provisional_junio',
                    'titulo' => 'Tarifa para el pago provisional del mes de junio',
                    'titulo_corto' => 'Provisional Junio',
                    'desde_mes' => 6,
                    'hasta_mes' => 6,
                ],
                [
                    'clave' => 'provisional_julio',
                    'titulo' => 'Tarifa para el pago provisional del mes de julio',
                    'titulo_corto' => 'Provisional Julio',
                    'desde_mes' => 7,
                    'hasta_mes' => 7,
                ],
                [
                    'clave' => 'provisional_agosto',
                    'titulo' => 'Tarifa para el pago provisional del mes de agosto',
                    'titulo_corto' => 'Provisional Agosto',
                    'desde_mes' => 8,
                    'hasta_mes' => 8,
                ],
                [
                    'clave' => 'provisional_septiembre',
                    'titulo' => 'Tarifa para el pago provisional del mes de septiembre',
                    'titulo_corto' => 'Provisional Septiembre',
                    'desde_mes' => 9,
                    'hasta_mes' => 9,
                ],
                [
                    'clave' => 'provisional_octubre',
                    'titulo' => 'Tarifa para el pago provisional del mes de octubre',
                    'titulo_corto' => 'Provisional Octubre',
                    'desde_mes' => 10,
                    'hasta_mes' => 10,
                ],
                [
                    'clave' => 'provisional_noviembre',
                    'titulo' => 'Tarifa para el pago provisional del mes de noviembre',
                    'titulo_corto' => 'Provisional Noviembre',
                    'desde_mes' => 11,
                    'hasta_mes' => 11,
                ],
                [
                    'clave' => 'provisional_diciembre',
                    'titulo' => 'Tarifa para el pago provisional del mes de diciembre',
                    'titulo_corto' => 'Provisional Diciembre',
                    'desde_mes' => 12,
                    'hasta_mes' => 12,
                ],
            ],
        ],

        [
            'regimen' => '626',
            'titulo' => 'RESICO',
            'desde' => 2018,
            'tablas' => [
                [
                    'clave' => 'mensual',
                    'titulo' => 'Tarifa mensual',
                    'titulo_corto' => 'Mensual',
                    'desde_mes' => 1,
                    'hasta_mes' => 12,
                ],
                [
                    'clave' => 'anual',
                    'titulo' => 'Tarifa anual',
                    'titulo_corto' => 'Anual',
                    'desde_mes' => 13,
                    'hasta_mes' => 13,
                ],
            ],
        ],
    ],
];
