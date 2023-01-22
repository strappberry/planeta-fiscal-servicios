<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Contafacil\Compartido\Contratos\DeterminacionImpuestosPorRegimen;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Contafacil\Facturas\ViewModels\Traits\UsarDeterminacionDB;
use App\Enums\DeterminacionImpuestosEnum;
use App\Enums\RegimenFiscal;
use App\Enums\TipoPersona;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionImpuestoRegimen626 extends ViewModel implements DeterminacionImpuestosPorRegimen
{
    use UsarDeterminacionDB;
    protected $excepciones = [
        'datosDeterminacion',
    ];

    private $determinacionResico;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
    ) {
        $this->cargarDeterminacionImpuestoDB();
        $this->determinacionResico = $this->resolverTipoDeclaracionResico()->toArray();
    }

    public function tipoRegimen(): int
    {
        return RegimenFiscal::RESICO;
    }

    public function tipoPersona(): string
    {
        return $this->cliente->tipoPersona;
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
        return round(
            $this->determinacionResico['calculos_tarifa']['total']
            - $this->isrAFavor()
        , 0);
    }

    public function datosDeterminacion(): array
    {
        $ingresosAcumulados = 0;
        $deduccionesAcumuladas = 0;
        $ppPagados = 0;
        $isrActividad = $this->isrPorPagar();

        if ($this->cliente->tipoPersona == TipoPersona::MORAL) {
            $ingresosAcumulados    = $this->determinacionResico['ingresos_acumulados'];
            $deduccionesAcumuladas = $this->determinacionResico['deducciones_cumuladas'];
            $ppPagados             = $this->determinacionResico['pp_pagados'];
            $isrActividad          = $this->determinacionResico['calculos_tarifa']['total'];
        }

        return [
            'ingresos_acumulados'   => $ingresosAcumulados,
            'deducciones_cumuladas' => $deduccionesAcumuladas,
            'pp_pagados'            => $ppPagados,
            'isr_actividad'         => $isrActividad,
        ];
    }

    private function resolverTipoDeclaracionResico()
    {
        if ($this->cliente->tipoPersona == TipoPersona::MORAL) {
            return new DeterminacionDelImpuestoResicoPMViewModel(
                $this->cliente,
                $this->fecha,
                $this->camposEditablesPorRegimen('626')
            );
        }

        return new DeterminacionDelImpuestoResicoPFViewModel(
            $this->cliente,
            $this->fecha
        );
    }
}
