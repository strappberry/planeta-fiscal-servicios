<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Facturas\ResolverFacturaCliente;
use App\Acciones\Facturas\ValidarPolizasMes;
use App\Acciones\MesTrabajo\ResolverMesTrabajo;
use App\Contafacil\Facturas\ViewModels\PolizaAutomaticaFacturaViewModel;
use App\Contafacil\Facturas\ViewModels\ValidacionPolizaAutomaticaFacturaViewModel;
use App\Contafacil\Ventas\ViewModels\ImpuestosViewModel;
use App\Contafacil\Ventas\ViewModels\ListadoFacturasVentas;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function impuestos(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfMonth();
        $fechaFin    = Carbon::parse($request->fecha_fin)->endOfMonth();
        $rfc         = $request->rfc;

        $modelo = new ImpuestosViewModel($rfc, $fechaInicio, $fechaFin);

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

        // Validación general de las polizas indivuales, se validará solo una vez por mes
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
        ->where('rfc_emisor', $rfc)
        ->whereIn('tipo_comprobante', ['I', 'E', 'i', 'e'])
        ->orderBy('fecha_emision')
        ->get();

        return response()->json([
            'facturas' => $facturas
        ]);
    }
}
