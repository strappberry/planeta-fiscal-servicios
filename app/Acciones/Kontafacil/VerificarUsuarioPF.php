<?php

namespace App\Acciones\Kontafacil;

use App\Clientes\PlanetaFiscalApi;

class VerificarUsuarioPF
{
    /**
     * Verificar si el usuario de planetafiscal existe y si la contraseña es correcta.
     */
    public static function ejecutar(string $usuario, string $password): bool
    {
        return (new PlanetaFiscalApi())->validarUsuarioPassword($usuario, $password);
    }
}
