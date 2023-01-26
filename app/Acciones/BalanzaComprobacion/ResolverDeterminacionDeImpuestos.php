<?php

namespace App\Acciones\BalanzaComprobacion;

use App\Contafacil\Facturas\ViewModels\DeterminacionImpuestoRegimen601;
use App\Contafacil\Facturas\ViewModels\DeterminacionImpuestoRegimen612;
use App\Contafacil\Facturas\ViewModels\DeterminacionImpuestoRegimen626;
use App\Enums\RegimenFiscal;
use App\Models\Cliente;
use Carbon\Carbon;

class ResolverDeterminacionDeImpuestos
{
    public static function ejecutar(Cliente $cliente, Carbon $carbon)
    {
        if ($cliente->esPersonaMoral && $cliente->tieneRegimen(RegimenFiscal::PERSONA_MORAL)) {
            return new DeterminacionImpuestoRegimen601($cliente, $carbon);
        }
        if ($cliente->esPersonaFisica && $cliente->tieneRegimen(RegimenFiscal::RESICO)) {
            return new DeterminacionImpuestoRegimen626($cliente, $carbon);
        }

        return new DeterminacionImpuestoRegimen612($cliente, $carbon);
    }
}
