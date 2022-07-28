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
            ->orderBy('descripcion', 'asc')
            ->get();

        return response()->json([
            'numeros_cuentas' => $numerosCuenta,
        ]);
    }

}
