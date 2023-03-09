<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Acciones\SaldosAFavor\Cuentas\SaldoFavorISRSueldosYSalarios;
use App\Contafacil\Compartido\Datos\SaldosAFavorDatos;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Contafacil\Facturas\ViewModels\CalculoDeIvaViewModel;
use App\Models\Cliente;
use App\Models\SaldoFavorAcreditamiento;
use Carbon\Carbon;

class ImpuestosFederalesViewModel extends ViewModel
{
    private $calculosIvaIsr = [];
    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha,
    ) {
        // TODO: refactorizar para usar saldos guardados del mes anterior
        // cuando el mes sea mayor a enero del año en curso
        $this->calculosIvaIsr = (new CalculoDeIvaViewModel($cliente, $fecha))->toArray();
        // if ($fecha->greaterThan(Carbon::create($fecha->year, 1, 1))) {
        //     $this->calculosIvaIsr = (
        //         new CalculoDeIvaViewModel($cliente, $fecha->copy()->subMonth())
        //     )->toArray();
        // }
    }

    public function cuentas(): array
    {
        $cuentas = collect();

        $cuentas[] = [
            'cuenta'      => '213-01',
            'clave'       => 'iva_por_pagar',
            'descripcion' => 'IVA por pagar',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_iva']['iva_del_periodo'] ?? 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '213-03',
            'clave'       => 'isr_por_pagar',
            'descripcion' => 'ISR por pagar',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-01',
            'clave'       => 'impuestos_retenidos_isr_sueldos',
            'descripcion' => 'Impuestos retenidos de ISR por sueldos y salarios',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_isr']['sueldos_salarios']['retenido'] ?? '',
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-02',
            'clave'       => 'impuestos_retenidos_isr_asimilados',
            'descripcion' => 'Impuestos retenidos de ISR por asimilados a salarios',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_isr']['asimilados_salario']['retenido'] ?? '',
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-03',
            'clave'       => 'impuestos_retenidos_isr_arrendamiento',
            'descripcion' => 'Impuestos retenidos de ISR Arrendamiento',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_isr']['arrendamiento']['retenido'] ?? '',
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-04',
            'clave'       => 'impuestos_retenidos_isr_servicios_profesionales',
            'descripcion' => 'Impuestos retenidos de ISR Servicios profesionales',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_isr']['servicios_profesionales']['retenido'] ?? '',
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-10',
            'clave'       => 'impuestos_retenidos_iva',
            'descripcion' => 'Impuestos retenidos de IVA',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_iva']['iva_retenciones']['retenido'] ?? '',
            'abono'       => 0,
        ];

        // TODO: hacer editable
        $cuentas[] = [
            'cuenta'      => '601-81',
            'clave'       => 'gastos_no_deducibles',
            'descripcion' => 'GASTOS NO DEDUCIBLE',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        // TODO: hacer editable
        $cuentas[] = [
            'cuenta'      => '601-84',
            'clave'       => 'otros_gastos',
            'descripcion' => 'Otros gastos',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '113-01-03',
            'clave'       => 'iva_a_favor',
            'descripcion' => 'IVA a favor',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $abonoIsrAFavor =
            ($this->calculosIvaIsr['calculos_isr']['sueldos_salarios']['a_favor'] ?? 0)
            + ($this->calculosIvaIsr['calculos_isr']['asimilados_salario']['a_favor'] ?? 0)
            + ($this->calculosIvaIsr['calculos_isr']['arrendamiento']['a_favor'] ?? 0)
            + ($this->calculosIvaIsr['calculos_isr']['servicios_profesionales']['a_favor'] ?? 0);
        $cuentas[] = [
            'cuenta'      => '113-02-03',
            'clave'       => 'isr_a_favor',
            'descripcion' => 'ISR a favor de Ejercicios anteriores',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => round($abonoIsrAFavor, 2),
        ];

        $cuentas[] = [
            'cuenta'      => '110-01',
            'clave'       => 'subsidio_al_empleo_por_aplicar',
            'descripcion' => 'Subsidio al empleo por aplicar',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cargos = $cuentas->where('columna', 'cargo')->sum('cargo');
        $abonos = $cuentas->where('columna', 'abono')->sum('abono');
        $cuentas[] = [
            'cuenta'      => '102-01',
            'clave'       => 'bancos_nacional',
            'descripcion' => 'Bancos nacional',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => round($cargos - $abonos, 2),
        ];

        return $cuentas->toArray();
    }
}
