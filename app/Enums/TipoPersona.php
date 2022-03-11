<?php
namespace App\Enums;

class TipoPersona
{
    const FISICA = 'fisica';
    const MORAL = 'moral';

    public static function obtenerTipoPersona($rfc)
    {
        if (self::esPersonMoral($rfc)) {
            return self::MORAL;
        }

        return self::FISICA;
    }

    public static function esPersonFisica(string $rfc): bool
    {
        return strlen($rfc) == 13;
    }

    public static function esPersonMoral(string $rfc): bool
    {
        return strlen($rfc) == 12;
    }
}