<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\SaldosAFavor\Cuentas\SaldoFavorIVADelPeriodo;
use App\Contafacil\Compartido\Contratos\DeterminacionImpuestosPorRegimen;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\DeterminacionImpuestosEnum;
use App\Enums\RegimenFiscal;
use App\Enums\TipoIngreso;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionImpuestoRegimen612 extends ViewModel implements DeterminacionImpuestosPorRegimen
{
    protected $excepciones = [
        'datosDeterminacion',
    ];

    private $determinacionPersonaFisica;
    private $determinacionArrendamiento;
    private $ventasCobradasActividadEmpresarial;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
    ) {
        $this->ventasCobradasActividadEmpresarial = $this
            ->cliente
            ->facturasCliente()
            ->with('factura')
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->tiposIngreso([
                TipoIngreso::ACTIVIDAD_EMPRESARIAL,
            ])
            ->esVenta()
            ->esConsiderado()
            ->get();

        $this->determinacionPersonaFisica = (
            new DeterminacionDelImpuestoActividadEmpresarialViewModel(
                $cliente,
                $fecha
            )
        )->toArray();

        $this->determinacionArrendamiento = (
            new DeterminacionDelImpuestoArrendamientoViewModel(
                $cliente,
                $fecha
            )
        )->toArray();
    }

    public function tipoRegimen(): int
    {
        return RegimenFiscal::PERSONA_FISICA_ACTIVIDAD_EMPRESARIAL;
    }

    public function porcentajes(): array
    {
        return $this
            ->ventasCobradasActividadEmpresarial
            ->generarTablaPorcentajeIngresos();
    }

    public function actividadEmpresarial(): array
    {
        return $this->determinacionPersonaFisica;
    }

    public function arrendamiento(): array
    {
        return $this->determinacionArrendamiento;
    }

    public function isrAFavor(): float
    {
        $ultimoValor = $this->cliente->determinacionCamposEditables()->buscarUltimoMesConValor(
            DeterminacionImpuestosEnum::CAMPO_ISR_A_FAVOR,
            $this->fecha,
            RegimenFiscal::PERSONA_FISICA_ACTIVIDAD_EMPRESARIAL
        )->first();

        if ($ultimoValor) return floatval($ultimoValor->valor);

        return SaldoFavorIVADelPeriodo::ejecutar($this->cliente, $this->fecha);
    }

    public function isrPorPagar(): float
    {
        $arrendamiento = $this->determinacionArrendamiento['calculos_tarifa']['total'];
        $actividadEmpresarial = $this->determinacionPersonaFisica['calculos_tarifa']['total'];

        return round($arrendamiento + $actividadEmpresarial - $this->isrAFavor(), 0);
    }

    public function datosDeterminacion(): array
    {
        return [
            'ingresos_acumulados' => $this->determinacionPersonaFisica['ingresos_acumulados'],
            'deducciones_acumuladas' => $this->determinacionPersonaFisica['deducciones_acumuladas'],
            'pp_pagados' => $this->determinacionPersonaFisica['pp_pagados'],
            'isr_actividad' => $this->determinacionPersonaFisica['calculos_tarifa']['total'],
        ];
    }
}
