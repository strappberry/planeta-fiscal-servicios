<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Illuminate\Http\Request;

class FacturasController extends Controller
{

    public function actualizarMontos(Request $request, Factura $factura)
    {
        $this->validate($request, [
            'tasa_cero'         => 'required',
            'traslados_exentos' => 'required',
            'otros_impuestos'   => 'required',
            'traslado_iva'      => 'required',
            'traslado_ieps'     => 'required',
            'retencion_iva'     => 'required',
            'retencion_isr'     => 'required',
        ]);

        $factura->tasa_cero         = $request->tasa_cero;
        $factura->traslados_exentos = $request->traslados_exentos;
        $factura->otros_impuestos   = $request->otros_impuestos;
        $factura->traslado_iva      = $request->traslado_iva;
        $factura->traslado_ieps     = $request->traslado_ieps;
        $factura->retencion_iva     = $request->retencion_iva;
        $factura->retencion_isr     = $request->retencion_isr;
        $factura->save();

        return response()->json([
            'factura' => $factura,
        ]);
    }

}
