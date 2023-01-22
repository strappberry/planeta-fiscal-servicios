<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\Facturas\CalcularIvaAcreditableAGasto;
use App\Acciones\TablasTarifas\ResolverTablaTarifaAAplicar;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\DeterminacionImpuestosEnum;
use App\Enums\TipoIngreso;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionDelImpuestoActividadEmpresarialViewModel extends ViewModel
{
    private $ventasCobradas;
    private $gastosPagados;
    private $determinacionPasada;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha,
        private $camposEditables = []
    ) {
        $this->ventasCobradas = $this->cliente->facturasCliente()
            ->with('factura')
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->tiposIngreso([
                TipoIngreso::ACTIVIDAD_EMPRESARIAL,
            ])
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

        $mesPasado = $fecha->copy()->subMonth();
        $this->determinacionPasada = $cliente->determinacionDelImpuesto()
                ->where('mes_trabajo', $mesPasado->format('Y-m-d'))
                ->first();
    }

    public function ivaAcreditableAGastos()
    {
        return CalcularIvaAcreditableAGasto::ejecutar(
            $this->ventasCobradas,
            $this->gastosPagados,
            0,
        );
    }

    public function ingresos()
    {
        return $this->ventasCobradas->calcularIngresos(0);
    }

    public function ingresosAcumulados()
    {
        $ingresosAnteriores = ($this->determinacionPasada) ? $this->determinacionPasada->ingresos_acumulados : 0;
        return round($ingresosAnteriores + $this->ingresos(), 0);
    }

    public function deducciones()
    {
        $deducciones = $this->gastosPagados->comprasGastosDevolucionesFacturadosPagados(0);
        $ivaAcreditableAGastos = $this->ivaAcreditableAGastos();

        return $deducciones + $ivaAcreditableAGastos;
    }

    public function deduccionesAcumuladas()
    {
        $deduccionesAnteriores = ($this->determinacionPasada) ?
            $this->determinacionPasada->deducciones_acumuladas : 0;

        return round($deduccionesAnteriores + $this->deducciones(), 0);
    }

    public function ptuPagada()
    {
        // TODO: Pendiente implementar
        return 0;
    }

    public function depreciacion()
    {
        return isset($this->camposEditables[DeterminacionImpuestosEnum::CAMPO_DEPRECIACION]) ?
            $this->camposEditables[DeterminacionImpuestosEnum::CAMPO_DEPRECIACION] : 0;
    }

    public function totalDeducciones()
    {
        return $this->deducciones() + $this->ptuPagada() + $this->depreciacion();
    }

    public function perdidasEjercicioAnterior()
    {
        return isset($this->camposEditables[DeterminacionImpuestosEnum::CAMPO_PERDIDA_EJERCICIOS_ANTERIORES]) ?
            $this->camposEditables[DeterminacionImpuestosEnum::CAMPO_PERDIDA_EJERCICIOS_ANTERIORES] : 0;
    }

    public function base()
    {
        $base = $this->ingresos() - $this->totalDeducciones() - $this->perdidasEjercicioAnterior();

        if ($base < 0) {
            return 0;
        }

        return round($base, 2);
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

    public function calculosTarifa()
    {
        $datosTabla = [
            'limite_inferior'      => 0,
            'limite_superior'      => 0,
            'cuota_fija'           => 0,
            'porcentaje_excedente' => 0,
            'excedente'            => 0,
            'importe_marginal'     => 0,
            'actividad_empresarial' => 0,
            'impuesto_a_cargo'     => 0,
            'total'                => 0,
        ];
        $base = $this->base();

        if ($base == 0) return $datosTabla;

        $tarifa = ResolverTablaTarifaAAplicar::ejecutar(
            '612', $this->fecha->year, $this->fecha->month,
            $this->base()
        );
        if (!$tarifa) return $datosTabla;

        // Datos de la tabla de tarifa
        $datosTabla['limite_inferior']      = $tarifa->limite_inferior;
        $datosTabla['limite_superior']      = $tarifa->limite_superior;
        $datosTabla['cuota_fija']           = $tarifa->cuota_fija;
        $datosTabla['porcentaje_excedente'] = $tarifa->porcentaje_excedente;

        // EXCEDENTE: Base - Limite Inferior
        $datosTabla['excedente'] = round($base - $datosTabla['limite_inferior'], 0);
        // IMPORTE MARGINAL: Excedente * Porcentaje Excedente
        $datosTabla['importe_marginal'] = round($datosTabla['excedente'] * $datosTabla['porcentaje_excedente'], 0);
        // ISR ACTIVIDAD: Cuota Fija + Importe Marginal
        $datosTabla['actividad_empresarial'] = round($datosTabla['cuota_fija'] + $datosTabla['importe_marginal'], 0);
        // Impuesto a cargo: Actividad empresarial + P.P. Pagados
        $datosTabla['impuesto_a_cargo'] = round(
            $datosTabla['actividad_empresarial'] + $this->ppPagados(),
            0,
        );
        // Total
        $datosTabla['total'] = round(
            $datosTabla['impuesto_a_cargo'] - $this->isrRetenido(),
            0
        );

        return $datosTabla;
    }

    public function isrRetenido()
    {
        return $this->ventasCobradas->sumatoriaRetencionesIsr(0);
    }
}
