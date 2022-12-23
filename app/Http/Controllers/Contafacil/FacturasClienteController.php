<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Facturas\ActualizarMontoComprobacion;
use App\Acciones\Facturas\GenerarValidacionPolizaIndividual;
use App\Acciones\Facturas\ResolverFacturaCliente;
use App\Http\Controllers\Controller;
use App\Models\Factura;
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
        $facturaCliente = GenerarValidacionPolizaIndividual::ejecutar($facturaCliente);

        $facturaCliente->considerado = ($considerado && $facturaCliente->poliza_valida) ? true : false;
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

        $clienteId   = $request->cliente_id;
        $considerado = $request->considerado;
        $cliente     = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $idsFacturas = explode(',', $request->facturas);

        foreach ($idsFacturas as $id) {
            $factura = Factura::find($id);
            if (!$factura) continue;

            try {
                ActualizarMontoComprobacion::ejecutar($factura);
            } catch (Exception $e) {
                Log::error($e);
            }

            $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);
            $facturaCliente = GenerarValidacionPolizaIndividual::ejecutar($facturaCliente);

            $facturaCliente->considerado = ($considerado && $facturaCliente->poliza_valida) ? true : false;
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
        $cliente   = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);
        $facturaCliente->fecha_pago = $fechaPago;
        $facturaCliente->save();

        return response()->json([
            'factura' => $facturaCliente,
        ]);
    }

    public function establecerConceptoSat(Request $request, Factura $factura)
    {
        $this->validate($request, [
            'cliente_id' => 'required',
        ]);

        $clienteId = $request->cliente_id;
        $cliente   = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);
        $facturaCliente->concepto_sat_id = $request->get('concepto_sat');
        $facturaCliente->save();

        return response()->json([
            'factura' => $facturaCliente,
        ]);
    }

    public function establecerConceptoDeduccionPersonal(Request $request, Factura $factura)
    {
        $this->validate($request, [
            'cliente_id' => 'required',
        ]);

        $clienteId = $request->cliente_id;
        $cliente   = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);
        $facturaCliente->concepto_deduccion_personal_id = $request->get('concepto_deduccion_personal');
        $facturaCliente->save();

        return response()->json([
            'factura' => $facturaCliente,
        ]);
    }

    public function establecerDeducible(Request $request, Factura $factura)
    {
        $this->validate($request, [
            'cliente_id' => 'required',
            'deducible'  => 'required|boolean',
        ]);

        $clienteId = $request->cliente_id;
        $cliente   = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);
        $facturaCliente->deducible = $request->get('deducible');
        $facturaCliente->save();

        return response()->json([
            'factura' => $facturaCliente,
        ]);
    }

    public function establecerTipoIngreso(Request $request, Factura $factura)
    {
        $this->validate($request, [
            'cliente_id' => 'required',
        ]);
        $cliente = ResolverClientePlanetaFiscal::ejecutar($request->cliente_id);

        $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);
        $facturaCliente->tipo_ingreso = $request->get('tipo_ingreso', '');
        $facturaCliente->save();

        return response()->json([
            'factura' => $facturaCliente,
        ]);
    }
}
