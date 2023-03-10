<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\BalanzaComprobacion\ResolverDeterminacionImpuestosDB;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use App\Models\DeterminacionImpuesto;
use Carbon\Carbon;

class DeterminacionDelImpuestoDBViewModel extends ViewModel
{
    private DeterminacionImpuesto $determinacionImpuesto;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
    ) {
        $this->determinacionImpuesto = ResolverDeterminacionImpuestosDB::ejecutar(
            $this->cliente,
            $this->fecha
        );
    }

    public function determinacionImpuesto(): ?array
    {
        return $this->determinacionImpuesto->determinacion;
    }

    public function calculosIvaIsr(): ?array
    {
        return $this->determinacionImpuesto->calculos_iva_isr;
    }

    public function deducciones(): ?array
    {
        return $this->determinacionImpuesto->deducciones;
    }

    public function impuestosFederales(): ?array
    {
        return $this->determinacionImpuesto->impuestos_federales;
    }
}
