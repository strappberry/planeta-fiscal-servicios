<?php

namespace App\Contafacil\Facturas\ViewModels;

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
        $determinacionImpuesto = $this->cliente->determinacionDelImpuesto()
            ->where('mes_trabajo', $this->fecha->format('Y-m-d'))
            ->first();

        if (!$determinacionImpuesto) {
            return null;
        }

        return $determinacionImpuesto->determinacion;
    }
}
