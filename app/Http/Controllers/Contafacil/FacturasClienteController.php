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
            ]
        );

        return response()->json([
            'factura' => $factura,
        ]);
    }

}
