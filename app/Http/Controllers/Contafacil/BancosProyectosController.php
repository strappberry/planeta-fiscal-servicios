<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\BancosProyecto;
use Illuminate\Http\Request;

class BancosProyectosController extends Controller
{
    public function listar($cliente)
    {
        $proyectos = BancosProyecto::query()
            ->with('banco')
            ->where('cliente_id', $cliente)
            ->get();

        return response()->json([
            'proyectos' => $proyectos,
        ]);
    }

    public function crearProyecto(Request $request)
    {
        $proyecto = BancosProyecto::create([
            'nombre'     => $request->nombre,
            'cliente_id' => $request->cliente_id,
            'banco_id'   => $request->banco_id,
            'tipo'       => $request->tipo,
        ]);

        return response()->json([
            'proyecto' => $proyecto,
        ]);
    }
}
