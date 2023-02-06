<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Contafacil\Compartido\Contratos\DebeTenerBaseMaxima;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\DeterminacionImpuestosEnum;
use App\Enums\RegimenFiscal;
use App\Models\Cliente;
use App\Models\DeterminacionImpuesto;
use Carbon\Carbon;

class DeterminacionDelImpuestoPersonaMoralViewModel extends ViewModel implements DebeTenerBaseMaxima
{
    private $ventasCobradas;
    private $determinacionPasada;
    private $camposEditables;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha,
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

        $this->camposEditables = $cliente->determinacionCamposEditables()
            ->buscarPorRegimen(RegimenFiscal::PERSONA_MORAL)
            ->buscarPorMes($this->fecha)
            ->get();
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
        $ultimoValor = $this->cliente->determinacionCamposEditables()->buscarUltimoMesConValor(
            DeterminacionImpuestosEnum::CAMPO_COEFICIENTE_UTILIDAD,
            $this->fecha,
            RegimenFiscal::PERSONA_MORAL
        )->first();

        if ($ultimoValor) {
            return $ultimoValor->valor;
        }

        return 0;
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
        $campo = $this->camposEditables->firstWhere(
            'clave',
            DeterminacionImpuestosEnum::CAMPO_ANTICIPOS_RENDIMIENTOS_DISTRIBUIDOS_EN_PERIODO
        );

        return $campo ? floatval($campo->valor) : 0;
    }

    public function perdidasFiscalesEjerciciosAnteriores(): float
    {
        $ultimoValor = $this->cliente->determinacionCamposEditables()->buscarUltimoMesConValor(
            DeterminacionImpuestosEnum::CAMPO_PERDIDA_EJERCICIOS_ANTERIORES,
            $this->fecha,
            RegimenFiscal::PERSONA_MORAL
        )->first();

        return $ultimoValor ? floatval($ultimoValor->valor) : 0;
    }

    public function perdidasFiscalesMesAnterior(): float
    {
        $ultimoValor = $this->cliente->determinacionCamposEditables()->buscarMesPrevioConValor(
            DeterminacionImpuestosEnum::CAMPO_PERDIDA_EJERCICIOS_ANTERIORES,
            $this->fecha,
            RegimenFiscal::PERSONA_MORAL
        )->first();

        return $ultimoValor ? floatval($ultimoValor->valor) : 0;
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
