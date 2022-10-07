<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Facturas\ResolverTipoFacturaVentaOGasto;
use App\Contafacil\Facturas\ViewModels\PolizaAutomaticaFacturaViewModel;
use App\Contafacil\Facturas\ViewModels\ValidacionPolizaAutomaticaFacturaViewModel;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;
use Illuminate\Http\Request;

/* TODO:  cambiar la relacion con cliente */
class FacturasNumeroCuentaController extends Controller
{
    public function obtenerPolizaAutomaticaFactura(string $clienteId, Factura $factura)
    {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $facturaCliente = FacturaCliente::query()
            ->where('factura_id', $factura->id)
            ->where('cliente_id', $cliente->planetafiscal_id)
            ->first();

        if (!$facturaCliente) {
            $tipoFactura = ResolverTipoFacturaVentaOGasto::ejecutar($factura, $cliente);
            $facturaCliente = FacturaCliente::create([
                'factura_id'    => $factura->id,
                'cliente_id'    => $clienteId,
                'fecha_emision' => $factura->fecha_emision,
                'consignado'    => false,
                'tipo_factura'  => $tipoFactura,
            ]);
        }

        $modelo = new PolizaAutomaticaFacturaViewModel($facturaCliente);
        $validaciones = new ValidacionPolizaAutomaticaFacturaViewModel($modelo);

        return response()->json([
            'poliza' => $modelo->toArray(),
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

        $facturaCliente = FacturaCliente::query()
            ->where('factura_id', $factura->id)
            ->where('cliente_id', $clienteId)
            ->first();

        if (!$facturaCliente) {
            $facturaCliente = FacturaCliente::create([
                'factura_id'    => $factura->id,
                'cliente_id'    => $clienteId,
                'fecha_emision' => $factura->fecha_emision,
                'consignado'    => false,
            ]);
        }

        $facturaCliente->numerosCuentas()->syncWithoutDetaching([
            $numeroCuenta->id => [
                'monto' => $request->monto,
            ],
        ]);

        if ($numeroCuenta->exclusiones) {
            foreach($numeroCuenta->exclusiones as $exclusion) {
                $numeroCuentaExcluido = NumeroCuenta::buscarExclusion($exclusion)->first();
                if ($numeroCuentaExcluido) {
                    $facturaCliente->numerosCuentas()->syncWithoutDetaching([
                        $numeroCuentaExcluido->id => [
                            'monto' => 0,
                        ],
                    ]);
                }
            }
        }

        return response()->json([
            'agregado' => true,
        ]);
    }

    public function eliminarNumeroCuentaManual(
        Request $request,
        string $clienteId,
        Factura $factura,
        NumeroCuenta $numeroCuenta
    ) {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $facturaCliente = FacturaCliente::query()
            ->where('factura_id', $factura->id)
            ->where('cliente_id', $clienteId)
            ->first();

        if (!$facturaCliente) {
            $facturaCliente = FacturaCliente::create([
                'factura_id'    => $factura->id,
                'cliente_id'    => $clienteId,
                'fecha_emision' => $factura->fecha_emision,
                'consignado'    => false,
            ]);
        }

        $facturaCliente->numerosCuentas()->detach($numeroCuenta->id);

        if ($numeroCuenta->exclusiones) {
            foreach($numeroCuenta->exclusiones as $exclusion) {
                $numeroCuentaExcluido = NumeroCuenta::buscarExclusion($exclusion)->first();
                if ($numeroCuentaExcluido) {
                    $facturaCliente->numerosCuentas()->detach($numeroCuentaExcluido->id);
                }
            }
        }

        return response()->json([
            'eliminado' => true,
        ]);
    }
}
