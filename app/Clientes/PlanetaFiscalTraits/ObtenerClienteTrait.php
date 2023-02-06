<?php

namespace App\Clientes\PlanetaFiscalTraits;

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
    public function obtenerCliente(string $clienteId): ?array
    {

        $respuesta = Http::planetaFiscalApi()->get("/cliente/consultar/id/{$clienteId}");

        if ($respuesta->failed()) return null;

        $datos = $respuesta->json();
        $cliente = $datos['planeta_cliente'];

        return [
            'id'                   => $cliente['planeta_id'],
            'rfc'                  => $cliente['rfc'],
            'razon_social'         => $cliente['razon_social'],
            'regimen'              => $cliente['regimen'],
            'activo'               => $cliente['activo'],
            'planeta_ejecutivo_id' => $cliente['planeta_ejecutivo_id'],
            'planeta_ejecutivo'    => [
                'id'     => $cliente['planeta_ejecutivo']['planeta_id'],
                'nombre' => $cliente['planeta_ejecutivo']['nombre'],
                'email'  => $cliente['planeta_ejecutivo']['email'],
                'activo' => $cliente['planeta_ejecutivo']['activo'],
            ],
        ];
    }
}
