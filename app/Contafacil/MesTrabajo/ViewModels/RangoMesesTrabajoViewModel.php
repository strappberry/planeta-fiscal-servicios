<?php

namespace App\Contafacil\MesTrabajo\ViewModels;

use App\Acciones\MesTrabajo\ResolverMesTrabajo;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class RangoMesesTrabajoViewModel extends ViewModel
{
    private $cliente;
    private $fechaInicio;
    private $fechaFin;

    public function __construct(
        Cliente $cliente,
        Carbon $fechaInicio,
        Carbon $fechaFin
    ) {
        $this->cliente     = $cliente;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
    }

    /**
     * Listado de meses de trabajo
     *
     * @return MesTrabajo[]
     */
    public function mesesTrabajo()
    {
        $rangoFechas = CarbonInterval::months(1)
            ->toPeriod($this->fechaInicio, $this->fechaFin);

        $mesesTrabajo = collect();
        foreach($rangoFechas as $fecha) {
            $mesTrabajo = ResolverMesTrabajo::ejecutar($fecha->startOfMonth(), $this->cliente);
            $mesesTrabajo->push($mesTrabajo);
        }

        return $mesesTrabajo->toArray();
    }
}
