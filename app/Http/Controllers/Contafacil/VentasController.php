<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function impuestos(Request $request)
    {
        $retencionISR = Factura::query()
            ->selectRaw(
                "SUM(retencion_isr) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfDay(),
                Carbon::parse($request->fecha_fin)->endOfDay(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        $retencionIVA = Factura::query()
            ->selectRaw(
                "SUM(retencion_iva) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfDay(),
                Carbon::parse($request->fecha_fin)->endOfDay(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        $retencionIEPS = Factura::query()
            ->selectRaw(
                "SUM(retencion_ieps) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfDay(),
                Carbon::parse($request->fecha_fin)->endOfDay(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        $trasladoIVA = Factura::query()
            ->selectRaw(
                "SUM(traslado_iva) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfDay(),
                Carbon::parse($request->fecha_fin)->endOfDay(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        $trasladoIEPS = Factura::query()
            ->selectRaw(
                "SUM(traslado_ieps) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfDay(),
                Carbon::parse($request->fecha_fin)->endOfDay(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        return response()->json([
            'impuestos' => [
                'retencion_isr'  => (float) $retencionISR->total,
                'retencion_iva'  => (float) $retencionIVA->total,
                'retencion_ieps' => (float) $retencionIEPS->total,
                'traslado_iva'   => (float) $trasladoIVA->total,
                'traslado_ieps'  => (float) $trasladoIEPS->total,
            ],
        ]);
    }

    public function listadoFacturas(Request $request)
    {
        $query = Factura::query()
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfDay(),
                Carbon::parse($request->fecha_fin)->endOfDay(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->aplicarFiltroBuscador($request->get('busqueda', null))
            ->orderBy('fecha_emision')
            ->get();

        return response()->json([
            'facturas' => $query,
        ]);
    }
}
