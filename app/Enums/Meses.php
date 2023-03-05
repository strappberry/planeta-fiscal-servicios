<?php

use Carbon\Carbon;

class Meses
{
    const LISTA = [
        ['mes' => 1, 'clave' => 'enero', 'descripcion' => 'Enero'],
        ['mes' => 2, 'clave' => 'febrero', 'descripcion' => 'Febrero'],
        ['mes' => 3, 'clave' => 'marzo', 'descripcion' => 'Marzo'],
        ['mes' => 4, 'clave' => 'abril', 'descripcion' => 'Abril'],
        ['mes' => 5, 'clave' => 'mayo', 'descripcion' => 'Mayo'],
        ['mes' => 6, 'clave' => 'junio', 'descripcion' => 'Junio'],
        ['mes' => 7, 'clave' => 'julio', 'descripcion' => 'Julio'],
        ['mes' => 8, 'clave' => 'agosto', 'descripcion' => 'Agosto'],
        ['mes' => 9, 'clave' => 'septiembre', 'descripcion' => 'Septiembre'],
        ['mes' => 10, 'clave' => 'octubre', 'descripcion' => 'Octubre'],
        ['mes' => 11, 'clave' => 'noviembre', 'descripcion' => 'Noviembre'],
        ['mes' => 12, 'clave' => 'diciembre', 'descripcion' => 'Diciembre'],
    ];

    public static function buscarMes($clave)
    {
        $meses = collect(self::LISTA);

        $mes = $meses->firstWhere('clave', $clave);

        return $mes ? $mes['descripcion'] : '';
    }

    public static function convertirAFecha($clave, $anio): Carbon
    {
        $mes = self::buscarMes($clave);

        return Carbon::createFromFormat('d/m/Y', "01/{$mes['mes']}/{$anio}");
    }
}
