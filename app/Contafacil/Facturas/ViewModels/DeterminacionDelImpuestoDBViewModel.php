<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\BalanzaComprobacion\ResolverDeterminacionImpuestosDB;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionDelImpuestoDBViewModel extends ViewModel
{
    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
    ) {
    }

    public function determinacionImpuesto()
    {
        $determinacionImpuesto = ResolverDeterminacionImpuestosDB::ejecutar(
            $this->cliente,
            $this->fecha
        );

        return $determinacionImpuesto->determinacion;
    }
}
