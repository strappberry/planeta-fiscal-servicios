<?php

namespace App\Acciones\Clientes;

use App\Clientes\PlanetaFiscalApi;
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
            $consulta = (new PlanetaFiscalApi())->obtenerCliente($clienteId);
            if (!$consulta) return null;

            $cliente = Cliente::updateOrCreate(
                ['planetafiscal_id' => $consulta['id']],
                [
                    'rfc'              => $consulta['rfc'],
                    'razon_social'     => $consulta['razon_social'],
                    'planetafiscal_id' => $consulta['id'],
                    'regimen_fiscal'   => $consulta['regimen'],
                ]
            );
        }

        return $cliente;
    }

}
