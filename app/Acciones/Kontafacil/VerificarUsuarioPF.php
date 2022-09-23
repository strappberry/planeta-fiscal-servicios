<?php

namespace App\Acciones\Kontafacil;

use App\Clientes\KontafacilApi;

class VerificarUsuarioPF
{
    /**
     * Verificar si el usuario de planetafiscal existe y si la contraseña es correcta.
     */
    public static function ejecutar(string $usuario, string $password): bool
    {
        $kontafacilApi = new KontafacilApi();
        $respuesta = $kontafacilApi->validarUsuario($usuario, $password);

        if (!$respuesta->ok()) {
            return false;
        }

        $datos = $respuesta->json();

        return $datos['valido'];
    }
}
