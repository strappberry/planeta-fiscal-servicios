<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Acciones\MesTrabajo\ResolverMesTrabajo;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Contafacil\Facturas\ViewModels\DeterminacionDelImpuestoDBViewModel;
use App\Contafacil\Polizas\ViewModels\PolizasAutomaticasVentasYGastosViewModel;
use App\Models\Cliente;
use App\Models\MesTrabajo;
use App\Models\NumeroCuenta;
use Carbon\Carbon;

class DeterminacionMesVistaAnualViewModel extends ViewModel
{
    private Carbon $fechaInicio;
    private Carbon $fechaFin;
    private MesTrabajo $mesTrabajo;

    public function __construct(
        private Cliente $cliente,
        private Carbon $mes,
    ) {
        $this->fechaInicio = $this->mes->copy()->startOfMonth();
        $this->fechaFin    = $this->mes->copy()->endOfMonth();
        $this->mesTrabajo  = ResolverMesTrabajo::ejecutar($this->fechaInicio, $this->cliente);
    }

    public function mes(): string
    {
        return $this->mes->monthName;
    }

    public function anio(): string
    {
        return $this->mes->year;
    }

    public function desde(): string
    {
        return $this->fechaInicio->format('d/m/Y');
    }

    public function hasta(): string
    {
        return $this->fechaFin->format('d/m/Y');
    }

    public function bloqueado(): bool
    {
        return $this->mesTrabajo->bloqueado;
    }

    public function polizaAutomaticaVentas(): array
    {
        return (new PolizasAutomaticasVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_VENTAS,
            $this->fechaInicio,
            $this->fechaFin,
            $this->cliente
        ))->toArray();
    }

    public function polizaAutomaticaGastos(): array
    {
        return (new PolizasAutomaticasVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_GASTOS,
            $this->fechaInicio,
            $this->fechaFin,
            $this->cliente
        ))->toArray();
    }

    public function balanzaComprobacion(): array
    {
        return (new BalanzaComprobacionSinCalculosViewModel(
            $this->fechaInicio,
            $this->cliente
        ))->toArray();
    }

    public function determinacionImpuesto(): array
    {
        return (new DeterminacionDelImpuestoDBViewModel(
            $this->cliente,
            $this->fechaInicio,
        ))->toArray();
    }
}
