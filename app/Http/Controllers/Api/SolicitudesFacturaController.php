<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SolicitudDescargaRequest;
use App\Jobs\ProcesarSolicitudDescargaJob;
use App\Models\Cliente;
use App\Models\SolicitudDescarga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SolicitudesFacturaController extends Controller
{

    public function crearSolicitudDescarga(SolicitudDescargaRequest $request)
    {
        $cliente = Cliente::where('rfc', $request->cliente)->firstOrFail();

        // Verificar si hay una solicitud activa
        $solicitud = $cliente->solicitudesDescarga()
            ->whereNotIn('status', [
                SolicitudDescarga::STATUS_PROCESADO,
                SolicitudDescarga::STATUS_ERROR_AL_PROCESAR,
                SolicitudDescarga::STATUS_CANCELADO,
                SolicitudDescarga::STATUS_SIN_FACTURAS,
            ])
            ->count();

        if ($solicitud > 0) {
            return response()->json([
                'message' => 'Ya existe una solicitud activa para este cliente',
            ], 409);
        }

        $descargarDesde = Carbon::parse($request->fecha_inicio, config('app.timezone'))->startOfDay();
        $descargarHasta = Carbon::parse($request->fecha_fin, config('app.timezone'))->endOfDay();

        if ($descargarDesde->gt($descargarHasta)) {
            return response()->json([
                'error' => 'La fecha de inicio debe ser menor a la fecha de fin',
            ], 400);
        }

        if ($descargarDesde->lt(Carbon::parse('2018-01-01'))) {
            return response()->json([
                'error' => 'No puede descargar facturas antes del 2018',
            ], 409);
        }

        $solicitud = SolicitudDescarga::create([
            'cliente_id'          => $cliente->id,
            'fecha_inicio'        => $descargarDesde->format('Y-m-d H:i:s'),
            'fecha_fin'           => $descargarHasta->format('Y-m-d H:i:s'),
            'solicitado_por'      => $request->ejecutivo ?? null,
            'descarga_automatica' => false,
            'status'              => SolicitudDescarga::STATUS_PENDIENTE,
        ]);

        dispatch(new ProcesarSolicitudDescargaJob($solicitud->id));

        return response()->json([
            'solicitud' => $solicitud,
        ]);
    }

    public function listarSolicitudes(Request $request)
    {
        $cliente = Cliente::where('rfc', $request->cliente)->firstOrFail();

        $solicitudes = $cliente->solicitudesDescarga()
            ->latest()
            ->limit(30)
            ->get();

        return response()->json([
            'solicitudes' => $solicitudes,
        ]);
    }

    public function cancelarSolicitud(Request $request)
    {
        $this->validate($request, [
            'solicitud_descarga_id' => 'required|integer',
        ]);

        $solicitud = SolicitudDescarga::findOrFail($request->solicitud_descarga_id);
        $solicitud->status = SolicitudDescarga::STATUS_CANCELADO;
        $solicitud->save();

        return response()->json([
            'message' => 'Solicitud cancelada',
            'solicitud' => $solicitud,
        ]);
    }
}
