<?php

namespace App\Clientes\KontafacilTraits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait ObtenerClienteTrait
{
    /**
     * Obtener los datos del cliente registrado en el portal de PlanetaFiscal
     * atravez del modulo de Kontaafacil.
     *
     * Se obtienen los datos:
     * * id
     * * rfc
     * * razon_social
     */
    public function obtenerCliente(string $clienteId): Response
    {
        $token = config('planetafiscal.kontafacil_token_remoto');
        $url = config('planetafiscal.kontafacil_url');

        $respuesta = Http::withHeaders([
            'Authorization' => $token,
        ])->get(
            $url . "/servicios_conexion.php?accion=clientes/obtener-cliente&cliente={$clienteId}"
        );

        return $respuesta;
    }
}
