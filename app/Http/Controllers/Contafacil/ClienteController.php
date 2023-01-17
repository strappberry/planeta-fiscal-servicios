<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(string $clienteId)
    {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $cliente->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'cliente' => $cliente,
        ]);
    }

    public function actualizarRegimenesFiscales(Request $request, string $clienteId)
    {
        $this->validate($request, [
            'regimenes_fiscales' => 'required|array',
        ]);

        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $cliente->regimenes_fiscales = $request->regimenes_fiscales;
        $cliente->save();

        return response()->json([
            'cliente' => $cliente,
        ]);
    }
}
