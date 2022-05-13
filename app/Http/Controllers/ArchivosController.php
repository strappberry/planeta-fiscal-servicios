<?php
namespace App\Http\Controllers;

use App\Enums\SolicitudArchivoEnum;
use App\Http\Requests\Api\SolicitudArchivosRequest;
use App\Models\Factura;
use App\Models\SolicitudArchivo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ArchivosController extends Controller
{

    public function crearSolicitudArchivos(SolicitudArchivosRequest $request)
    {
        $solicitudArchivos = SolicitudArchivo::create([
            'tipo' => $request->tipo,
            'rfc' => $request->rfc,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'token' => Str::uuid(),
        ]);

        $facturas =  Factura::query()
        ->select('uuid')
        ->where(function ($query) use($solicitudArchivos) {
            return $query->where('rfc_emisor', $solicitudArchivos->rfc)
                ->orWhere('rfc_receptor', $solicitudArchivos->rfc);
        })
        ->where('fecha_emision', '>=', $solicitudArchivos->fecha_inicio)
        ->where('fecha_emision', '<=', $solicitudArchivos->fecha_fin)
        ->vigentes()
        ->get();
        
        $archivosEncontrados = 0;
        foreach ($facturas as $factura) {
            if (Storage::exists('/facturas/xmls/' . $factura->uuid . '.xml')) {
                $archivosEncontrados++;
            }
        }

        $url = route(
            'archivos.atender-solicitud-archivos',
            [
                'token' => $solicitudArchivos->token,
            ]
        );

        return response()->json([
            'message' => 'Solicitud de reporte creada',
            'url' => $url,
            'cantidad_facturas' => $facturas->count(),
            'cantidad_archivos_encontrados' => $archivosEncontrados,
        ], 201);
    }

    public function atenderSolicitudArchivos(string $token)
    {
        $solicitudArchivos = SolicitudArchivo::where('token', $token)->firstOrFail();

        if ($solicitudArchivos->tipo == SolicitudArchivoEnum::PAQUETE_XML) {
            $emitidas = Factura::query()
                ->select('uuid')
                ->where('rfc_emisor', $solicitudArchivos->rfc)
                ->where('fecha_emision', '>=', $solicitudArchivos->fecha_inicio)
                ->where('fecha_emision', '<=', $solicitudArchivos->fecha_fin)
                ->vigentes()
                ->get();
            $emitidas = $emitidas->pluck('uuid')->toArray();

            $recibidas = Factura::query()
                ->select('uuid')
                ->where('rfc_receptor', $solicitudArchivos->rfc)
                ->where('fecha_emision', '>=', $solicitudArchivos->fecha_inicio)
                ->where('fecha_emision', '<=', $solicitudArchivos->fecha_fin)
                ->vigentes()
                ->get();
            $recibidas = $recibidas->pluck('uuid')->toArray();
            if (count($emitidas) == 0 && count($recibidas) == 0) {
                return "No se encontraron facturas";
            }

            $zip = new ZipArchive();
            $xml = public_path("{$solicitudArchivos->rfc}-{$solicitudArchivos->fecha_inicio}-{$solicitudArchivos->fecha_fin}.zip");
            $solicitudArchivos->delete();
            if ($zip->open($xml, ZipArchive::CREATE) === false) {
                abort(404);
            }

            foreach($emitidas as $uuid) {
                if (Storage::exists('/facturas/xmls/' . $uuid . '.xml')) {
                    $zip->addFile(
                        Storage::path('/facturas/xmls/' . $uuid . '.xml'),
                        'emitido/' . $uuid . '.xml'
                    );
                }
            }
            foreach($recibidas as $uuid) {
                if (Storage::exists('/facturas/xmls/' . $uuid . '.xml')) {
                    $zip->addFile(
                        Storage::path('/facturas/xmls/' . $uuid . '.xml'),
                        'recibido/' . $uuid . '.xml'
                    );
                }
            }
            $zip->close();

            return response()->download($xml)->deleteFileAfterSend();
        }

        abort(404);
    }

    public function descargarFactura(string $uuid)
    {
        if ( Storage::exists("/facturas/xmls/{$uuid}.xml") ) {
            return response()->download(
                Storage::path("/facturas/xmls/{$uuid}.xml"),
                "{$uuid}.xml"
            );
        }

        return "No se encontró la factura con el UUID {$uuid}";
    }

}
