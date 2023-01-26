<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Contafacil\Compartido\Contratos\DeterminacionImpuestosPorRegimen;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Contafacil\Facturas\ViewModels\Traits\UsarDeterminacionDB;
use App\Enums\RegimenFiscal;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionImpuestoRegimen601 extends ViewModel implements DeterminacionImpuestosPorRegimen
{
    use UsarDeterminacionDB;

    protected $excepciones = [
        'datosDeterminacion',
    ];
    private $determinacionPersonaMoral;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
    ) {
        $this->cargarDeterminacionImpuestoDB();
        $this->determinacionPersonaMoral = (new DeterminacionDelImpuestoPersonaMoralViewModel(
            $this->cliente,
            $this->fecha,
            $this->camposEditablesPorRegimen('601')
        ))->toArray();
    }

    public function tipoRegimen(): int
    {
        return RegimenFiscal::PERSONA_MORAL;
    }

    public function personaMoral(): array
    {
        return $this->determinacionPersonaMoral;
    }

    public function isrAFavor(): float
    {
        // TODO: implementar
        return 0;
    }

    public function isrPorPagar(): float
    {
        return round(
            $this->determinacionPersonaMoral['calculos_tarifa']['total']
            - $this->isrAFavor()
        , 0);
    }

    public function datosDeterminacion(): array
    {
        return [
            'ingresos_acumulados'   => 0,
            'deducciones_cumuladas' => 0,
            'pp_pagados'            => 0,
            'isr_actividad'         => 0,
        ];
    }
}
