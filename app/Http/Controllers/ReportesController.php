<?php

namespace App\Http\Controllers;

use App\Exports\ReporteSimplificadoExport;
use App\Models\Cliente;
use App\Reportes\ReporteSimplificado;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{

    public function simplificado(string $rfc, string $fechaInicio, string $fechaFin)
    {
        $cliente = Cliente::where('rfc', $rfc)->first();

        $reporte = new ReporteSimplificado (
            $rfc,
            new DateTimeImmutable($fechaInicio . ' 00:00:00'),
            new DateTimeImmutable($fechaFin . ' 23:59:59')
        );

        return Excel::download(
            new ReporteSimplificadoExport($reporte),
            $reporte->nombreArchivo()
        );
    }

}
