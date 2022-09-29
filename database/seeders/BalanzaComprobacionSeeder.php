<?php

namespace Database\Seeders;

use App\Models\BalanzaComprobacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BalanzaComprobacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lineas = [
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '102',
                'descripcion'   => 'Efectivo en caja y depósitos en instituciones de crédito',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '102-01',
                        'descripcion' => 'Efectivo en caja y depósitos en instituciones de crédito',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '103',
                'descripcion'   => 'Inversiones',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '103-01',
                        'descripcion' => 'Inversiones',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '105',
                'descripcion'   => 'Clientes',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '105-01',
                        'descripcion' => 'Clientes',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '107',
                'descripcion'   => 'Deudores diversos',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '107-01',
                        'descripcion' => 'Deudores diversos',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '110',
                'descripcion'   => ' Subsidio al empleo por aplicar',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '110-01',
                        'descripcion' => 'Subsidio al empleo por aplicar',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '113',
                'descripcion'   => 'Impuestos a favor',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-01',
                        'descripcion' => 'IVA a favor',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-01-01',
                        'descripcion' => 'Retencion de IVA (ingresos No cobrados)',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-01-02',
                        'descripcion' => 'Retencion de IVA (ingresos cobrados)',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-01-03',
                        'descripcion' => 'IVA a favor',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-02',
                        'descripcion' => 'ISR a favor',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-02-01',
                        'descripcion' => 'Retencion de ISR (Ingresos No Cobrados)',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-02-02',
                        'descripcion' => 'Retencion de ISR (Ingresos Cobrados)',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-02-03',
                        'descripcion' => 'ISR a favor de Ejercicios anteriores',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-02-04',
                        'descripcion' => 'ISR a favor del Ejercicio',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '113-02-05',
                        'descripcion' => 'ISR a favor pago de lo indebido',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '114',
                'descripcion'   => 'Pagos provisionales ',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '114-01',
                        'descripcion' => 'Pagos provisionales de ISR',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '115',
                'descripcion'   => 'Inventario',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '115-01',
                        'descripcion' => 'Inventario',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '118',
                'descripcion'   => 'Impuestos acreditables pagados',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '118-01',
                        'descripcion' => 'IVA Acreditable pagado',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '119',
                'descripcion'   => 'Impuestos acreditables por pagar',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '119-01',
                        'descripcion' => 'IVA Acreditable por pagar',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '150',
                'descripcion'   => 'Activo Fijo',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '150-01',
                        'descripcion' => 'Terrenos',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '150-02',
                        'descripcion' => 'Construcciones',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '150-03',
                        'descripcion' => 'Maquinaría y equipo',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '150-04',
                        'descripcion' => 'Mobiliario y equipo de oficina',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '150-05',
                        'descripcion' => 'Equipo de transporte',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '150-06',
                        'descripcion' => 'Otros activos fijos',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '150-07',
                        'descripcion' => 'Cargos y gastos diferidos',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '151',
                'descripcion'   => 'Depreciación acumulada',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '151-01',
                        'descripcion' => 'Depreciación acumulada',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '152',
                'descripcion'   => 'Amortización acumulada',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '152-01',
                        'descripcion' => 'Amortización acumulada',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '201',
                'descripcion'   => 'Proveedores',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '201-01',
                        'descripcion' => 'Proveedores nacional',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '205',
                'descripcion'   => 'Acreedores diversos',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '205-01',
                        'descripcion' => 'Acreedores Diversos',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '208',
                'descripcion'   => 'Impuestos trasladados cobrados',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '208-01',
                        'descripcion' => 'IVA trasladado cobrado',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '209',
                'descripcion'   => 'Impuestos trasladados no cobrados',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '209-01',
                        'descripcion' => 'IVA trasladado no cobrado',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '210',
                'descripcion'   => 'Provisión de sueldos y salarios por pagar',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '210-01',
                        'descripcion' => 'Provisión de sueldos y salarios por pagar',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '211',
                'descripcion'   => 'Provision de contribuciones de seguridad social por pagar',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '211-01',
                        'descripcion' => 'Provisión de IMSS patronal por pagar',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '211-02',
                        'descripcion' => 'Provisión de SAR por pagar',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '211-03',
                        'descripcion' => 'Provisión de infonavit por pagar',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '212',
                'descripcion'   => 'Provision de impuesto estatal sobre nomina por pagar',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '212-01',
                        'descripcion' => 'Provisión de impuesto estatal sobre nómina por pagar',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '213',
                'descripcion'   => 'Impuestos y derechos por pagar',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '213-01',
                        'descripcion' => 'IVA por pagar',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '213-03',
                        'descripcion' => 'ISR por pagar',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '216',
                'descripcion'   => 'Impuestos retenidos',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '216-01',
                        'descripcion' => 'Impuestos retenidos de ISR por sueldos y salarios',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '216-02',
                        'descripcion' => 'Impuestos retenidos de ISR por asimilados a salarios',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '216-03',
                        'descripcion' => 'Impuestos retenidos de ISR Arrendamiento',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '216-04',
                        'descripcion' => 'Impuestos retenidos de ISR Servicios profesionales',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '216-10',
                        'descripcion' => 'Impuestos retenidos de IVA',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '216-11',
                        'descripcion' => 'Retenciones de IMSS a los trabajadores',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '301',
                'descripcion'   => 'Capital social',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '301-01',
                        'descripcion' => 'Capital fijo',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '301-02',
                        'descripcion' => 'Capital variable',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '301-03',
                        'descripcion' => 'Aportaciones para futuros aumentos de capital',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '304',
                'descripcion'   => 'Resultado de ejercicios anteriores',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '304-01',
                        'descripcion' => 'Utilidad de ejercicios anteriores',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '304-02',
                        'descripcion' => 'Pérdida de ejercicios anteriores',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '305',
                'descripcion'   => 'Resultado del ejercicio',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '305-01',
                        'descripcion' => 'Utilidad del ejercicio',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '305-02',
                        'descripcion' => 'Pérdida del ejercicio',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '401',
                'descripcion'   => 'Ingresos',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '401-01',
                        'descripcion' => 'Ventas y/o servicios gravados a la tasa general',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '401-04',
                        'descripcion' => 'Ventas y/o servicios gravados al 0%',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '401-07',
                        'descripcion' => 'Ventas y/o servicios exentos',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '402',
                'descripcion'   => 'Devoluciones descuentos o bonificaciones sobre ingresos',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '402-01',
                        'descripcion' => 'Devoluciones descuentos o bonificaciones sobre ingresos',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '501',
                'descripcion'   => 'Costo de venta y/o servicio',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '501-01',
                        'descripcion' => 'Costo de venta y/o servicio',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '503',
                'descripcion'   => 'Devoluciones descuentos o bonificaciones sobre compras',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '503-01',
                        'descripcion' => 'Devoluciones descuentos o bonificaciones sobre compras',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'resta', 'columna' => 'cargo'],
                            ['operacion' => 'suma', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '601',
                'descripcion'   => 'Gastos de Operación',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-01',
                        'descripcion' => 'Sueldos y salarios',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-03',
                        'descripcion' => 'Tiempos extras',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-06',
                        'descripcion' => 'Vacaciones',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-07',
                        'descripcion' => 'Prima vacacional',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-08',
                        'descripcion' => 'Prima dominical',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-09',
                        'descripcion' => 'Días festivos',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-10',
                        'descripcion' => 'Gratificaciones',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-11',
                        'descripcion' => 'Primas de antigüedad',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-12',
                        'descripcion' => 'Aguinaldo',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-13',
                        'descripcion' => 'Indemnizaciones',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-21',
                        'descripcion' => 'PTU',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-23',
                        'descripcion' => 'Previsión social',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-25',
                        'descripcion' => 'Otras prestaciones al personal',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-26',
                        'descripcion' => 'Cuotas al IMSS',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-27',
                        'descripcion' => 'Aportaciones al infonavit',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-28',
                        'descripcion' => 'Aportaciones al SAR',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-29',
                        'descripcion' => 'Contribuciones pagadas excepto ISR, IETU, IMPAC, IVA e IEPS',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-31',
                        'descripcion' => 'Asimilados a salarios',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-34',
                        'descripcion' => 'Honorarios pagados a personas físicas',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-45',
                        'descripcion' => 'Uso o goce temporal de bienes pagados a personas físicas',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-48',
                        'descripcion' => 'Gasolina y mantenimiento de transporte',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-49',
                        'descripcion' => 'Viáticos y gastos de viaje',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-57',
                        'descripcion' => 'Seguros y fianzas',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-72',
                        'descripcion' => 'Fletes y acarreos pagados a personas físicas',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-73',
                        'descripcion' => 'Intereses pagados sin ajuste alguno e intereses moratorios',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-74',
                        'descripcion' => 'Combustibles y lubricantes',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-75',
                        'descripcion' => 'Deducción por los pagos efectuados por el uso o goce temporal de automóviles',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-76',
                        'descripcion' => 'Consumo en restaurantes',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-77',
                        'descripcion' => 'Regalías y asistencia técnica',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-78',
                        'descripcion' => 'Pagos efectuados por el uso o goce temporal de automóviles cuya propulsión sea a través de baterias eléctricas recargables y automóviles eléctricos con motor de combustión interna',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-79',
                        'descripcion' => 'Impuesto local sobre ingresos por actividades empresariales',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-80',
                        'descripcion' => 'Maniobras, empaques y fletes en el campo para la enajenación de productos alimenticios',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-81',
                        'descripcion' => 'GASTOS NO DEDUCIBLES',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-82',
                        'descripcion' => 'Compra de mercancías',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-84',
                        'descripcion' => 'Otros gastos',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '601-89',
                        'descripcion' => 'ISR anual',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '613',
                'descripcion'   => 'Depreciación contable',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '613-01',
                        'descripcion' => 'Depreciación contable',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
            [
                'tipo'          => 'mayor',
                'numero_cuenta' => '614',
                'descripcion'   => 'Amortización contable',
                'auxiliares'    => [
                    [
                        'tipo' => 'auxiliar',
                        'numero_cuenta' => '614-01',
                        'descripcion' => 'Amortización contable',
                        'formula' => [
                            ['operacion' => 'suma', 'columna' => 'saldo_inicial'],
                            ['operacion' => 'suma', 'columna' => 'cargo'],
                            ['operacion' => 'resta', 'columna' => 'abono'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($lineas as $cuentaMayor) {
            $cuentaBalanza = BalanzaComprobacion::create([
                'tipo'          => $cuentaMayor['tipo'],
                'numero_cuenta' => $cuentaMayor['numero_cuenta'],
                'descripcion'   => $cuentaMayor['descripcion'],
            ]);
            foreach($cuentaMayor['auxiliares'] as $cuentaAuxiliar) {
                $cuentaBalanza->auxiliares()->create($cuentaAuxiliar);
            }
        }
    }
}
