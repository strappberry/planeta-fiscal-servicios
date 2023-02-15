<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Contafacil\Compartido\Datos\SaldosAFavorDatos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaldosAFavorController extends Controller
{
    public function index($clienteId)
    {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $saldos = $cliente->saldosAFavor()->get();
        $saldos = $saldos->append([
            'origen_descripcion',
        ]);

        return response()->json([
            'saldos' => $saldos,
        ]);
    }

    public function agregarSaldoAFavor(Request $request, $clienteId, $fecha)
    {
        $this->validate($request, [
            'numero_operacion' => 'required|string',
            'origen' => 'required|string',
            'tipo' => 'required|string',
            'fecha' => 'required|date',
            'fecha_presentacion' => 'date',
            'saldo_original' => 'required|numeric',
            'suma_comp_acred_ejer_ant' => 'numeric',
        ]);

        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $cliente->saldosAFavor()->create([
            'numero_operacion' => $request->get('numero_operacion'),
            'origen' => $request->get('origen'),
            'tipo' => $request->get('tipo'),
            'fecha' => $request->get('fecha'),
            'fecha_presentacion' => $request->get('fecha_presentacion', null),
            'saldo_original' => $request->get('saldo_original', 0),
            'suma_comp_acred_ejer_ant' => $request->get('suma_comp_acred_ejer_ant', 0),
        ]);

        return response()->json([
            'mensaje' => 'Saldo a favor agregado correctamente',
        ]);
    }

    public function catalogos()
    {
        return response()->json([
            'origen' => SaldosAFavorDatos::ORIGEN,
            'tipos' => SaldosAFavorDatos::TIPOS,
        ]);
    }
}
