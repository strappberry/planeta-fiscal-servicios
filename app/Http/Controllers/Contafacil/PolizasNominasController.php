<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Acciones\PolizasNominas\GuardarPolizasNominasAccion;
use App\Contafacil\PolizasNominas\ViewModels\CuentasDesdeArchivoViewModel;
use App\Contafacil\PolizasNominas\ViewModels\PolizasNominasMensualViewModel;
use App\Http\Controllers\Controller;
use App\Imports\NomilineaAcumuladoImport;
use App\Imports\SoloPrimeraPaginaImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PolizasNominasController extends Controller
{
    public function polizasNomina(Request $request, int $clienteId, string $fecha)
    {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $fecha = Carbon::parse($fecha)->startOfMonth();

        $modelo = new PolizasNominasMensualViewModel(
            $cliente,
            $fecha
        );

        return response()->json([
            'modelo' => $modelo->toArray(),
        ]);
    }

    public function subirExcel(Request $request)
    {
        $this->validate($request, [
            'archivo_excel' => 'required|file|mimes:xlsx,xls',
            'isn' => 'required|integer',
        ]);

        $archivos = Excel::toArray(
            new SoloPrimeraPaginaImport(NomilineaAcumuladoImport::class),
            request()->file('archivo_excel')
        );

        $modelo = new CuentasDesdeArchivoViewModel(
            $archivos[0],
            (int) $request->input('isn', 3),
        );

        return response()->json([
            'modelo' => $modelo->toArray(),
        ]);
    }

    public function confirmarDatosExcel(Request $request, int $clienteId, string $fecha)
    {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $fecha = Carbon::parse($fecha)->startOfMonth();

        GuardarPolizasNominasAccion::ejecutar(
            $cliente,
            $fecha,
            $request->input('datos', [])
        );

        return response()->json([
            'mensaje' => 'Datos confirmados',
        ]);
    }
}
