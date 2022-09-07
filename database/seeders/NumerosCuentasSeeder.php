<?php

namespace Database\Seeders;

use App\Models\NumeroCuenta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NumerosCuentasSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $cuentas = [
            /*
             |------------------------------------------------------------------
             | Polizas automaticos - Ventas - Fecha Emisión - Columna izquierda
             |------------------------------------------------------------------
             */
            [
                'numero_cuenta'   => '105-01',
                'descripcion'     => 'Clientes',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'total'],
                ],
            ],
            [
                'numero_cuenta'   => '401-01',
                'descripcion'     => 'Ventas y/o servicios gravados a la tasa general',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslado_iva_sobre_dieciseis'],
                ],
            ],
            [
                'numero_cuenta'   => '401-04',
                'descripcion'     => 'Ventas y/o servicios gravados al 0%',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'tasa_cero'],
                ],
            ],
            [
                'numero_cuenta'   => '401-07',
                'descripcion'     => 'Ventas y/o servicios exentos',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslados_exentos'],
                ],
            ],
            [
                'numero_cuenta'   => '402-01',
                'descripcion'     => 'Devoluciones descuentos o bonificaciones sobre ingresos',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'descuento'],
                ],
            ],
            [
                'numero_cuenta'   => '209-01',
                'descripcion'     => 'IVA Trasladado No Cobrado',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslado_iva'],
                ],
            ],
            [
                'numero_cuenta'   => '113-01-01',
                'descripcion'     => 'Retencion de IVA (ingresos No cobrados)',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'retencion_iva'],
                ],
            ],
            [
                'numero_cuenta'   => '113-02-01',
                'descripcion'     => 'Retencion de ISR (Ingresos No Cobrados)',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'retencion_isr'],
                ],
            ],

            /*
             |------------------------------------------------------------------
             | Polizas automaticos - Gastos - Fecha Pago - Columna derecha
             |------------------------------------------------------------------
             */
            [
                'numero_cuenta'   => '102-01',
                'descripcion'     => 'Efectivo en caja y depósitos en instituciones de crédito',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'total'],
                ],
            ],
            [
                'numero_cuenta'   => '105-01',
                'descripcion'     => 'Clientes',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'total'],
                ],
            ],
            [
                'numero_cuenta'   => '209-01',
                'descripcion'     => 'IVA Trasladado No Cobrado',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslado_iva'],
                ],
            ],
            [
                'numero_cuenta'   => '208-01',
                'descripcion'     => 'IVA trasladado cobrado',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslado_iva'],
                ],
            ],
            [
                'numero_cuenta'   => '113-01-01',
                'descripcion'     => 'Retencion de IVA (ingresos No cobrados)',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'retencion_iva'],
                ],
            ],
            [
                'numero_cuenta'   => '113-01-02',
                'descripcion'     => 'Retencion de IVA (ingresos cobrados)',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'retencion_iva'],
                ],
            ],
            [
                'numero_cuenta'   => '113-02-01',
                'descripcion'     => 'Retencion de ISR (Ingresos No Cobrados)',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'retencion_isr'],
                ],
            ],
            [
                'numero_cuenta'   => '113-02-02',
                'descripcion'     => 'Retencion de ISR (Ingresos Cobrados)',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'retencion_isr'],
                ],
            ],

            /*
             |------------------------------------------------------------------
             | Balanza comprobacion automatico - Gastos
             |------------------------------------------------------------------
             */
            [
                'numero_cuenta'   => '119-01',
                'descripcion'     => 'IVA Acreditable Pendiente de Pago',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslado_iva'],
                ],
            ],
            [
                'numero_cuenta'   => '601-84',
                'descripcion'     => 'Otros Impuestos y Derechos',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslado_ieps'],
                    ['operacion' => 'suma', 'clave_monto' => 'otros_impuestos'],
                ],
            ],

            /*
             |------------------------------------------------------------------
             | Balanza comprobacion automatico - Gastos
             |------------------------------------------------------------------
             */
            [
                'numero_cuenta'   => '216-10',
                'descripcion'     => 'Impuestos retenidos de IVA',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'retencion_isr'],
                ],
            ],
            [
                'numero_cuenta'   => '201-01',
                'descripcion'     => 'Proveedores',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'total'],
                ],
            ],
            [
                'numero_cuenta'   => '503-01',
                'descripcion'     => 'Devoluciones, Descuentos o Bonificaciones Sobre Compras',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'descuento'],
                ],
            ],
            // -----------------------------------

            [
                'numero_cuenta'   => '201-01',
                'descripcion'     => 'Proveedores',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'total'],
                ],
            ],
            [
                'numero_cuenta'   => '102-01',
                'descripcion'     => 'Efectivo en caja y depósitos en instituciones de crédito',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'total'],
                ],
            ],
            [
                'numero_cuenta'   => '118-01',
                'descripcion'     => 'IVA Acreditable Pagado',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslado_iva'],
                ],
            ],
            [
                'numero_cuenta'   => '119-01',
                'descripcion'     => 'IVA Acreditable Pendiente de Pago',
                'automatico'      => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_ABONO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslado_iva'],
                ],
            ],
        ];

        foreach ($cuentas as $cuenta) {
            NumeroCuenta::create($cuenta);
        }
    }
}
