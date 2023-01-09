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

        return response()->json([
            'cliente' => $cliente,
        ]);
    }
}
