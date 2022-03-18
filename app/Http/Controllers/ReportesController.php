<?php

namespace App\Http\Controllers;

use App\Enums\ReportesEnum;
use App\Exports\ReporteSimplificadoExport;
use App\Http\Requests\Api\SolicitudReporteRequest;
use App\Models\Cliente;
use App\Models\SolicitudReporte;
use App\Reportes\ReporteElectronica;
use App\Reportes\ReporteSimplificado;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ReportesController extends Controller
{
    public function crearSolicitudReporte(SolicitudReporteRequest $request)
    {
        if (!in_array($request->tipo, [
            ReportesEnum::SIMPLIFICADO,
            ReportesEnum::ELECTRONICA,
        ])) {
            return response()->json([
                'message' => 'Tipo de reporte no soportado',
            ], 404);
        }

        $solicitudReporte = SolicitudReporte::create([
            'tipo' => $request->tipo,
            'rfc' => $request->rfc,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'token' => Str::uuid(),
        ]);

        $url = route(
            'reportes.atender-solicitud-reporte',
            [
                'token' => $solicitudReporte->token,
            ]
        );

        return response()->json([
            'message' => 'Solicitud de reporte creada',
            'url' => $url,
        ], 201);
    }

    public function atenderSolicitudReporte(string $token)
    {
        $solicitudReporte = SolicitudReporte::where('token', $token)->firstOrFail();

        if ($solicitudReporte->tipo === ReportesEnum::SIMPLIFICADO) {
            $reporte = new ReporteSimplificado (
                $solicitudReporte->rfc,
                new DateTimeImmutable($solicitudReporte->fecha_inicio . ' 00:00:00'),
                new DateTimeImmutable($solicitudReporte->fecha_fin . ' 23:59:59')
            );

            $solicitudReporte->delete();

            return Excel::download(
                new ReporteSimplificadoExport($reporte),
                $reporte->nombreArchivo()
            );
        }

        abort(404);
    }

    public function reporteWeb(string $tipo, string $rfc, string $fechaInicio, string $fechaFin)
    {
        if (!in_array($tipo, [
            ReportesEnum::SIMPLIFICADO,
            ReportesEnum::ELECTRONICA,
        ])) {
            abort(404);
        }

        if ($tipo == ReportesEnum::SIMPLIFICADO) {
            $reporte = new ReporteSimplificado(
                $rfc,
                new DateTimeImmutable($fechaInicio . ' 00:00:00'),
                new DateTimeImmutable($fechaFin . ' 23:59:59')
            );
        }

        if ($tipo == ReportesEnum::ELECTRONICA) {
            $reporte = new ReporteElectronica(
                $rfc,
                new DateTimeImmutable($fechaInicio . ' 00:00:00'),
                new DateTimeImmutable($fechaFin . ' 23:59:59')
            );
        }


        return Excel::download(
            new ReporteSimplificadoExport($reporte),
            $reporte->nombreArchivo()
        );
    }

}
