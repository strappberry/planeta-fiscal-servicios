<?php

namespace App\Acciones\PhpCfdi;

use App\Models\Cliente;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\CfdiSatScraper\SatHttpGateway;
use PhpCfdi\CfdiSatScraper\SatScraper;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionManager;
use PhpCfdi\Credentials\Credential;

class CrearServicioScraper
{
    public static function ejecutar(Cliente $cliente): SatScraper
    {
        $claveSat = $cliente->clavesSat()
            ->esFiel()
            ->sinCaducar()
            ->latest()
            ->first();

        if (!$claveSat) {
            throw new Exception("El cliente {$cliente->rfc} no tiene FIEL SAT valida");
        }

        $credencial = Credential::create(
            Storage::get($claveSat->cer),
            Storage::get($claveSat->key),
            $claveSat->password
        );

        $client = new Client([
            'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
        ]);

        return new SatScraper(FielSessionManager::create($credencial), new SatHttpGateway($client));
    }
}
