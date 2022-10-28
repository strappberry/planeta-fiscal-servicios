<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\TablasTarifas\ResolverTablaTarifaAAplicar;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionDelImpuestoViewModel extends ViewModel
{
    private $ventasCobradas;
    private $gastosPagados;

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
    }

    public function ingresos()
    {
        return $this->ventasCobradas->calcularIngresos();
    }

    public function deducciones()
    {
        return $this->gastosPagados->comprasGastosDevolucionesFacturadosPagados();
    }

    public function ptuPagada()
    {
        return 0;
    }

    public function depreciacion()
    {
        return 0;
    }

    public function totalDeducciones()
    {
        return $this->deducciones() + $this->ptuPagada() + $this->depreciacion();
    }

    public function perdidasEjercicioAnterior()
    {
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

    public function calculosTarifa()
    {
        $datosTabla = [
            'limite_inferior'      => 0,
            'limite_superior'      => 0,
            'cuota_fija'           => 0,
            'porcentaje_excedente' => 0,
            'excedente'            => 0,
            'importe_marginal'     => 0,
            'isr_actividad'        => 0,
            'pp_pagado'            => 0,
            'impuesto_a_cargo'     => 0,
        ];
        $base = $this->base();

        if ($base == 0) return $datosTabla;

        $tarifa = ResolverTablaTarifaAAplicar::ejecutar(
            '612', $this->fecha->year, $this->fecha->month,
            $this->base()
        );
        if (!$tarifa) return $datosTabla;

        $datosTabla['limite_inferior']      = $tarifa->limite_inferior;
        $datosTabla['limite_superior']      = $tarifa->limite_superior;
        $datosTabla['cuota_fija']           = $tarifa->cuota_fija;
        $datosTabla['porcentaje_excedente'] = $tarifa->porcentaje_excedente;

        $datosTabla['excedente']        = round($base - $datosTabla['limite_inferior'], 2);
        $datosTabla['importe_marginal'] = round($datosTabla['excedente'] * $datosTabla['porcentaje_excedente'], 2);
        $datosTabla['isr_actividad']    = round($datosTabla['cuota_fija'] + $datosTabla['importe_marginal'], 2);

        $impuestoACargo = $datosTabla['isr_actividad'] - $datosTabla['pp_pagado'];
        $datosTabla['impuesto_a_cargo'] = ($impuestoACargo >= 0) ? round($impuestoACargo, 2): 0;

        return $datosTabla;
    }

    public function isrRetenido()
    {
        return round($this->ventasCobradas->isrRetenido(), 2);
    }
}
