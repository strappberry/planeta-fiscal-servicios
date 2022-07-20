<?php

namespace App\Http\Controllers\Contafacil;

use App\Contafacil\Ventas\ViewModels\ImpuestosViewModel;
use App\Contafacil\Ventas\ViewModels\ListadoFacturasVentas;
use App\Http\Controllers\Controller;
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
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfMonth();
        $fechaFin    = Carbon::parse($request->fecha_fin)->endOfMonth();
        $rfc         = $request->rfc;
        $busqueda    = $request->get('busqueda', '');

        $modelo = new ListadoFacturasVentas($rfc, $fechaInicio, $fechaFin, $busqueda);

        return response()->json(
            $modelo->toArray()
        );
    }
}
