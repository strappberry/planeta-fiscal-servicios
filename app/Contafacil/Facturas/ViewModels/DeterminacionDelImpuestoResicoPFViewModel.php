<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\TablasTarifas\ResolverTablaTarifaAAplicar;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionDelImpuestoResicoPFViewModel extends ViewModel
{
    private $ventasCobradas;

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
    }

    public function ingresos()
    {
        return $this->ventasCobradas->calcularIngresos(0);
    }

    public function calculosTarifa(): array
    {
        $datosTabla = [
            'limite_inferior'      => 0,
            'limite_superior'      => 0,
            'cuota_fija'           => 0,
            'porcentaje_excedente' => 0,
            'isr_causado'          => 0,
            'total'                => 0,
        ];

        $tarifa = ResolverTablaTarifaAAplicar::ejecutar(
            '626', $this->fecha->year, $this->fecha->month,
            $this->ingresos()
        );
        if (!$tarifa) return $datosTabla;

        // Datos de la tabla de tarifa
        $datosTabla['limite_inferior']      = $tarifa->limite_inferior;
        $datosTabla['limite_superior']      = $tarifa->limite_superior;
        $datosTabla['cuota_fija']           = $tarifa->cuota_fija;
        $datosTabla['porcentaje_excedente'] = $tarifa->porcentaje_excedente;

        $datosTabla['isr_causado'] = round(
            $this->ingresos() * $datosTabla['porcentaje_excedente'],
            0
        );

        $total = $datosTabla['isr_causado'] - $this->isrRetenido();
        $datosTabla['total'] = ($total < 0) ? 0 : round($total, 0);

        return $datosTabla;
    }

    public function isrRetenido()
    {
        return $this->ventasCobradas->sumatoriaRetencionesIsr(0);
    }
}
