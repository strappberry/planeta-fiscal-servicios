<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Acciones\BalanzaComprobacion\ResolverDeterminacionImpuestosDB;
use App\Acciones\CamposEditables\ImpuestoFederalGastosNoDeducibleAccion;
use App\Acciones\CamposEditables\ImpuestoFederalOtrosGastosAccion;
use App\Acciones\SaldosAFavor\SaldosFavorOriginadosEnSubsidioAlEmpleoPorAplicar;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use Carbon\Carbon;

class ImpuestosFederalesViewModel extends ViewModel
{
    private $calculosIvaIsr = [];
    private $impuestosFederales = [];
    private $isrActividad   = 0;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha,
    ) {
        $determinacionImpuesto = ResolverDeterminacionImpuestosDB::ejecutar(
            $cliente,
            $fecha->copy()->subMonth()->startOfMonth()
        );

        $this->calculosIvaIsr = $determinacionImpuesto ? $determinacionImpuesto->calculos_iva_isr : [];
        $this->isrActividad   = $determinacionImpuesto ? $determinacionImpuesto->isr_actividad : 0;
        $this->impuestosFederales = $determinacionImpuesto ? $determinacionImpuesto->impuestos_federales : [];
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
            'cargo'       => $this->isrActividad,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-01',
            'clave'       => 'impuestos_retenidos_isr_sueldos',
            'descripcion' => 'Impuestos retenidos de ISR por sueldos y salarios',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_isr']['sueldos_salarios']['retenido'] ?? 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-02',
            'clave'       => 'impuestos_retenidos_isr_asimilados',
            'descripcion' => 'Impuestos retenidos de ISR por asimilados a salarios',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_isr']['asimilados_salario']['retenido'] ?? 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-03',
            'clave'       => 'impuestos_retenidos_isr_arrendamiento',
            'descripcion' => 'Impuestos retenidos de ISR Arrendamiento',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_isr']['arrendamiento']['retenido'] ?? 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-04',
            'clave'       => 'impuestos_retenidos_isr_servicios_profesionales',
            'descripcion' => 'Impuestos retenidos de ISR Servicios profesionales',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_isr']['servicios_profesionales']['retenido'] ?? 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-10',
            'clave'       => 'impuestos_retenidos_iva',
            'descripcion' => 'Impuestos retenidos de IVA',
            'columna'     => 'cargo',
            'cargo'       => $this->calculosIvaIsr['calculos_iva']['iva_retenciones']['retenido'] ?? 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '601-81',
            'clave'       => 'gastos_no_deducibles',
            'descripcion' => 'GASTOS NO DEDUCIBLE',
            'columna'     => 'cargo',
            'cargo'       => ImpuestoFederalGastosNoDeducibleAccion::resolver($this->cliente, $this->fecha),
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '601-84',
            'clave'       => 'otros_gastos',
            'descripcion' => 'Otros gastos',
            'columna'     => 'cargo',
            'cargo'       => ImpuestoFederalOtrosGastosAccion::resolver($this->cliente, $this->fecha),
            'abono'       => 0,
        ];

        // TODO: Debe estar condicionado a que el impuesto diga Pago de lo indebido de IVA y de pago de lo indebido de retención de IVA
        $ivaAFavor = ($this->calculosIvaIsr['calculos_iva']['iva_retenciones']['a_favor'] ?? 0)
            + ($this->calculosIvaIsr['calculos_iva']['acreditamiento_saldo_favor_iva'] ?? 0);
        $cuentas[] = [
            'cuenta'      => '113-01-03',
            'clave'       => 'iva_a_favor',
            'descripcion' => 'IVA a favor',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => $ivaAFavor,
        ];

        $subsidioPorAplicar = $this->impuestosFederales['saldos_favor_origen']['subsidio_empleo_por_aplicar'] ?? 0;
        // SaldosFavorOriginadosEnSubsidioAlEmpleoPorAplicar::ejecutar($this->cliente, $this->fecha);

        $abonoIsrAFavor =
            ($this->calculosIvaIsr['calculos_isr']['sueldos_salarios']['a_favor'] ?? 0)
            + ($this->calculosIvaIsr['calculos_isr']['asimilados_salario']['a_favor'] ?? 0)
            + ($this->calculosIvaIsr['calculos_isr']['arrendamiento']['a_favor'] ?? 0)
            + ($this->calculosIvaIsr['calculos_isr']['servicios_profesionales']['a_favor'] ?? 0);
        $abonoIsrAFavor = ($abonoIsrAFavor > 0) ? ($abonoIsrAFavor - $subsidioPorAplicar) : 0;

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
            'abono'       => $subsidioPorAplicar,
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

    public function saldosFavorOrigen(): array
    {
        return [
            'subsidio_empleo_por_aplicar' =>
                SaldosFavorOriginadosEnSubsidioAlEmpleoPorAplicar::ejecutar($this->cliente, $this->fecha),
        ];
    }
}
