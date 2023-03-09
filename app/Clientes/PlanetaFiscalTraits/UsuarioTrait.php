<?php

namespace App\Clientes\PlanetaFiscalTraits;

use Illuminate\Support\Facades\Http;

trait UsuarioTrait
{
    public function validarUsuarioPassword(string $usuario, string $password): bool
    {
        $respuesta = Http::planetaFiscalApi()->asForm()->post(
            "/usuarios/validar-usuario-password",
            [
                'usuario'  => $usuario,
                'password' => $password,
            ]
        );

        return $respuesta->ok();
    }
}
