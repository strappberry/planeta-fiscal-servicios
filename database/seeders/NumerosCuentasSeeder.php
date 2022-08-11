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
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-45',
                'descripcion'   => 'Uso o goce temporal de bienes pagados a personas físicas',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-48',
                'descripcion'   => 'Gasolina y mantenimiento de transporte',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-49',
                'descripcion'   => 'Viáticos y gastos de viaje',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-57',
                'descripcion'   => 'Seguros y fianzas',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-72',
                'descripcion'   => 'Fletes y acarreos pagados a personas físicas',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-73',
                'descripcion'   => 'Intereses pagados sin ajuste alguno e intereses moratorios',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-74',
                'descripcion'   => 'Combustibles y lubricantes',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-75',
                'descripcion'   => 'Deducción por los pagos efectuados por el uso o goce temporal de automóviles',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-76',
                'descripcion'   => 'Consumo en restaurantes',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-77',
                'descripcion'   => 'Regalías y asistencia técnica',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-78',
                'descripcion'   => 'Pagos efectuados por el uso o goce temporal de automóviles cuya propulsión sea a través de baterias eléctricas recargables y automóviles eléctricos con motor de combustión interna',
                'ventas'        => false,
                'gastos'        => true,
            ],
            // [
            //     'numero_cuenta' => '601-79',
            //     'descripcion'   => 'Impuesto local sobre ingresos por actividades empresariales',
            //     'ventas'        => false,
            //     'gastos'        => true,
            // ],
            [
                'numero_cuenta' => '601-80',
                'descripcion'   => 'Maniobras, empaques y fletes en el campo para la enajenación de productos alimenticios',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-81',
                'descripcion'   => 'GASTOS NO DEDUCIBLES',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-82',
                'descripcion'   => 'Compra de mercancías',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '601-84',
                'descripcion'   => 'Otros gastos',
                'ventas'        => false,
                'gastos'        => true,
            ],
            // [
            //     'numero_cuenta' => '119-01',
            //     'descripcion'   => 'IVA Acreditable Pendiente de Pago',
            //     'ventas'        => false,
            //     'gastos'        => true,
            // ],
            [
                'numero_cuenta' => '601-84',
                'descripcion'   => 'Otros Impuestos y Derechos',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '115-01',
                'descripcion'   => 'Inventario',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '150-01',
                'descripcion'   => 'Terrenos',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '150-02',
                'descripcion'   => 'Construcciones',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '150-03',
                'descripcion'   => 'Maquinaría y equipo',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '150-04',
                'descripcion'   => 'Mobiliario y equipo de oficina',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '150-05',
                'descripcion'   => 'Equipo de transporte',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '150-06',
                'descripcion'   => 'Otros activos fijos',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '150-07',
                'descripcion'   => 'Cargos y gastos diferidos',
                'ventas'        => false,
                'gastos'        => true,
            ],
            // [
            //     'numero_cuenta' => '216-03',
            //     'descripcion'   => 'Impuestos retenidos de ISR Arrendamiento',
            //     'ventas'        => false,
            //     'gastos'        => true,
            // ],
            // [
            //     'numero_cuenta' => '216-04',
            //     'descripcion'   => 'Impuestos retenidos de ISR Servicios profesionales',
            //     'ventas'        => false,
            //     'gastos'        => true,
            // ],
            // [
            //     'numero_cuenta' => '216-10',
            //     'descripcion'   => 'Impuestos retenidos de IVA',
            //     'ventas'        => false,
            //     'gastos'        => true,
            // ],
            [
                'numero_cuenta' => '201-01',
                'descripcion'   => 'Proveedores',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '503-01',
                'descripcion'   => 'Devoluciones, Descuentos o Bonificaciones Sobre Compras',
                'ventas'        => false,
                'gastos'        => true,
            ],
            [
                'numero_cuenta' => '400-01',
                'descripcion'   => 'Ventas y/o servicios gravados a la tasa general',
                'ventas'        => true,
                'gastos'        => false,
            ],
            [
                'numero_cuenta' => '401-04',
                'descripcion'   => 'Ventas y/o servicios gravados a la tasa general',
                'ventas'        => true,
                'gastos'        => false,
            ],
            [
                'numero_cuenta' => '401-07',
                'descripcion'   => 'Ventas y/o servicios exentos',
                'ventas'        => true,
                'gastos'        => false,
            ],
        ];

        foreach ($cuentas as $cuenta) {
            NumeroCuenta::create($cuenta);
        }
    }
}
