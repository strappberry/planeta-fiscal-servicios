<?php

namespace App\Http\Controllers\Contafacil;

use App\Contafacil\BalanzaComprobacion\ViewModels\BalanzaComprobacionViewModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BalanzaComprobacionController extends Controller
{

    public function balanza(int $cliente)
    {
        $viewModel = new BalanzaComprobacionViewModel($cliente);

        return response()->json([
            'balanza_comprobacion' => $viewModel->toArray(),
        ]);
    }

}
