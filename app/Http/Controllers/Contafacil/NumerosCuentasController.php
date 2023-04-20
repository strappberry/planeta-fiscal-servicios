<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\NumeroCuenta;
use Illuminate\Http\Request;

class NumerosCuentasController extends Controller
{

    public function numerosCuenta()
    {
        $numerosCuenta = NumeroCuenta::query()
            ->orderBy('tipo_cuenta', 'asc')
            ->orderBy('numero_cuenta', 'asc')
            ->get();

        return response()->json([
            'numeros_cuentas' => $numerosCuenta,
        ]);
    }

    public function crearNumeroCuenta(Request $request)
    {
        $campos = $request->all();

        $numeroCuenta = NumeroCuenta::create($campos);

        return response()->json([
            'numero_cuenta' => $numeroCuenta,
        ]);
    }

}
