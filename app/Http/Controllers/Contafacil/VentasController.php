<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\Facturas\ValidarPolizasMes;
use App\Acciones\MesTrabajo\ResolverMesTrabajo;
use App\Contafacil\Ventas\ViewModels\ImpuestosViewModel;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaCliente;
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

        $facturasIds = FacturaCliente::query()
            ->select(
                'factura_clientes.id as id',
                'factura_id as factura_id',
                'facturas.rfc_emisor as rfc_emisor',
                'factura_clientes.fecha_emision as fecha_emision',
                'factura_clientes.fecha_pago as fecha_pago',
                'facturas.tipo_comprobante as tipo_comprobante'
            )
            ->join('facturas', 'facturas.id', '=', "factura_clientes.factura_id")
            ->esVenta()
            ->where('facturas.rfc_emisor', $rfc)
            ->whereIn('facturas.tipo_comprobante', ['I', 'E', 'i', 'e'])
            ->where(function ($query) use($fechaInicio, $fechaFin) {
                return $query
                    ->whereBetween('factura_clientes.fecha_emision', [
                        $fechaInicio,
                        $fechaFin,
                    ])
                    ->orWhereBetween('factura_clientes.fecha_pago', [
                        $fechaInicio,
                        $fechaFin,
                    ]);
            })
            ->orderBy('fecha_emision')
            ->get();

        $facturas = Factura::query()
            ->with([
                'facturasCliente' => function ($query) use ($cliente) {
                    $query->where('cliente_id', $cliente->id);
                },
                'complementoPagos',
                'complementoPagos.pagos',
                'complementoPagos.pagos.documentosRelacionados',
            ])
            ->whereIn('id', $facturasIds->pluck('factura_id'))
            ->get();

        return response()->json([
            'facturas' => $facturas,
        ]);
    }
}
