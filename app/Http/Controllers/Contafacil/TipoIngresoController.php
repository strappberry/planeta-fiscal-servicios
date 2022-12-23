<?php

namespace App\Http\Controllers\Contafacil;

use App\Enums\TipoIngreso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoIngresoController extends Controller
{
    public function index()
    {
        return response()->json([
            'tipos_ingreso' => TipoIngreso::listado(),
        ]);
    }
}
