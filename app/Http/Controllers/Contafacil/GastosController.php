<?php

namespace App\Http\Controllers\Contafacil;

use App\Contafacil\Gastos\ViewModels\ImpuestosGastosViewModel;
use App\Contafacil\Gastos\ViewModels\ListadoFacturasGastos;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GastosController extends Controller
{

    public function impuestos(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfMonth();
        $fechaFin    = Carbon::parse($request->fecha_fin)->endOfMonth();
        $rfc         = $request->rfc;

        $modelo = new ImpuestosGastosViewModel($rfc, $fechaInicio, $fechaFin);

        return response()->json([
            'impuestos' => $modelo->toArray(),
        ]);
    }

    public function listadoFacturas(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfMonth();
        $fechaFin    = Carbon::parse($request->fecha_fin)->endOfMonth();
        $rfc         = $request->rfc;
        $busqueda    = $request->get('busqueda', '');

        $modelo = new ListadoFacturasGastos($rfc, $fechaInicio, $fechaFin, $busqueda);

        return response()->json(
            $modelo->toArray()
        );
    }

    public function listadoFacturasProvisional(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha)->startOfMonth();
        $fechaFin    = Carbon::parse($request->fecha)->endOfMonth();
        $rfc         = $request->rfc;
        $clienteId   = $request->cliente_id;

        $facturas = Factura::query()
        ->with([
            'facturasCliente' => function ($query) use ($clienteId) {
                $query->where('cliente_id', $clienteId);
            },
        ])
        ->whereBetween('fecha_emision', [
            $fechaInicio,
            $fechaFin,
        ])
        ->where('rfc_receptor', $rfc)
        ->orderBy('fecha_emision')
        ->get();

        return response()->json([
            'facturas' => $facturas
        ]);
    }

}
