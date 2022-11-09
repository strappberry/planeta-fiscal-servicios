<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\Facturas\CalcularIvaAcreditableAGasto;
use App\Acciones\TablasTarifas\ResolverTablaTarifaAAplicar;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionDelImpuestoViewModel extends ViewModel
{
    private $ventasCobradas;
    private $gastosPagados;
    private $determinacionPasada;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
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

        $mesPasado = $fecha->copy()->subMonth();
        $this->determinacionPasada = $cliente->determinacionDelImpuesto()
                ->where('mes_trabajo', $mesPasado->format('Y-m-d'))
                ->first();
    }

    public function tablasPorcentajesIngresos(): array
    {
        return $this->ventasCobradas->generarTablaPorcentajeIngresos();
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
        // TODO: Pendiente implementar
        return 0;
    }

    public function totalDeducciones()
    {
        return $this->deducciones() + $this->ptuPagada() + $this->depreciacion();
    }

    public function perdidasEjercicioAnterior()
    {
        // TODO: Pendiente implementar
        return 0;
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
            'isr_actividad_uno'    => 0,
            'impuesto_a_cargo'     => 0,
            'isr_actividad_dos'    => 0,
            'isr_a_favor'          => 0,
            'is_por_pagar'         => 0,
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
        $datosTabla['excedente']        = round($base - $datosTabla['limite_inferior'], 2);
        // IMPORTE MARGINAL: Excedente * Porcentaje Excedente
        $datosTabla['importe_marginal'] = round($datosTabla['excedente'] * $datosTabla['porcentaje_excedente'], 2);
        // ISR ACTIVIDAD: Cuota Fija + Importe Marginal
        $datosTabla['isr_actividad']    = round($datosTabla['cuota_fija'] + $datosTabla['importe_marginal'], 2);

        $impuestoACargo = $datosTabla['isr_actividad'] - $this->ppPagados();
        $datosTabla['impuesto_a_cargo'] = ($impuestoACargo >= 0) ? round($impuestoACargo, 2): 0;

        $isrRetenido = $this->isrRetenido();
        // ISR de la actividad empresarial se calcula con:
        // IMPUESTO A CARGO - ISR RETENIDO (ventas) y solo si es mayor a cero
        $isrActividadEmpresarial = $datosTabla['impuesto_a_cargo'] - $isrRetenido;
        $datosTabla['isr_actividad_empresarial'] = ($isrActividadEmpresarial >= 0) ? round($isrActividadEmpresarial, 2): 0;

        // ISR A FAVOR
        // TODO: Pendiente de definir como se calcula
        $datosTabla['isr_a_favor'] = 0;

        //ISR POR PAGAR
        // Se calcula con:
        // ISR DE LA ACTIVIDAD EMPRESARIAL + ISR ARRENDAMIENTO - ISR A FAVOR
        // TODO: Implementar ISR ARRENDAMIENTO
        $datosTabla['is_por_pagar'] = $datosTabla['isr_actividad_empresarial'] + 0 - $datosTabla['isr_a_favor'];

        return $datosTabla;
    }

    public function isrRetenido()
    {
        return $this->ventasCobradas->sumatoriaRetencionesIsr(0);
    }
}
