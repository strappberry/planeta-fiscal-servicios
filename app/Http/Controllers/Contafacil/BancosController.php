<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use Illuminate\Http\Request;

class BancosController extends Controller
{

    public function listarBancos()
    {
        return response()->json([
            'bancos' => Banco::all(),
        ]);
    }

    public function crearBanco(Request $request)
    {
        $banco = Banco::create([
            'nombre' => $request->nombre,
            'nombre_corto' => $request->nombre_corto,
        ]);

        return response()->json([
            'banco' => $banco,
        ]);
    }

}
