<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Facturas\ValidarPolizasMes;
use App\Acciones\MesTrabajo\ResolverMesTrabajo;
use App\Contafacil\Gastos\ViewModels\ImpuestosGastosViewModel;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GastosController extends Controller
{

    public function impuestos(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfMonth();
        $fechaFin    = Carbon::parse($request->fecha_fin)->endOfMonth();
        $rfc         = $request->rfc;

        $modelo = new ImpuestosGastosViewModel($rfc, $fechaInicio, $fechaFin);

        return response()->json([
            'impuestos' => $modelo->toArray(),
        ]);
    }

    public function listadoFacturas(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha)->startOfMonth();
        $fechaFin    = Carbon::parse($request->fecha)->endOfMonth();
        $rfc         = $request->rfc;
        $clienteId   = $request->cliente_id;
        $cliente     = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        // Validación general de las polizas indivuales
        $mesTrabajo = ResolverMesTrabajo::ejecutar($fechaInicio, $cliente);
        if (!$mesTrabajo->polizas_validadas) {
            ValidarPolizasMes::ejecutar($fechaInicio, $fechaFin, $cliente);
            $mesTrabajo->polizas_validadas = true;
            $mesTrabajo->save();
        }

        $facturas = Factura::query()
            ->with([
                'facturasCliente' => function ($query) use ($cliente) {
                    $query->where('cliente_id', $cliente->id);
                },
                'complementoPagos',
                'complementoPagos.pagos',
                'complementoPagos.pagos.documentosRelacionados',
            ])
            ->whereBetween('fecha_emision', [
                $fechaInicio,
                $fechaFin,
            ])
            ->where('rfc_receptor', $rfc)
            ->whereIn('tipo_comprobante', ['I', 'E', 'i', 'e'])
            ->orderBy('fecha_emision')
            ->get();

        return response()->json([
            'facturas' => $facturas
        ]);
    }

}
