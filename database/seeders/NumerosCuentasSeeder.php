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
            [
                'numero_cuenta' => '601-34',
                'descripcion'   => 'Honorarios pagados a personas físicas',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-45',
                'descripcion'   => 'Uso o goce temporal de bienes pagados a personas físicas',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-48',
                'descripcion'   => 'Gasolina y mantenimiento de transporte',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-49',
                'descripcion'   => 'Viáticos y gastos de viaje',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-57',
                'descripcion'   => 'Seguros y fianzas',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-72',
                'descripcion'   => 'Fletes y acarreos pagados a personas físicas',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-73',
                'descripcion'   => 'Intereses pagados sin ajuste alguno e intereses moratorios',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-74',
                'descripcion'   => 'Combustibles y lubricantes',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-75',
                'descripcion'   => 'Deducción por los pagos efectuados por el uso o goce temporal de automóviles',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-76',
                'descripcion'   => 'Consumo en restaurantes',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-77',
                'descripcion'   => 'Regalías y asistencia técnica',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-78',
                'descripcion'   => 'Pagos efectuados por el uso o goce temporal de automóviles cuya propulsión sea a través de baterias eléctricas recargables y automóviles eléctricos con motor de combustión interna',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-80',
                'descripcion'   => 'Maniobras, empaques y fletes en el campo para la enajenación de productos alimenticios',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-81',
                'descripcion'   => 'GASTOS NO DEDUCIBLES',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-82',
                'descripcion'   => 'Compra de mercancías',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-84',
                'descripcion'   => 'Otros gastos',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '601-84',
                'descripcion'   => 'Otros Impuestos y Derechos',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '115-01',
                'descripcion'   => 'Inventario',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '150-01',
                'descripcion'   => 'Terrenos',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '150-02',
                'descripcion'   => 'Construcciones',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '150-03',
                'descripcion'   => 'Maquinaría y equipo',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '150-04',
                'descripcion'   => 'Mobiliario y equipo de oficina',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '150-05',
                'descripcion'   => 'Equipo de transporte',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '150-06',
                'descripcion'   => 'Otros activos fijos',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '150-07',
                'descripcion'   => 'Cargos y gastos diferidos',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => true,
            ],
            [
                'numero_cuenta' => '201-01',
                'descripcion'   => 'Proveedores',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => false,
            ],
            [
                'numero_cuenta' => '503-01',
                'descripcion'   => 'Devoluciones, Descuentos o Bonificaciones Sobre Compras',
                'tipo_cuenta'   => NumeroCuenta::TIPO_GASTO,
                'poliza'        => false,
                'cargo'         => false,
            ],
            [
                'numero_cuenta' => '400-01',
                'descripcion'   => 'Ventas y/o servicios gravados a la tasa general',
                'tipo_cuenta'   => NumeroCuenta::TIPO_VENTA,
                'poliza'        => false,
                'cargo'         => false,
            ],
            [
                'numero_cuenta' => '401-04',
                'descripcion'   => 'Ventas y/o servicios gravados a la tasa cero',
                'tipo_cuenta'   => NumeroCuenta::TIPO_VENTA,
                'poliza'        => false,
                'cargo'         => false,
            ],
            [
                'numero_cuenta' => '401-07',
                'descripcion'   => 'Ventas y/o servicios exentos',
                'tipo_cuenta'   => NumeroCuenta::TIPO_VENTA,
                'poliza'        => false,
                'cargo'         => false,
            ],
            [
                'numero_cuenta' => '401-01',
                'descripcion'   => 'Ventas tasa General',
                'tipo_cuenta'   => NumeroCuenta::TIPO_POLIZA,
                'poliza'        => true,
                'cargo'         => true,
                'subtipo'       => NumeroCuenta::SUBTIPO_POLIZA_VENTA,
            ],
            [
                'numero_cuenta' => '401-04',
                'descripcion'   => 'Tasa 0',
                'tipo_cuenta'   => NumeroCuenta::TIPO_POLIZA,
                'poliza'        => true,
                'cargo'         => true,
                'subtipo'       => NumeroCuenta::SUBTIPO_POLIZA_VENTA,
            ],
            [
                'numero_cuenta' => '401-07',
                'descripcion'   => 'Exentos',
                'tipo_cuenta'   => NumeroCuenta::TIPO_POLIZA,
                'poliza'        => true,
                'cargo'         => true,
                'subtipo'       => NumeroCuenta::SUBTIPO_POLIZA_VENTA,
            ],
            [
                'numero_cuenta' => '105-01',
                'descripcion'   => 'Caja y bancos',
                'tipo_cuenta'   => NumeroCuenta::TIPO_POLIZA,
                'poliza'        => true,
                'cargo'         => true,
                'subtipo'       => NumeroCuenta::SUBTIPO_POLIZA_GASTO,
            ],
            [
                'numero_cuenta' => '106-01',
                'descripcion'   => 'Deudores',
                'tipo_cuenta'   => NumeroCuenta::TIPO_POLIZA,
                'poliza'        => true,
                'cargo'         => true,
                'subtipo'       => NumeroCuenta::SUBTIPO_POLIZA_GASTO,
            ],
            [
                'numero_cuenta' => '205-01',
                'descripcion'   => 'Acreedores',
                'tipo_cuenta'   => NumeroCuenta::TIPO_POLIZA,
                'poliza'        => true,
                'cargo'         => true,
                'subtipo'       => NumeroCuenta::SUBTIPO_POLIZA_GASTO,
            ],
        ];

        foreach ($cuentas as $cuenta) {
            NumeroCuenta::create($cuenta);
        }
    }
}
