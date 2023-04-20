<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Facturas\ValidarPolizasMes;
use App\Acciones\MesTrabajo\ResolverMesTrabajo;
use App\Contafacil\Gastos\ViewModels\ImpuestosGastosViewModel;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaCliente;
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

        $facturas = $cliente->facturasCliente()
            ->with([
                'factura',
                'factura.complementoPagos',
                'factura.complementoPagos.pagos',
                'factura.complementoPagos.pagos.documentosRelacionados',
            ])
            ->esGasto()
            ->where(function ($query) use($fechaInicio, $fechaFin) {
                return $query
                    ->whereBetween('fecha_emision', [$fechaInicio, $fechaFin])
                    ->orWhereBetween('fecha_pago', [$fechaInicio, $fechaFin]);
            })
            ->orderBy('considerado', 'asc')
            ->orderBy('fecha_emision', 'asc')
            ->get();

        return response()->json([
            'facturas' => $facturas,
        ]);
    }

}
