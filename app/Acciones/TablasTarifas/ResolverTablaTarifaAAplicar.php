<?php

namespace App\Acciones\TablasTarifas;

use App\Models\TablaTarifa;

class ResolverTablaTarifaAAplicar
{
    public static function ejecutar(
        $segment, $anio, $mes, $base
    ) {
        $tarifas = collect(config('tarifas.tablas'));
        $tarifa = $tarifas->firstWhere('regimen', $segment);
        if (!$tarifa) return null;

        $tablas = collect($tarifa['tablas']);
        $tabla = $tablas->where('desde_mes', '>=', $mes)->where('hasta_mes', '<=', $mes)->first();
        if (!$tabla) return null;

        return TablaTarifa::query()
            ->where('segmento', $segment)
            ->where('anio', $anio)
            ->where('clave_tabla', $tabla['clave'])
            ->where('limite_inferior', '<=', $base)
            ->orderBy('limite_inferior', 'desc')
            ->first();
    }
}
