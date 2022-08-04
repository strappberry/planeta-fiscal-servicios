<?php

namespace App\Http\Controllers\Contafacil;

use App\Contafacil\BalanzaComprobacion\ViewModels\BalanzaComprobacionViewModel;
use App\Contafacil\BalanzaComprobacion\ViewModels\BalanzaImpuestsoViewModel;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
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

    public function impuestos(Request $request, int $cliente)
    {
        $this->validate($request, [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date',
            'rfc'          => 'required|string',
        ]);

        $viewModel = new BalanzaImpuestsoViewModel(
            $cliente,
            $request->rfc,
            Carbon::parse($request->fecha_inicio)->startOfMonth(),
            Carbon::parse($request->fecha_fin)->endOfMonth()
        );

        return response()->json([
            'impuestos' => $viewModel->toArray(),
        ]);
    }

}
