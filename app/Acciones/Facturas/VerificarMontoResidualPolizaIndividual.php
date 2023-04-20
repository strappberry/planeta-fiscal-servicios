<?php

namespace App\Acciones\Facturas;

use App\Contafacil\Facturas\ViewModels\PolizaAutomaticaFacturaViewModel;
use App\Contafacil\Facturas\ViewModels\ValidacionPolizaAutomaticaFacturaViewModel;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;

class VerificarMontoResidualPolizaIndividual
{
    // Calcular la diferencia entre cargo y abono. Si la diferencia es menor a 1 y mayor a -1
    // Se vinculara automaticamente el numero de cuenta 601-84 Otros gastos
    // La diferencia se ingresara a la columna cargo puede ser posivito o negativo
    public static function ejecutar(FacturaCliente $facturaCliente)
    {
        $modelo       = new PolizaAutomaticaFacturaViewModel($facturaCliente);
        $validaciones = (new ValidacionPolizaAutomaticaFacturaViewModel($modelo))->toArray();

        $tipoCuenta = $facturaCliente->tipo_factura == FacturaCliente::TIPO_VENTA ?
            NumeroCuenta::TIPO_POLIZA_VENTAS: NumeroCuenta::TIPO_POLIZA_GASTOS;

        $cuentaResidualEmision = NumeroCuenta::query()
                ->where('numero_cuenta', '601-84')
                ->where('tipo_cuenta', $tipoCuenta)
                ->where('subtipo', NumeroCuenta::SUBTIPO_FECHA_EMISION)
                ->where('residual_cargo_abono', true)
                ->first();
        $cuentaResidualPago = NumeroCuenta::query()
                ->where('numero_cuenta', '601-84')
                ->where('tipo_cuenta', $tipoCuenta)
                ->where('subtipo', NumeroCuenta::SUBTIPO_FECHA_PAGO)
                ->where('residual_cargo_abono', true)
                ->first();

        $diferenciaEmision = $validaciones['totales_fecha_emision']['cargo'] - $validaciones['totales_fecha_emision']['abono'];

        if ($diferenciaEmision > -1 && $diferenciaEmision < 1 && $diferenciaEmision != 0) {
            $diferenciaEmision *= -1;
            $diferenciaEmision += VerificarMontoVinculadoNumeroCuenta::ejecutar($facturaCliente, $cuentaResidualEmision);
            VincularNumeroDeCuentaFacturaCliente::ejecutar($facturaCliente, $cuentaResidualEmision, $diferenciaEmision);
        }

        $diferenciaPago = $validaciones['totales_fecha_pago']['cargo'] - $validaciones['totales_fecha_pago']['abono'];
        if ($diferenciaPago > -1 && $diferenciaPago < 1 && $diferenciaPago != 0) {
            $diferenciaPago *= -1;
            $diferenciaEmision += VerificarMontoVinculadoNumeroCuenta::ejecutar($facturaCliente, $cuentaResidualEmision);
            VincularNumeroDeCuentaFacturaCliente::ejecutar($facturaCliente, $cuentaResidualPago, $diferenciaPago);
        }
    }
}
