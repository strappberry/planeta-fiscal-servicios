<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\SaldosAFavor\Cuentas\SaldoFavorIVADelPeriodo;
use App\Contafacil\Compartido\Contratos\DeterminacionImpuestosPorRegimen;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\DeterminacionImpuestosEnum;
use App\Enums\RegimenFiscal;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionImpuestoRegimen601 extends ViewModel implements DeterminacionImpuestosPorRegimen
{
    protected $excepciones = [
        'datosDeterminacion',
    ];
    private $determinacionPersonaMoral;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
    ) {
        $this->determinacionPersonaMoral = (new DeterminacionDelImpuestoPersonaMoralViewModel(
            $this->cliente,
            $this->fecha,
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
        $ultimoValor = $this->cliente->determinacionCamposEditables()->buscarUltimoMesConValor(
            DeterminacionImpuestosEnum::CAMPO_ISR_A_FAVOR,
            $this->fecha,
            RegimenFiscal::PERSONA_MORAL
        )->first();

        if ($ultimoValor) return floatval($ultimoValor->valor);

        return SaldoFavorIVADelPeriodo::ejecutar($this->cliente, $this->fecha);
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
            'ingresos_acumulados'    => $this->determinacionPersonaMoral['ingresos_acumulados'],
            'deducciones_acumuladas' => 0,
            'pp_pagados'             => $this->determinacionPersonaMoral['pp_pagados'],
            'isr_actividad'          => $this->determinacionPersonaMoral['calculos_tarifa']['total'],
        ];
    }
}
