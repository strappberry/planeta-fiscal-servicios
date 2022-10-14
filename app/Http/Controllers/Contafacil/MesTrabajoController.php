<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Kontafacil\VerificarUsuarioPF;
use App\Acciones\MesTrabajo\BloquearMesTrabajo;
use App\Acciones\MesTrabajo\ResolverMesTrabajo;
use App\Contafacil\MesTrabajo\ViewModels\RangoMesesTrabajoViewModel;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MesTrabajoController extends Controller
{

    public function verificarMesTrabajo(string $cliente, string $fecha)
    {
        $cliente      = ResolverClientePlanetaFiscal::ejecutar($cliente);
        $fechaTrabajo = Carbon::parse($fecha)->startOfMonth();
        $mesTrabajo   = ResolverMesTrabajo::ejecutar($fechaTrabajo, $cliente);

        return response()->json([
            'bloqueado' => $mesTrabajo->bloqueado,
        ]);
    }

    public function obtenerHistorico(string $cliente, string $fecha)
    {
        $cliente      = ResolverClientePlanetaFiscal::ejecutar($cliente);
        $fechaTrabajo = Carbon::parse($fecha)->startOfMonth();
        $mesTrabajo   = ResolverMesTrabajo::ejecutar($fechaTrabajo, $cliente);
        $historico    = $mesTrabajo->historico()->orderBy('id', 'desc')->get();

        return response()->json([
            'historico' => $historico,
        ]);
    }

    public function bloquearMesTrabajo(Request $request,string $cliente, string $fecha)
    {
        $this->validate($request, [
            'usuario'    => 'required',
            'password'   => 'required',
            'comentario' => 'required',
            'cascada'    => 'required',
        ]);

        $puedeBloquearMes = VerificarUsuarioPF::ejecutar($request->usuario, $request->password);
        $cliente          = ResolverClientePlanetaFiscal::ejecutar($cliente);

        if (!$puedeBloquearMes || !$cliente) {
            return response()->json([
                'error' => 'No se puede bloquear el mes porque el usuario o la contraseña son incorrectos.',
            ], 401);
        }

        $fechaTrabajo = Carbon::parse($fecha)->startOfMonth();
        $mesTrabajo   = ResolverMesTrabajo::ejecutar($fechaTrabajo, $cliente);
        BloquearMesTrabajo::ejecutar($mesTrabajo, $cliente, $request->cascada);

        $mesTrabajo->historico()->create([
            'comentario' => $request->comentario,
            'usuario_planeta_fiscal' => $request->usuario,
        ]);

        return response()->json([
            'bloqueado' => $mesTrabajo->bloqueado,
        ]);
    }

    public function desbloquearMesTrabajo(Request $request, string $cliente, string $fecha)
    {
        $this->validate($request, [
            'usuario'    => 'required',
            'password'   => 'required',
            'comentario' => 'required',
        ]);

        $puedeDesbloquearMes = VerificarUsuarioPF::ejecutar($request->usuario, $request->password);
        $cliente = ResolverClientePlanetaFiscal::ejecutar($cliente);

        if (!$puedeDesbloquearMes || !$cliente) {
            return response()->json([
                'error' => 'No se puede desbloquear el mes porque el usuario o la contraseña son incorrectos.',
            ], 401);
        }

        $fechaTrabajo = Carbon::parse($fecha)->startOfMonth();
        $mesTrabajo   = ResolverMesTrabajo::ejecutar($fechaTrabajo, $cliente);
        $mesTrabajo->bloqueado = false;
        $mesTrabajo->save();

        $mesTrabajo->historico()->create([
            'comentario' => $request->comentario,
            'usuario_planeta_fiscal' => $request->usuario,
        ]);

        return response()->json([
            'bloqueado' => $mesTrabajo->bloqueado,
        ]);
    }

    public function obtenerAnioTrabajo(string $cliente, string $fecha)
    {
        $cliente     = ResolverClientePlanetaFiscal::ejecutar($cliente);
        $fechaInicio = Carbon::parse($fecha)->startOfYear();
        $fechaFin    = Carbon::parse($fecha)->endOfYear();

        $modelo = new RangoMesesTrabajoViewModel($cliente, $fechaInicio, $fechaFin);

        return response()->json([
            'modelo' => $modelo->toArray(),
        ]);
    }
}
