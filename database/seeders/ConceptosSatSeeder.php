<?php

namespace Database\Seeders;

use App\Models\ConceptoSat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConceptosSatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conceptos = [
            [
                'id' => 1,
                'concepto' => 'Otros Gastos',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 2,
                'concepto' => 'Gasolina y mantenimiento de transporte',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 3,
                'concepto' => 'Viáticos y gastos de viaje',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 4,
                'concepto' => 'Honorarios pagados a personas físicas',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 5,
                'concepto' => 'Uso o goce temporal de bienes pagados a personas físicas',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 6,
                'concepto' => 'Seguros y finanzas',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 7,
                'concepto' => 'Fletes y acarreos pagados a personas físicas',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 8,
                'concepto' => 'Intereses pagados sin ajuste alguno e intereses moratorios',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 9,
                'concepto' => 'Combustibles y lubricantes',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 10,
                'concepto' => 'Compra de mercancías',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 11,
                'concepto' => 'Deducción por los pagos efectuados por el uso de goces temporales de automóviles',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 12,
                'concepto' => 'Consumo en restaurantes',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 13,
                'concepto' => 'Regalías y asistencia técnica',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 14,
                'concepto' => 'Pagos efectuados por el uso o goce temporal de automóviles cuya propulsión sea a través de baterias eléctricas recargables y automóviles eléctricos con motor de combustión interna',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 15,
                'concepto' => 'Impuesto local sobre ingresos por actividades empresariales',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 16,
                'concepto' => 'Maniobras, empaques y fletes en el campo para la enajenación de productos alimenticios',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 17,
                'concepto' => 'Inventario',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 18,
                'concepto' => 'Terrenos',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 19,
                'concepto' => 'Construcciones',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 20,
                'concepto' => 'Maquinaria y equipo',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 21,
                'concepto' => 'Mobiliario y equipo de oficina',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 22,
                'concepto' => 'Equipo de transporte',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 23,
                'concepto' => 'Otros activos fijos',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 24,
                'concepto' => 'Cargos y activos fijos',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 25,
                'concepto' => 'Cargos y gastos diferidos',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 26,
                'concepto' => 'Deducciones personales',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
            [
                'id' => 27,
                'concepto' => 'Gastos no deducibles',
                'tipo_factura' => ConceptoSat::TIPO_GASTO,
            ],
        ];

        foreach ($conceptos as $concepto) {
            ConceptoSat::create($concepto);
        }
    }
}
