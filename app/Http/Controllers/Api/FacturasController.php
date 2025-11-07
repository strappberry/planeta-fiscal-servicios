<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BuscarFacturaRequest;
use App\Models\Factura;
use Carbon\Carbon;

class FacturasController extends Controller
{

    public function buscarFacturas(BuscarFacturaRequest $request)
    {
        $query = Factura::query()
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfDay(),
                Carbon::parse($request->fecha_fin)->endOfDay(),
            ]);

        if ($request->tipo_busqueda === 'emitido') {
            $query->where('rfc_emisor', $request->rfc);
        } else {
            $query->where('rfc_receptor', $request->rfc);
        }

        $query->aplicarFiltroBuscador($request->get('busqueda', null));

        $facturas = $query->orderBy('fecha_emision')->get();

        return response()->json([
            'facturas' => $facturas,
        ]);
    }

    public function buscarPorUuid(Request $request)
    {
        $request->validate([
            'rfc' => 'required|string',
            'uuid' => 'required|string|uuid',
        ]);

        $factura = \App\Models\Factura::where('rfc_receptor', $request->rfc)
            ->where('uuid', $request->uuid)
            ->first();

        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        return response()->json(['factura' => $factura]);
    }
}
