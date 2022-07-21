<?php

namespace App\Contafacil\Gastos\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Factura;
use Carbon\Carbon;

class ListadoFacturasGastos extends ViewModel
{
    private $fechaInicio;
    private $fechaFin;
    private $rfc;
    private $busqueda;

    public function __construct(
        string $rfc,
        Carbon $fechaInicio,
        Carbon $fechaFin,
        ?string $busqueda = ''
    ) {
        $this->rfc         = $rfc;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
        $this->busqueda    = $busqueda;
    }

    /**
     * Listado de facturas de ventas
     *
     * @return Factura[]
     */
    public function facturas()
    {
        $facturas = Factura::query()
        ->whereBetween('fecha_emision', [
            $this->fechaInicio,
            $this->fechaFin,
        ])
        ->where('rfc_receptor', $this->rfc)
        ->aplicarFiltroBuscador($this->busqueda)
        ->orderBy('fecha_emision')
        ->get();

        return $facturas;
    }

}
