<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\DeterminacionImpuestosEnum;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionDelImpuestoResicoPMViewModel extends ViewModel
{
    private $determinacionPasada;
    private $ventasCobradas;
    private $gastosPagados;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha,
        private array $camposEditables,
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
        $this->gastosPagados = $this->cliente->facturasCliente()
            ->with('factura')
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->esGasto()
            ->esConsiderado()
            ->get();

        $this->determinacionPasada = $cliente->determinacionDelImpuesto()
                ->where('mes_trabajo', $this->fecha->copy()->subMonth()->format('Y-m-d'))
                ->first();
    }

    public function ingresos()
    {
        return $this->ventasCobradas->calcularIngresos(0);
    }

    public function ingresosAcumulados()
    {
        if ($this->fecha->month === 1) {
            return $this->ingresos();
        }

        $ingresosAnteriores = ($this->determinacionPasada) ? $this->determinacionPasada->ingresos_acumulados : 0;
        return round($ingresosAnteriores + $this->ingresos(), 0);
    }

    public function deducciones()
    {
        $deducciones = $this->gastosPagados->comprasGastosDevolucionesFacturadosPagados(0);

        return $deducciones;
    }

    public function deduccionesAcumuladas()
    {
        $deduccionesAnteriores = ($this->determinacionPasada) ?
            $this->determinacionPasada->deducciones_acumuladas : 0;

        return round($deduccionesAnteriores + $this->deducciones(), 0);
    }

    public function costoVendidoEjerciciosAnteriores()
    {
        return isset($this->camposEditables[DeterminacionImpuestosEnum::CAMPO_COSTO_VENDIDO_EJERCICIOS_ANTERIORES])
            ? $this->camposEditables[DeterminacionImpuestosEnum::CAMPO_COSTO_VENDIDO_EJERCICIOS_ANTERIORES] : 0;
    }

    public function deduccionInversionesEjerciciosAnteriores()
    {
        return isset($this->camposEditables[DeterminacionImpuestosEnum::CAMPO_DEDUCCION_INVERSIONES_EJERCICIOS_ANTERIORES])
            ? $this->camposEditables[DeterminacionImpuestosEnum::CAMPO_DEDUCCION_INVERSIONES_EJERCICIOS_ANTERIORES] : 0;
    }

    public function participacionTrabajadoresUtilidades()
    {
        return isset($this->camposEditables[DeterminacionImpuestosEnum::CAMPO_PARTICIPACION_TRABAJADORES_UTILIDADES])
            ? $this->camposEditables[DeterminacionImpuestosEnum::CAMPO_PARTICIPACION_TRABAJADORES_UTILIDADES] : 0;
    }

    public function perdidasFiscalesEjerciciosAnteriores()
    {
        return isset($this->camposEditables[DeterminacionImpuestosEnum::CAMPO_PERDIDA_EJERCICIOS_ANTERIORES])
            ? $this->camposEditables[DeterminacionImpuestosEnum::CAMPO_PERDIDA_EJERCICIOS_ANTERIORES] : 0;
    }

    public function base(): float
    {
        $base = $this->ingresosAcumulados()
            - $this->deduccionesAcumuladas()
            - $this->costoVendidoEjerciciosAnteriores()
            - $this->deduccionInversionesEjerciciosAnteriores()
            - $this->participacionTrabajadoresUtilidades()
            - $this->perdidasFiscalesEjerciciosAnteriores()
            ;

        return $base > 0 ? round($base, 0) : 0;
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

        $isrACargon = $tabla['isr_causado'] - $this->ppPagados() - $this->isrRetenido();
        $tabla['total'] = $isrACargon > 0 ? round($isrACargon, 0) : 0;

        return $tabla;
    }
}
