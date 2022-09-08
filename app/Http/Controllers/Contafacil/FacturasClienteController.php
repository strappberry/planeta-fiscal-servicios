<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Facturas\ActualizarMontoComprobacion;
use App\Clientes\KontafacilApi;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaCliente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FacturasClienteController extends Controller
{
    private $cliente;

    public function asignarNumeroCuenta(Request $request, Factura $factura)
    {
        $numeroCuenta = $request->numero_cuenta;
        $clienteId    = $request->cliente_id;

        $factura = FacturaCliente::updateOrCreate(
            [
                'factura_id' => $factura->id,
            ],
            [
                'factura_id'       => $factura->id,
                'cliente_id'       => $clienteId,
                'numero_cuenta_id' => $numeroCuenta,
                'fecha_emision'    => $factura->fecha_emision,
            ]
        );

        return response()->json([
            'factura' => $factura,
        ]);
    }

    public function asignarNumeroCuentaPolizaSemiautomatica(Request $request, Factura $factura)
    {
        $numeroCuenta = $request->numero_cuenta;
        $clienteId    = $request->cliente_id;

        $factura = FacturaCliente::updateOrCreate(
            [
                'factura_id' => $factura->id,
            ],
            [
                'factura_id'       => $factura->id,
                'cliente_id'       => $clienteId,
                'cuenta_poliza'    => $numeroCuenta,
                'fecha_emision'    => $factura->fecha_emision,
            ]
        );

        return response()->json([
            'factura' => $factura,
        ]);
    }

    public function establecerConsideracion(Request $request, Factura $factura)
    {
        $considerado = $request->considerado;
        $clienteId    = $request->cliente_id;

        try {
            ActualizarMontoComprobacion::ejecutar($factura);
        } catch (Exception $e) {
            Log::error($e);
        }

        $fechaPago = null;
        $facturaCliente = FacturaCliente::query()
            ->where('factura_id', $factura->id)
            ->where('cliente_id', $clienteId)
            ->first();
        if ($facturaCliente) {
            $fechaPago = $facturaCliente->fecha_pago;
        }

        $tipoFactura = $this->varificarFacturaTipo($factura, $clienteId);

        $facturaCliente = FacturaCliente::updateOrCreate(
            [
                'factura_id' => $factura->id,
            ],
            [
                'factura_id'    => $factura->id,
                'fecha_emision' => $factura->fecha_emision,
                'cliente_id'    => $clienteId,
                'considerado'   => $considerado,
                'fecha_pago'    => $factura->metodo_pago == 'PUE' ? $factura->fecha_emision : $fechaPago,
                'tipo_factura'  => $tipoFactura,
            ]
        );

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
            $tipoFactura = $this->varificarFacturaTipo($factura, $clienteId);

            $fechaPago = null;
            $facturaCliente = FacturaCliente::query()
                ->where('factura_id', $factura->id)
                ->where('cliente_id', $clienteId)
                ->first();
            if ($facturaCliente) {
                $fechaPago = $facturaCliente->fecha_pago;
            }

            try {
                ActualizarMontoComprobacion::ejecutar($factura);
            } catch (Exception $e) {
                Log::error($e);
            }

            FacturaCliente::updateOrCreate(
                [
                    'factura_id' => $factura->id,
                ],
                [
                    'factura_id'    => $factura->id,
                    'fecha_emision' => $factura->fecha_emision,
                    'cliente_id'    => $clienteId,
                    'considerado'   => $considerado,
                    'fecha_pago'    => $factura->metodo_pago == 'PUE' ? $factura->fecha_emision : $fechaPago,
                    'tipo_factura'  => $tipoFactura,
                ]
            );
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function establecerFechaPago(Request $request, Factura $factura)
    {
        $fechaPago = $request->fecha_pago;
        $clienteId = $request->cliente_id;

        $facturaCliente = FacturaCliente::updateOrCreate(
            [
                'factura_id' => $factura->id,
            ],
            [
                'factura_id'    => $factura->id,
                'fecha_emision' => $factura->fecha_emision,
                'cliente_id'    => $clienteId,
                'fecha_pago'    => $fechaPago,
            ]
        );

        return response()->json([
            'factura' => $facturaCliente,
        ]);
    }

    /**
     * Verificar sl la factura es de ingreso o egreso.
     *
     * @param Factura $factura
     * @param string $cliente
     * @return ?string
     */
    private function varificarFacturaTipo($factura, $clienteId)
    {
        if ($this->cliente == null) {
            $kontafacilApi = new KontafacilApi();
            $response      = $kontafacilApi->obtenerCliente($clienteId);
            $this->cliente = $response->json();

            if (!$response->ok()) {
                return null;
            }
        }

        if ($this->cliente['rfc'] == $factura->rfc_emisor) {
            return FacturaCliente::TIPO_VENTA;
        }
        if ($this->cliente['rfc'] == $factura->rfc_receptor) {
            return FacturaCliente::TIPO_GASTO;
        }

        return null;
    }

}
