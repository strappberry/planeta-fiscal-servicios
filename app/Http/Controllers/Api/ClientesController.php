<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RecibirClientesRequest;
use App\Http\Requests\Api\SubirFielRequest;
use App\Jobs\ProcesarSolicitudDescargaJob;
use App\Models\ClaveSat;
use App\Models\Cliente;
use App\Models\SolicitudDescarga;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\Credentials\Credential;

class ClientesController extends Controller
{
    
    public function recibirClientes(RecibirClientesRequest $request)
    {
        $urlPlaneta = "https://www.planetafiscal.com/SPF/";
        foreach ($request->clientes as $datosCliente) {
            try {
                $cer =  file_get_contents($urlPlaneta . $datosCliente['cer']);
                $key =  file_get_contents($urlPlaneta . $datosCliente['key']);

                $credencial = Credential::create(
                    $cer,
                    $key,
                    $datosCliente['password'],
                );

                if (!$credencial->isFiel()) continue;
                if (!$credencial->certificate()->validOn()) continue;

                $cliente = Cliente::where('rfc', $datosCliente['rfc'])->first();
                if (!$cliente) {
                    $cliente = Cliente::create([
                        'razon_social' => $datosCliente['razon_social'],
                        'rfc' => $datosCliente['rfc'],
                        'regimen_fiscal' => $datosCliente['regimen_fiscal'],
                        'obtener_facturas' => true,
                    ]);
                }

                $rutaCer = 'archivos/' . $cliente->rfc . '/fiel/' . Str::uuid() . '.cer';
                $rutaKey = 'archivos/' . $cliente->rfc . '/fiel/' . Str::uuid() . '.key';

                Storage::put($rutaCer, $cer);
                Storage::put($rutaKey, $key);

                $cliente->clavesSat()->create([
                    'cer' => $rutaCer,
                    'key' => $rutaKey,
                    'password' => $datosCliente['password'],
                    'activo' => true,
                    'caducidad' => $credencial->certificate()->validToDateTime()->format('Y-m-d H:i:s'),
                    'tipo' => ClaveSat::TIPO_FIEL,
                ]);

            } catch (Exception $e) {
                Log::error(
                    "Error al recibir cliente: " . $datosCliente['razon_social'] . " - " . $e->getMessage()
                );
            }
        }

        return response()->json([
            'mensaje' => 'Clientes recibidos',
        ]);
    }

    public function subirFiel(SubirFielRequest $request)
    {
        $cliente = Cliente::where('rfc', $request->rfc)->first();

        if (!$cliente) {
            $cliente = Cliente::create([
                'razon_social' => '',
                'rfc' => $request->rfc,
                'regimen_fiscal' => $request->regimen_fiscal,
                'obtener_facturas' => true,
            ]);

            $cliente->refresh();
        }

        $respuesta = [
            'valido' => true,
            'vigencia' => '',
            'mensajes' => [],
        ];

        try {
            $fiel = Credential::create(
                base64_decode($request->archivo_cer),
                base64_decode($request->archivo_key),
                $request->password,
            );
            if (!$fiel->isFiel()) {
                $respuesta['valido'] = false;
                $respuesta['mensajes'][] = 'El archivo no es un fiel';
            }
            if (!$fiel->certificate()->validOn()) {
                $respuesta['valido'] = false;
                $respuesta['mensajes'][] = 'El fiel ha caducado';
            }
            if ($fiel->rfc() != $cliente->rfc) {
                $respuesta['valido'] = false;
                $respuesta['mensajes'][] = 'El fiel no corresponde al RFC del cliente';
            }

            if (!$respuesta['valido']) {
                return response()->json($respuesta);
            }

            $respuesta['vigencia'] = $fiel->certificate()->validToDateTime()->format('Y-m-d H:i:s');

            $rutaCer = 'archivos/' . $cliente->rfc . '/fiel/' . Str::uuid() . '.cer';
            $rutaKey = 'archivos/' . $cliente->rfc . '/fiel/' . Str::uuid() . '.key';

            Storage::put($rutaCer, base64_decode($request->archivo_cer));
            Storage::put($rutaKey, base64_decode($request->archivo_key));

            $certificadosFiel = $cliente->clavesSat()->where('tipo', ClaveSat::TIPO_FIEL)->get();
            foreach ($certificadosFiel as $certificado) {
                $certificado->eliminarArchivos();
                $certificado->delete();
            }

            $cliente->razon_social = $fiel->legalName();
            $cliente->regimen_fiscal = $request->regimen_fiscal;
            $cliente->save();

            $cliente->clavesSat()->create([
                'cer' => $rutaCer,
                'key' => $rutaKey,
                'password' => $request->password,
                'activo' => true,
                'caducidad' => $fiel->certificate()->validToDateTime()->format('Y-m-d H:i:s'),
                'tipo' => ClaveSat::TIPO_FIEL,
            ]);

            $descargarDesde = now()->startOfYear()->startOfDay();
            $descargarHasta = now()->subDay()->endOfDay();
            $solicitud = SolicitudDescarga::create([
                'cliente_id'          => $cliente->id,
                'fecha_inicio'        => $descargarDesde->format('Y-m-d H:i:s'),
                'fecha_fin'           => $descargarHasta->format('Y-m-d H:i:s'),
                'descarga_automatica' => true,
                'status'              => SolicitudDescarga::STATUS_PENDIENTE,
            ]);

            dispatch(new ProcesarSolicitudDescargaJob($solicitud->id));
        } catch (Exception $e) {
            $respuesta['valido'] = false;
            $respuesta['mensajes'][] = 'La contrase&ntilde;a es incorrecta';
        }

        return response()->json($respuesta);
    }

    public function informacionCliente(string $rfc)
    {
        $cliente = Cliente::where('rfc', $rfc)->firstOrFail();
        $fiel    = $cliente->clavesSat()->where('tipo', ClaveSat::TIPO_FIEL)->latest()->first();

        $datos = [
            'rfc'          => $cliente->rfc,
            'razon_social' => $cliente->razon_social,
            'vigencia'     => '',
            'facturas'     => $cliente->facturas()->count(),
        ];

        if ($fiel) {
            $datos['vigencia'] = $fiel->caducidad->format('Y-m-d H:i:s');
        }

        return response()->json([
            'cliente' => $datos,
        ]);
    }

}
