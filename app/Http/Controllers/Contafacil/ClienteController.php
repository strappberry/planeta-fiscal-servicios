<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Facturas\ValidarPolizasMes;
use App\Acciones\Facturas\VerificarYActualizarTipoIngresoPorRegimen;
use App\Acciones\MesTrabajo\ResolverMesTrabajo;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
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

    public function actualizarRegimenesFiscales(Request $request, string $clienteId, string $fecha)
    {
        $this->validate($request, [
            'regimenes_fiscales' => 'required|array',
        ]);

        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $fecha   = Carbon::parse($fecha)->startOfMonth();

        $cliente->regimenes_fiscales = $request->regimenes_fiscales;
        $cliente->save();

        $mesTrabajo = ResolverMesTrabajo::ejecutar($fecha, $cliente);
        if (!$mesTrabajo->polizas_validadas) {
            ValidarPolizasMes::ejecutar($fecha, $fecha->copy()->endOfMonth(), $cliente);
            $mesTrabajo->polizas_validadas = true;
            $mesTrabajo->save();
        } else if (!$mesTrabajo->bloqueado) {
            VerificarYActualizarTipoIngresoPorRegimen::ejecutar($cliente, $fecha);
        }

        return response()->json([
            'cliente' => $cliente,
        ]);
    }
}
