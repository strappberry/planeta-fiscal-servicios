<?php

namespace App\Enums;

use App\Contafacil\Facturas\ViewModels\DeterminacionDelImpuestoActividadEmpresarialViewModel;
use Exception;

class TipoIngreso
{
    const ACTIVIDAD_EMPRESARIAL = 'actividad_empresarial';
    const ARRENDAMIENTO         = 'arrendamiento';
    const PLATAFORMAS_DIGITALES = 'plataformas_digitales';

    public static function descripcion(string $tipoIngreso)
    {
        return __('contafacil.tipo_ingreso.' . $tipoIngreso);
    }

    public static function listado()
    {
        return [
            [
                'clave'      => self::ACTIVIDAD_EMPRESARIAL,
                'descripcion' => self::descripcion(self::ACTIVIDAD_EMPRESARIAL),
            ],
            [
                'clave'      => self::ARRENDAMIENTO,
                'descripcion' => self::descripcion(self::ARRENDAMIENTO),
            ],
            [
                'clave'      => self::PLATAFORMAS_DIGITALES,
                'descripcion' => self::descripcion(self::PLATAFORMAS_DIGITALES),
            ],
        ];
    }
}
