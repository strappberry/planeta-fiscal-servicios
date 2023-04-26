<?php

namespace App\Acciones\Facturas;

use App\Contafacil\Facturas\ViewModels\PolizaAutomaticaFacturaViewModel;
use App\Contafacil\Facturas\ViewModels\ValidacionPolizaAutomaticaFacturaViewModel;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;

class VinculacionMasivaNumeroCuenta
{
    public static function ejecutar($ids, $cuenta, $monto)
    {
        $facturasCliente = FacturaCliente::whereIn('id', $ids)->get();
        $numeroCuenta = NumeroCuenta::findOrFail($cuenta);

        foreach ($facturasCliente as $facturaCliente) {
            $montoAVincular = $monto;
            RemoverNumeroDeCuentaDeFacturaCliente::ejecutar($facturaCliente, $numeroCuenta);
            $poliza = new PolizaAutomaticaFacturaViewModel($facturaCliente);
            $validaciones = (new ValidacionPolizaAutomaticaFacturaViewModel($poliza))->toArray();

            if ($numeroCuenta->subtipo == NumeroCuenta::SUBTIPO_FECHA_EMISION) {
                if ($montoAVincular == '') {
                    $diferencia = $validaciones['totales_fecha_emision']['cargo'] - $validaciones['totales_fecha_emision']['abono'];
                    $montoAVincular = abs($diferencia);
                }

                VincularNumeroDeCuentaFacturaCliente::ejecutar($facturaCliente, $numeroCuenta, $montoAVincular);
            }

            if ($numeroCuenta->subtipo == NumeroCuenta::SUBTIPO_FECHA_PAGO) {
                if ($montoAVincular == '') {
                    $diferencia = $validaciones['totales_fecha_pago']['cargo'] - $validaciones['totales_fecha_pago']['abono'];
                    $montoAVincular = abs($diferencia);
                }

                VincularNumeroDeCuentaFacturaCliente::ejecutar($facturaCliente, $numeroCuenta, $montoAVincular);
            }

            VerificarFacturaClienteDeducible::ejecutar($facturaCliente);
            GenerarValidacionPolizaIndividual::ejecutar($facturaCliente);
        }
    }
}
