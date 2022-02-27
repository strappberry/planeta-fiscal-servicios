<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RecibirClientesRequest;
use App\Models\ClaveSat;
use App\Models\Cliente;
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

}
