<?php

namespace App\Http\Controllers\Contafacil;

use App\Contafacil\Ventas\ViewModels\ImpuestosViewModel;
use App\Http\Controllers\Controller;
use App\Models\CompNominaDeduccion;
use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function impuestos(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfMonth();
        $fechaFin    = Carbon::parse($request->fecha_fin)->endOfMonth();
        $rfc         = $request->rfc;

        $modelo = new ImpuestosViewModel($rfc, $fechaInicio, $fechaFin);
        return response()->json([
            'impuestos' => $modelo->toArray(),
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
