<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Contafacil\Compartido\Contratos\DeterminacionImpuestosPorRegimen;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\RegimenFiscal;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionImpuestoRegimen626 extends ViewModel implements DeterminacionImpuestosPorRegimen
{
    protected $excepciones = [
        'datosDeterminacion',
    ];

    private $determinacionResico;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
    ) {
        $this->determinacionResico = (
            new DeterminacionDelImpuestoResicoViewModel(
                $cliente,
                $fecha
            )
        )->toArray();
    }

    public function tipoRegimen(): int
    {
        return RegimenFiscal::RESICO;
    }

    public function resico(): array
    {
        return $this->determinacionResico;
    }

    public function isrAFavor(): float
    {
        // TODO: implementar
        return 0;
    }

    public function isrPorPagar(): float
    {
        return $this->determinacionResico['calculos_tarifa']['total'];
    }

    public function datosDeterminacion(): array
    {
        return [
            'ingresos_acumulados' => 0,
            'deducciones_cumuladas' => 0,
            'pp_pagados' => 0,
            'isr_actividad' => $this->isrPorPagar(),
        ];
    }
}
