<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\BalanzaComprobacion\CalcularSaldosInicialesSiguienteMes;
use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Kontafacil\VerificarUsuarioPF;
use App\Http\Controllers\Controller;
use App\Models\MesTrabajo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MesTrabajoController extends Controller
{

    public function verificarMesTrabajo(string $cliente, string $fecha)
    {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($cliente);
        $fechaTrabajo = Carbon::parse($fecha)->startOfMonth();
        $bloqueado = false;

        $mesTrabajo = $cliente->mesesTrabajo()->where('fecha', $fechaTrabajo)->first();
        if ($mesTrabajo) {
            $bloqueado = $mesTrabajo->bloqueado;
        }

        return response()->json([
            'bloqueado' => $bloqueado,
        ]);
    }

    public function bloquearMesTrabajo(Request $request,string $cliente, string $fecha)
    {
        $this->validate($request, [
            'usuario' => 'required',
            'password' => 'required',
        ]);

        $puedeBloquearMes = VerificarUsuarioPF::ejecutar($request->usuario, $request->password);
        $cliente = ResolverClientePlanetaFiscal::ejecutar($cliente);

        if (!$puedeBloquearMes || !$cliente) {
            return response()->json([
                'error' => 'No se puede bloquear el mes porque el usuario o la contraseña son incorrectos.',
            ], 401);
        }

        $fechaTrabajo = Carbon::parse($fecha)->startOfMonth();
        $mesTrabajo = MesTrabajo::updateOrCreate(
            [
                'cliente_id' => $cliente->id,
                'fecha'      => $fechaTrabajo,
            ],
            [
                'cliente_id' => $cliente->id,
                'fecha'      => $fechaTrabajo,
                'bloqueado'  => true,
            ]
        );

        CalcularSaldosInicialesSiguienteMes::ejecutar($fechaTrabajo, $cliente);

        return response()->json([
            'bloqueado' => $mesTrabajo->bloqueado,
        ]);
    }

    public function desbloquearMesTrabajo(Request $request, string $cliente, string $fecha)
    {
        $this->validate($request, [
            'usuario' => 'required',
            'password' => 'required',
        ]);

        $puedeDesbloquearMes = VerificarUsuarioPF::ejecutar($request->usuario, $request->password);
        $cliente = ResolverClientePlanetaFiscal::ejecutar($cliente);

        if (!$puedeDesbloquearMes || !$cliente) {
            return response()->json([
                'error' => 'No se puede desbloquear el mes porque el usuario o la contraseña son incorrectos.',
            ], 401);
        }

        $fechaTrabajo = Carbon::parse($fecha)->startOfMonth();
        $mesTrabajo = MesTrabajo::updateOrCreate(
            [
                'cliente_id' => $cliente->id,
                'fecha'      => $fechaTrabajo,
            ],
            [
                'cliente_id' => $cliente->id,
                'fecha'      => $fechaTrabajo,
                'bloqueado'  => false,
            ]
        );

        return response()->json([
            'bloqueado' => $mesTrabajo->bloqueado,
        ]);
    }
}
