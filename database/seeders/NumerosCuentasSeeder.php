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
            [
                'numero_cuenta'   => '601-84',
                'descripcion'     => 'Otros gastos',
                'automatico'      => false,
                'residual_cargo_abono' => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [],
            ],

            /*
             |------------------------------------------------------------------
             | Polizas automaticos - Ventas - Fecha Pago - Columna derecha
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
            [
                'numero_cuenta'   => '601-84',
                'descripcion'     => 'Otros gastos',
                'automatico'      => false,
                'residual_cargo_abono' => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_VENTAS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [],
            ],

            /*
             |------------------------------------------------------------------
             | Cuentas - Gastos - Emision
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

            /*
             |------------------------------------------------------------------
             | GASTOS: Números de cuenta manuales - Fecha emision
             |------------------------------------------------------------------
             | TODO: Cambiar las formulas y consideraciones adicionales
             */
            [
                'numero_cuenta'   => '601-34',
                'descripcion'     => 'Honorarios pagados a personas físicas',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-45',
                'descripcion'     => 'Uso o goce temporal de bienes pagados a personas físicas',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-48',
                'descripcion'     => 'Gasolina y mantenimiento de transporte',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-49',
                'descripcion'     => 'Viáticos y gastos de viaje',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-57',
                'descripcion'     => 'Seguros y fianzas',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-72',
                'descripcion'     => 'Fletes y acarreos pagados a personas físicas',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-73',
                'descripcion'     => 'Intereses pagados sin ajuste alguno e intereses moratorios',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-74',
                'descripcion'     => 'Combustibles y lubricantes',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-75',
                'descripcion'     => 'Deducción por los pagos efectuados por el uso o goce temporal de automóviles',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-76',
                'descripcion'     => 'Consumo en restaurantes',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-77',
                'descripcion'     => 'Regalías y asistencia técnica',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-78',
                'descripcion'     => 'Pagos efectuados por el uso o goce temporal de automóviles cuya propulsión sea a través de baterias eléctricas recargables y automóviles eléctricos con motor de combustión interna',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-79',
                'descripcion'     => 'Impuesto local sobre ingresos por actividades empresariales',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-80',
                'descripcion'     => 'Maniobras, empaques y fletes en el campo para la enajenación de productos alimenticios',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-81',
                'descripcion'     => 'GASTOS NO DEDUCIBLES',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'deducible'       => false,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'total'],
                ],
                'exclusiones'     => [
                    [
                        'numero_cuenta' => '119-01',
                        'automatico'    => true,
                        'tipo_cuenta'   => NumeroCuenta::TIPO_POLIZA_GASTOS,
                        'subtipo'       => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                    ]
                ],
            ],
            [
                'numero_cuenta'   => '601-82',
                'descripcion'     => 'Compra de mercancías',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-84',
                'descripcion'     => 'Otros gastos',
                'automatico'      => false,
                'residual_cargo_abono' => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '119-01',
                'descripcion'     => 'IVA Acreditable Pendiente de Pago',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'total'],
                ],
            ],
            [
                'numero_cuenta'   => '601-84',
                'descripcion'     => 'Otros Impuestos y Derechos',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'traslado_ieps'],
                    ['operacion' => 'suma', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '115-01',
                'descripcion'     => 'Inventario',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '150-01',
                'descripcion'     => 'Terrenos',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '150-02',
                'descripcion'     => 'Construcciones',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '150-03',
                'descripcion'     => 'Maquinaría y equipo',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '150-04',
                'descripcion'     => 'Mobiliario y equipo de oficina',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '150-05',
                'descripcion'     => 'Equipo de transporte',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '150-06',
                'descripcion'     => 'Otros activos fijos',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '150-07',
                'descripcion'     => 'Cargos y gastos diferidos',
                'automatico'      => false,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_EMISION,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [
                    ['operacion' => 'suma', 'clave_monto' => 'subtotal'],
                    ['operacion' => 'resta', 'clave_monto' => 'otros_impuestos'],
                ],
            ],
            [
                'numero_cuenta'   => '601-84',
                'descripcion'     => 'Otros gastos',
                'automatico'      => false,
                'residual_cargo_abono' => true,
                'tipo_cuenta'     => NumeroCuenta::TIPO_POLIZA_GASTOS,
                'subtipo'         => NumeroCuenta::SUBTIPO_FECHA_PAGO,
                'columna_calculo' => NumeroCuenta::COLUMNA_CALCULO_CARGO,
                'formula'         => [],
            ],
        ];

        foreach ($cuentas as $cuenta) {
            NumeroCuenta::create($cuenta);
        }
    }
}
