<?php

namespace App\Clientes\KontafacilTraits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait UsuarioTrait
{
    public function validarUsuario(string $usuario, string $password): Response
    {
        $token = config('planetafiscal.kontafacil_token_remoto');
        $url = config('planetafiscal.kontafacil_url');

        $respuesta = Http::withHeaders([
            'Authorization' => $token,
            'Accept'        => 'application/json',
        ])->asForm()->post(
            $url . "/servicios_conexion.php?accion=usuario/validar-usuario",
            [
                'usuario'  => $usuario,
                'password' => $password,
            ]
        );

        return $respuesta;
    }
}
