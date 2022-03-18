<?php
namespace App\Enums;

class TipoPersona
{
    const FISICA = 'fisica';
    const MORAL = 'moral';

    public static function obtenerTipoPersona($rfc)
    {
        if (self::esPersonMoral($rfc)) {
            return __('dashboard.tipo_persona.' . self::MORAL);
        }

        return __('dashboard.tipo_persona.' . self::FISICA);
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