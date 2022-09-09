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
                'tipo' => 'auxiliar',
                'numero_cuenta' => '102-01',
                'descripcion' => 'Efectivo en caja y depósitos en instituciones de crédito',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '103-01',
                'descripcion' => 'Inversiones',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '105-01',
                'descripcion' => 'Clientes',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '107-01',
                'descripcion' => 'Deudores diversos',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '110-01',
                'descripcion' => 'Subsidio al empleo por aplicar',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-01',
                'descripcion' => 'IVA a favor',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-01-01',
                'descripcion' => 'Retencion de IVA (ingresos No cobrados)',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-01-02',
                'descripcion' => 'Retencion de IVA (ingresos cobrados)',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-01-03',
                'descripcion' => 'IVA a favor',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-02',
                'descripcion' => 'ISR a favor',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-02-01',
                'descripcion' => 'Retencion de ISR (Ingresos No Cobrados)',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-02-02',
                'descripcion' => 'Retencion de ISR (Ingresos Cobrados)',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-02-03',
                'descripcion' => 'ISR a favor de Ejercicios anteriores',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-02-04',
                'descripcion' => 'ISR a favor del Ejercicio',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '113-02-05',
                'descripcion' => 'ISR a favor pago de lo indebido',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '114-01',
                'descripcion' => 'Pagos provisionales de ISR',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '115-01',
                'descripcion' => 'Inventario',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '118-01',
                'descripcion' => 'IVA Acreditable pagado',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '119-01',
                'descripcion' => 'IVA Acreditable por pagar',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '150-01',
                'descripcion' => 'Terrenos',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '150-02',
                'descripcion' => 'Construcciones',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '150-03',
                'descripcion' => 'Maquinaría y equipo',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '150-04',
                'descripcion' => 'Mobiliario y equipo de oficina',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '150-05',
                'descripcion' => 'Equipo de transporte',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '150-06',
                'descripcion' => 'Otros activos fijos',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '150-07',
                'descripcion' => 'Cargos y gastos diferidos',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '151-01',
                'descripcion' => 'Depreciación acumulada',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '152-01',
                'descripcion' => 'Amortización acumulada',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '201-01',
                'descripcion' => 'Proveedores nacional',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '205-01',
                'descripcion' => 'Acreedores Diversos',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '208-01',
                'descripcion' => 'IVA trasladado cobrado',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '209-01',
                'descripcion' => 'IVA trasladado no cobrado',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '210-01',
                'descripcion' => 'Provisión de sueldos y salarios por pagar',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '211-01',
                'descripcion' => 'Provisión de IMSS patronal por pagar',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '211-02',
                'descripcion' => 'Provisión de SAR por pagar',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '211-03',
                'descripcion' => 'Provisión de infonavit por pagar',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '212-01',
                'descripcion' => 'Provisión de impuesto estatal sobre nómina por pagar',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '213-01',
                'descripcion' => 'IVA por pagar',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '213-03',
                'descripcion' => 'ISR por pagar',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '216-01',
                'descripcion' => 'Impuestos retenidos de ISR por sueldos y salarios',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '216-02',
                'descripcion' => 'Impuestos retenidos de ISR por asimilados a salarios',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '216-03',
                'descripcion' => 'Impuestos retenidos de ISR Arrendamiento',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '216-04',
                'descripcion' => 'Impuestos retenidos de ISR Servicios profesionales',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '216-10',
                'descripcion' => 'Impuestos retenidos de IVA',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '216-11',
                'descripcion' => 'Retenciones de IMSS a los trabajadores',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '301-01',
                'descripcion' => 'Capital fijo',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '301-02',
                'descripcion' => 'Capital variable',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '301-03',
                'descripcion' => 'Aportaciones para futuros aumentos de capital',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '304-01',
                'descripcion' => 'Utilidad de ejercicios anteriores',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '304-02',
                'descripcion' => 'Pérdida de ejercicios anteriores',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '305-01',
                'descripcion' => 'Utilidad del ejercicio',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '305-02',
                'descripcion' => 'Pérdida del ejercicio',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '401-01',
                'descripcion' => 'Ventas y/o servicios gravados a la tasa general',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '401-04',
                'descripcion' => 'Ventas y/o servicios gravados al 0%',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '401-07',
                'descripcion' => 'Ventas y/o servicios exentos',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '402-01',
                'descripcion' => 'Devoluciones descuentos o bonificaciones sobre ingresos',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '501-01',
                'descripcion' => 'Costo de venta y/o servicio',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '503-01',
                'descripcion' => 'Devoluciones descuentos o bonificaciones sobre compras',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-01',
                'descripcion' => 'Sueldos y salarios ',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-03',
                'descripcion' => 'Tiempos extras ',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-06',
                'descripcion' => 'Vacaciones',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-07',
                'descripcion' => 'Prima vacacional ',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-08',
                'descripcion' => 'Prima dominical',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-09',
                'descripcion' => 'Días festivos',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-10',
                'descripcion' => 'Gratificaciones',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-11',
                'descripcion' => 'Primas de antigüedad',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-12',
                'descripcion' => 'Aguinaldo',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-13',
                'descripcion' => 'Indemnizaciones',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-21',
                'descripcion' => 'PTU',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-23',
                'descripcion' => 'Previsión social',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-25',
                'descripcion' => 'Otras prestaciones al personal',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-26',
                'descripcion' => 'Cuotas al IMSS',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-27',
                'descripcion' => 'Aportaciones al infonavit',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-28',
                'descripcion' => 'Aportaciones al SAR',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-29',
                'descripcion' => 'Contribuciones pagadas excepto ISR, IETU, IMPAC, IVA e IEPS',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-31',
                'descripcion' => 'Asimilados a salarios',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-34',
                'descripcion' => 'Honorarios pagados a personas físicas',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-45',
                'descripcion' => 'Uso o goce temporal de bienes pagados a personas físicas',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-48',
                'descripcion' => 'Gasolina y mantenimiento de transporte',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-49',
                'descripcion' => 'Viáticos y gastos de viaje',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-57',
                'descripcion' => 'Seguros y fianzas',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-72',
                'descripcion' => 'Fletes y acarreos pagados a personas físicas',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-73',
                'descripcion' => 'Intereses pagados sin ajuste alguno e intereses moratorios',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-74',
                'descripcion' => 'Combustibles y lubricantes',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-75',
                'descripcion' => 'Deducción por los pagos efectuados por el uso o goce temporal de automóviles',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-76',
                'descripcion' => 'Consumo en restaurantes',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-77',
                'descripcion' => 'Regalías y asistencia técnica',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-78',
                'descripcion' => 'Pagos efectuados por el uso o goce temporal de automóviles cuya propulsión sea a través de baterias eléctricas recargables y automóviles eléctricos con motor de combustión interna',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-79',
                'descripcion' => 'Impuesto local sobre ingresos por actividades empresariales',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-80',
                'descripcion' => 'Maniobras, empaques y fletes en el campo para la enajenación de productos alimenticios',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-81',
                'descripcion' => 'GASTOS NO DEDUCIBLES',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-82',
                'descripcion' => 'Compra de mercancías',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-84',
                'descripcion' => 'Otros gastos',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '601-89',
                'descripcion' => 'ISR anual',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '613-01',
                'descripcion' => 'Depreciación contable',
            ],
            [
                'tipo' => 'auxiliar',
                'numero_cuenta' => '614-01',
                'descripcion' => 'Amortización contable',
            ],
        ];

        foreach ($lineas as $linea) {
            BalanzaComprobacion::create($linea);
        }
    }
}
