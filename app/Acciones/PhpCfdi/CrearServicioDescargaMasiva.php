<?php

namespace App\Acciones\PhpCfdi;

use App\Models\Cliente;
use Exception;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\Fiel;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\FielRequestBuilder;
use PhpCfdi\SatWsDescargaMasiva\Service;
use PhpCfdi\SatWsDescargaMasiva\WebClient\GuzzleWebClient;

class CrearServicioDescargaMasiva
{
    public static function ejecutar(Cliente $cliente): Service
    {
        $claveSat = $cliente->clavesSat()
            ->esFiel()
            ->sinCaducar()
            ->latest()
            ->first();

        if (!$claveSat) {
            throw new Exception("El cliente {$cliente->rfc} el cliente no tiene registrado una Fiel SAT");
        }

        $fiel = Fiel::create(
            Storage::get($claveSat->cer),
            Storage::get($claveSat->key),
            $claveSat->password
        );

        if (!$fiel->isValid()) {
            throw new Exception("El cliente {$cliente->rfc} no tiene FIEL SAT valida");
        }

        $webClient = new GuzzleWebClient();
        $requestBuilder = new FielRequestBuilder($fiel);

        return new Service($requestBuilder, $webClient);
    }
}
