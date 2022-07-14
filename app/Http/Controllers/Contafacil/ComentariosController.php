<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Illuminate\Http\Request;

class ComentariosController extends Controller
{
    public function agregarComentarioFactura(Request $request)
    {
        $factura = Factura::find($request->factura_id);
        if (!$factura) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Factura no encontrada',
            ], 404);
        }

        $comentario = $factura->comentarios()->create([
            'comentario' => $request->comentario,
            'usuario_id' => $request->usuario_id,
        ]);

        return response()->json([
            'success'    => true,
            'mensaje'    => 'Comentario agregado',
            'comentario' => $comentario,
        ]);
    }

    public function comentariosFactura(Factura $factura)
    {
        $comentarios = $factura->comentarios()->get();

        return response()->json([
            'success' => true,
            'comentarios' => $comentarios,
        ]);
    }
}
