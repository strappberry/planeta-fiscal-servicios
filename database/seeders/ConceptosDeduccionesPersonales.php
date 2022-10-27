<?php

namespace Database\Seeders;

use App\Models\ConceptoDeduccionPersonal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConceptosDeduccionesPersonales extends Seeder
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
                'concepto' => 'Honorarios médicos, dentales y hospitalarios',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 2,
                'concepto' => 'Gastos funerales',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 3,
                'concepto' => 'Intereses reales',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 4,
                'concepto' => 'Primas por seguros de gastos médicos',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 5,
                'concepto' => 'Transportación escolar',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 6,
                'concepto' => 'Gastos médicos por incapacidad o discapacidad',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 7,
                'concepto' => 'Gastos médicos (lentes)',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 8,
                'concepto' => 'Donativos',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 9,
                'concepto' => 'Aportaciones complementarias',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 10,
                'concepto' => 'Depósitos en cuentas especiales para el ahorro',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 11,
                'concepto' => 'Preescolar',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 12,
                'concepto' => 'Primaria',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 13,
                'concepto' => 'Secundaria',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 14,
                'concepto' => 'Profesional técnico',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
            [
                'id' => 15,
                'concepto' => 'Bachillerato o equivalente',
                'tipo_factura' => ConceptoDeduccionPersonal::TIPO_GASTO,
            ],
        ];

        foreach($conceptos as $concepto) {
            ConceptoDeduccionPersonal::create($concepto);
        }
    }
}
