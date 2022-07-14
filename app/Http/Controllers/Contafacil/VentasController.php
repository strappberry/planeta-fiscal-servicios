<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\CompNominaDeduccion;
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
                Carbon::parse($request->fecha_inicio)->startOfMonth(),
                Carbon::parse($request->fecha_fin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        $retencionISRArrendamiento = Factura::query()
            ->selectRaw(
                "SUM(retencion_isr) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfMonth(),
                Carbon::parse($request->fecha_fin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->where('regimen_fiscal_emisor', '606')
            ->first();

        $retencionIVA = Factura::query()
            ->selectRaw(
                "SUM(retencion_iva) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfMonth(),
                Carbon::parse($request->fecha_fin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        $retencionIEPS = Factura::query()
            ->selectRaw(
                "SUM(retencion_ieps) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfMonth(),
                Carbon::parse($request->fecha_fin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        $trasladoIVA = Factura::query()
            ->selectRaw(
                "SUM(traslado_iva) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfMonth(),
                Carbon::parse($request->fecha_fin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        $trasladoIEPS = Factura::query()
            ->selectRaw(
                "SUM(traslado_ieps) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfMonth(),
                Carbon::parse($request->fecha_fin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->first();

        $facturasNomina = Factura::query()
            ->where('tipo_comprobante', 'N')
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfMonth(),
                Carbon::parse($request->fecha_fin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $request->rfc)
            ->get();
        $nominaISR = 0;
        foreach ($facturasNomina as $factura) {
            if (!$factura->complementoNomina) continue;
            $calculado = CompNominaDeduccion::query()
                ->selectRaw("SUM(importe) as total")
                ->where('tipo_deduccion', '002')
                ->where('comp_nomina_id', $factura->complementoNomina->id)
                ->first();

            $nominaISR += $calculado->total;
        }

        return response()->json([
            'impuestos' => [
                'retencion_isr'  => (float) $retencionISR->total,
                'retencion_iva'  => (float) $retencionIVA->total,
                'retencion_ieps' => (float) $retencionIEPS->total,
                'traslado_iva'   => (float) $trasladoIVA->total,
                'traslado_ieps'  => (float) $trasladoIEPS->total,
                'nomina_isr'     => (float) $nominaISR,
                'arrendamiento_retencion_isr' => (float) $retencionISRArrendamiento->total,
            ],
        ]);
    }

    public function listadoFacturas(Request $request)
    {
        $query = Factura::query()
            ->whereBetween('fecha_emision', [
                Carbon::parse($request->fecha_inicio)->startOfMonth(),
                Carbon::parse($request->fecha_fin)->endOfMonth(),
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
