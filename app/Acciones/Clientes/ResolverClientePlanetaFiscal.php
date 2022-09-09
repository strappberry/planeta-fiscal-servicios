<?php

namespace App\Acciones\Clientes;

use App\Clientes\KontafacilApi;
use App\Models\Cliente;

class ResolverClientePlanetaFiscal
{

    /**
     * Verificar si el id del cliente de planetafiscal esta registrado en la base de datos,
     * si el cliente esta registrado, se retorna una instancia de la clase Cliente,
     * si el cliente no esta registrado se consulta la api de planetafiscal para obtener
     * los datos del cliente y registrar su id de planetafiscal, razon social y rfc.
     *
     * @param string $clienteId Id del cliente de planetafiscal
     * @return Cliente instancia del cliente registrado en la base de datos
     */
    public static function ejecutar(string $clienteId): ?Cliente
    {
        $cliente = Cliente::where('planetafiscal_id', $clienteId)->first();

        if (!$cliente) {
            $kontafacilApi = new KontafacilApi();
            $respuesta = $kontafacilApi->obtenerCliente($clienteId);
            if (!$respuesta->ok()) {
                return null;
            }

            $datosCliente = $respuesta->json();

            $cliente = Cliente::updateOrCreate(
                ['rfc' => $datosCliente['rfc']],
                [
                    'rfc'              => $datosCliente['rfc'],
                    'razon_social'     => $datosCliente['razon_social'],
                    'planetafiscal_id' => $datosCliente['id'],
                ]
            );
        }

        return $cliente;
    }

}
