<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\SaldosAFavor\AgregarAcreditamientoSaldoAFavor;
use App\Contafacil\Compartido\Datos\SaldosAFavorDatos;
use App\Http\Controllers\Controller;
use App\Models\SaldoAFavor;
use Illuminate\Http\Request;

class SaldosAFavorController extends Controller
{
    public function index($clienteId)
    {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $saldos = $cliente->saldosAFavor()->with('acreditamientos')->get();
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

    public function agregarAcreditamiento(SaldoAFavor $saldoAFavor, Request $request)
    {
        $this->validate($request, [
            'importe' => "required|numeric|min:0|max:{$saldoAFavor->remanente}",
            'periodo' => 'required|string',
            'concepto' => 'required|string',
        ]);

        AgregarAcreditamientoSaldoAFavor::ejecutar($saldoAFavor, [
            'importe' => $request->get('importe', 0),
            'periodo' => $request->get('periodo', null),
            'concepto' => $request->get('concepto', null),
        ]);

        return response()->json([
            'mensaje' => 'Acreditamiento agregado correctamente',
        ]);
    }

    public function catalogos()
    {
        return response()->json([
            'origen' => SaldosAFavorDatos::ORIGEN,
            'tipos' => SaldosAFavorDatos::TIPOS,
            'conceptos_acreditamiento' => SaldosAFavorDatos::CONCEPTOS_ACREDITAMIENTO,
        ]);
    }
}
