<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Contafacil\Facturas\ViewModels\DeterminacionImpuestoRegimen612;
use App\Contafacil\Facturas\ViewModels\DeterminacionImpuestoRegimen626;
use App\Models\Cliente;
use Carbon\Carbon;

class ResolverDeterminacionDeImpuestos
{
    public static function ejecutar(Cliente $cliente, Carbon $carbon)
    {
        if (is_array($cliente->regimenes_fiscales) && in_array('626', $cliente->regimenes_fiscales)) {
            return new DeterminacionImpuestoRegimen626($cliente, $carbon);
        }

        return new DeterminacionImpuestoRegimen612($cliente, $carbon);
    }
}
