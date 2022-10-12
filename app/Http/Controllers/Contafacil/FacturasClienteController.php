<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Facturas\ActualizarMontoComprobacion;
use App\Acciones\Facturas\ResolverFacturaCliente;
use App\Acciones\Facturas\ResolverTipoFacturaVentaOGasto;
use App\Clientes\KontafacilApi;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FacturasClienteController extends Controller
{
    public function establecerConsideracion(Request $request, Factura $factura)
    {
        $considerado = $request->considerado;
        $clienteId   = $request->cliente_id;
        $cliente     = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        try {
            ActualizarMontoComprobacion::ejecutar($factura);
        } catch (Exception $e) {
            Log::error($e);
        }

        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);
        $facturaCliente->considerado = $considerado;
        $facturaCliente->save();

        return response()->json([
            'factura' => $facturaCliente,
        ]);
    }

    public function establecerConsideracionMultiples(Request $request)
    {
        $this->validate($request, [
            'facturas'    => 'required',
            'cliente_id'  => 'required|integer',
            'considerado' => 'required',
        ]);

        $clienteId = $request->cliente_id;
        $considerado = $request->considerado;
        $idsFacturas = explode(',', $request->facturas);

        foreach ($idsFacturas as $id) {
            $factura = Factura::find($id);
            if (!$factura) continue;

            try {
                ActualizarMontoComprobacion::ejecutar($factura);
            } catch (Exception $e) {
                Log::error($e);
            }

            $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $clienteId);
            $facturaCliente->considerado = $considerado;
            $facturaCliente->save();
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function establecerFechaPago(Request $request, Factura $factura)
    {
        $fechaPago = $request->fecha_pago;
        $clienteId = $request->cliente_id;

        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $clienteId);
        $facturaCliente->fecha_pago = $fechaPago;
        $facturaCliente->save();

        return response()->json([
            'factura' => $facturaCliente,
        ]);
    }
}
