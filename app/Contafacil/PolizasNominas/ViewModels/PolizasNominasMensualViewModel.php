<?php

namespace App\Contafacil\PolizasNominas\ViewModels;

use App\Contafacil\Compartido\Datos\PolizasNominasDatos;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use App\Models\PolizaNomina;
use Carbon\Carbon;

class PolizasNominasMensualViewModel extends ViewModel
{
    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha,
    ) {

    }

    public function sueldosYSalariosSemiautomatico()
    {
        $polizas = $this->obtenerPolizasNomina(PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS);

        return $polizas;
    }

    public function asimiladosSemiautomatico()
    {
        $polizas = $this->obtenerPolizasNomina(PolizasNominasDatos::SEGMENTO_ASIMILADOS);

        return $polizas;
    }

    public function provisionCostosPatronalesEmaEbaSemiautomatico()
    {
        $polizas = $this->obtenerPolizasNomina(PolizasNominasDatos::SEGMENTO_PROVISION_COSTOS_PATRONALES_EMA_EBA);

        return $polizas;
    }

    public function provisionImpuestoSobreNominaSemiautomatico()
    {
        $polizas = $this->obtenerPolizasNomina(PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA);

        return $polizas;
    }

    private function obtenerPolizasNomina(string $segmento)
    {
        return $this->cliente->polizasNominas()
            ->where('segmento', $segmento)
            ->whereDate('mes_trabajo', $this->fecha)
            ->get();
    }
}
