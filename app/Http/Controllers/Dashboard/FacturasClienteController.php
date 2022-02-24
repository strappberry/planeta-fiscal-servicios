<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\FacturasExport;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Reportes\ReporteFacturas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FacturasClienteController extends Controller
{

    public function index(Cliente $cliente)
    {
        return view('dashboard.facturas_cliente.index', compact('cliente'));
    }

    public function descargarFacturas(Cliente $cliente)
    {
        $facturas = $cliente->facturas()->select('uuid')->get();
        $uuids = $facturas->pluck('uuid')->toArray();
        $reporte = new ReporteFacturas($uuids);

        return Excel::download(
            new FacturasExport($reporte),
            $reporte->nombreArchivo()
        );
    }

}
