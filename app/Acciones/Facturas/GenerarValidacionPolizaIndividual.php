<?php

namespace App\Acciones\Facturas;

use App\Contafacil\Facturas\ViewModels\PolizaAutomaticaFacturaViewModel;
use App\Contafacil\Facturas\ViewModels\ValidacionPolizaAutomaticaFacturaViewModel;
use App\Models\FacturaCliente;

class GenerarValidacionPolizaIndividual
{
    /**
     * Dada una factura de cliente se generara la poliza indivdual y se guardara la validacion
     * en la base de datos.
     */
    public static function ejecutar(FacturaCliente $facturaCliente): FacturaCliente
    {
        $modelo       = new PolizaAutomaticaFacturaViewModel($facturaCliente);
        $validaciones = (new ValidacionPolizaAutomaticaFacturaViewModel($modelo))->toArray();

        $facturaCliente->poliza_valida = $validaciones['validaciones']['validacion'];

        if ($facturaCliente->poliza_valida === false) {
            $facturaCliente->considerado = false;
        }

        $facturaCliente->save();

        return $facturaCliente;
    }
}
