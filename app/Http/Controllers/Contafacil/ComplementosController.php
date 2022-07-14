<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Illuminate\Http\Request;

class ComplementosController extends Controller
{
    public function obtenerComplementoPagos(Factura $factura)
    {
        $complemento = $factura->complementoPagos()
            ->with([
                'pagos',
                'pagos.documentosRelacionados'
            ])
            ->first();

        return response()->json([
            'success' => true,
            'complemento' => $complemento,
        ]);
    }

    public function obtenerComplementoNomina(Factura $factura)
    {
        $complemento = $factura->complementoNomina()
            ->with([
                'percepciones',
                'deducciones',
                'otrosPagos',
            ])
            ->first();

        return response()->json([
            'success' => true,
            'complemento' => $complemento,
        ]);
    }
}
