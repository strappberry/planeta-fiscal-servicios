<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Facturas\GenerarValidacionPolizaIndividual;
use App\Acciones\Facturas\RemoverNumeroDeCuentaDeFacturaCliente;
use App\Acciones\Facturas\ResolverFacturaCliente;
use App\Acciones\Facturas\VerificarMontoResidualPolizaIndividual;
use App\Acciones\Facturas\VincularNumeroDeCuentaFacturaCliente;
use App\Contafacil\Facturas\ViewModels\PolizaAutomaticaFacturaViewModel;
use App\Contafacil\Facturas\ViewModels\ValidacionPolizaAutomaticaFacturaViewModel;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\NumeroCuenta;
use Illuminate\Http\Request;

class FacturasNumeroCuentaController extends Controller
{
    public function obtenerPolizaAutomaticaFactura(string $clienteId, Factura $factura)
    {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);

        $modelo       = new PolizaAutomaticaFacturaViewModel($facturaCliente);
        $validaciones = new ValidacionPolizaAutomaticaFacturaViewModel($modelo);

        return response()->json([
            'poliza'       => $modelo->toArray(),
            'validaciones' => $validaciones->toArray(),
        ]);
    }

    public function agregarNumeroCuentaManual(Request $request, string $clienteId, Factura $factura)
    {
        $this->validate($request, [
            'numero_cuenta' => 'required',
            'monto'         => 'required',
        ]);

        $numeroCuenta = NumeroCuenta::find($request->numero_cuenta);
        if (!$numeroCuenta) {
            return response()->json([
                'success' => false,
                'message' => 'El número de cuenta no existe',
            ]);
        }

        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);

        VincularNumeroDeCuentaFacturaCliente::ejecutar($facturaCliente, $numeroCuenta, $request->monto);
        VerificarMontoResidualPolizaIndividual::ejecutar($facturaCliente);
        $facturaCliente = GenerarValidacionPolizaIndividual::ejecutar($facturaCliente);

        return response()->json([
            'agregado'      => true,
            'poliza_valida' => $facturaCliente->poliza_valida,
            'considerado'   => $facturaCliente->considerado,
        ]);
    }

    public function eliminarNumeroCuentaManual(
        Request $request,
        string $clienteId,
        Factura $factura,
        NumeroCuenta $numeroCuenta
    ) {
        $cliente        = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);

        RemoverNumeroDeCuentaDeFacturaCliente::ejecutar($facturaCliente, $numeroCuenta);
        VerificarMontoResidualPolizaIndividual::ejecutar($facturaCliente);
        $facturaCliente = GenerarValidacionPolizaIndividual::ejecutar($facturaCliente);

        return response()->json([
            'eliminado'     => true,
            'poliza_valida' => $facturaCliente->poliza_valida,
            'considerado'   => $facturaCliente->considerado,
        ]);
    }
}
