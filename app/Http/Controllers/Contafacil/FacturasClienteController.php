<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaCliente;
use Illuminate\Http\Request;

class FacturasClienteController extends Controller
{

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
                'fecha_pago'       => $factura->metodo_pago == 'PUE' ? $factura->fecha_emision : null,
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

        $factura = FacturaCliente::updateOrCreate(
            [
                'factura_id' => $factura->id,
            ],
            [
                'factura_id'    => $factura->id,
                'fecha_emision' => $factura->fecha_emision,
                'cliente_id'    => $clienteId,
                'considerado'   => $considerado,
                'fecha_pago'    => $factura->metodo_pago == 'PUE' ? $factura->fecha_emision : null,
            ]
        );

        return response()->json([
            'factura' => $factura,
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

}
