<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Contafacil\Compartido\Contratos\DebeTenerBaseMaxima;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\DeterminacionImpuestosEnum;
use App\Models\Cliente;
use App\Models\DeterminacionImpuesto;
use Carbon\Carbon;

class DeterminacionDelImpuestoPersonaMoralViewModel extends ViewModel implements DebeTenerBaseMaxima
{
    private $ventasCobradas;
    private $determinacionPasada;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha,
        private array $camposEditables = [],
    ) {
        $this->ventasCobradas = $this->cliente->facturasCliente()
            ->with('factura')
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->esVenta()
            ->esConsiderado()
            ->get();

        $this->determinacionPasada = $cliente->determinacionDelImpuesto()
                ->where('mes_trabajo', $this->fecha->copy()->subMonth()->format('Y-m-d'))
                ->first();
    }

    public function ingresos(): float
    {
        return $this->ventasCobradas->calcularIngresos(0);
    }

    public function ingresosAcumulados(): float
    {
        if ($this->fecha->month === 1) {
            return $this->ingresos();
        }

        $ingresosAnteriores = ($this->determinacionPasada) ? $this->determinacionPasada->ingresos_acumulados : 0;
        return round($ingresosAnteriores + $this->ingresos(), 0);
    }

    public function coeficienteUtilidad(): float
    {
        $coeficienteUtilidad = $this->camposEditables[
            DeterminacionImpuestosEnum::CAMPO_COEFICIENTE_UTILIDAD
        ] ?? 0;

        if ($coeficienteUtilidad != 0) return $coeficienteUtilidad;

        $determinacionCoeficienteUtilidad = DeterminacionImpuesto::query()
            ->select(DeterminacionImpuesto::COEFICIENTE_UTILIDAD)
            ->buscarUltimoMesConCoeficienteUtilidad($this->cliente, $this->fecha)
            ->first();

        if (!$determinacionCoeficienteUtilidad) return 0;

        return $determinacionCoeficienteUtilidad->{DeterminacionImpuesto::COEFICIENTE_UTILIDAD};
    }

    public function utilidadFiscalPagoProvisional(): float
    {
        return round(
            $this->ingresosAcumulados() * $this->coeficienteUtilidad(),
            0
        );
    }

    public function ptu(): float
    {
        return 0;
    }

    public function anticiposRendimientoDistribuidosEnPeriodo(): float
    {
        return $this->camposEditables[
            DeterminacionImpuestosEnum::CAMPO_ANTICIPOS_RENDIMIENTOS_DISTRIBUIDOS_EN_PERIODO
        ] ?? 0;
    }

    public function perdidasFiscalesEjerciciosAnteriores(): float
    {
        return $this->camposEditables[
            DeterminacionImpuestosEnum::CAMPO_PERDIDA_EJERCICIOS_ANTERIORES
        ] ?? 0;
    }

    public function baseMaxima(): float
    {
        return $this->utilidadFiscalPagoProvisional()
            - $this->ptu()
            - $this->anticiposRendimientoDistribuidosEnPeriodo()
            ;
    }

    public function base(): float
    {
        return $this->utilidadFiscalPagoProvisional()
            - $this->ptu()
            - $this->anticiposRendimientoDistribuidosEnPeriodo()
            - $this->perdidasFiscalesEjerciciosAnteriores()
            ;
    }

    /**
     * PAGOS PROVISIONALES PAGADOS
     *
     * Si el mes es enero o no hay determinacion del mes pasado se regresa el valor de 0
     * Si el mes es mayor a enero se regresará el valor de pp_pagados + isr_actividad
     * del mes anterior
     */
    public function ppPagados()
    {
        if ($this->fecha->month == 1 || !$this->determinacionPasada) {
            return 0;
        }

        return $this->determinacionPasada->pp_pagados + $this->determinacionPasada->isr_actividad;
    }

    public function isrRetenido()
    {
        return $this->ventasCobradas->sumatoriaRetencionesIsr(0);
    }

    public function calculosTarifa(): array
    {
        $tabla = [
            'isr_causado' => 0,
            'total' => 0,
        ];

        $tabla['isr_causado'] = $this->base() * config('sat.resico_moral.porcentaje_isr', 0.3);

        $isrACargo = $tabla['isr_causado'] - $this->ppPagados() - $this->isrRetenido();
        $tabla['total'] = $isrACargo > 0 ? round($isrACargo, 0) : 0;

        return $tabla;
    }
}
