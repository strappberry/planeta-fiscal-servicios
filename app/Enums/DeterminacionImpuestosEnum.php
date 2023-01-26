<?php

namespace App\Enums;

use Illuminate\Http\Request;
use ReflectionClass;

class DeterminacionImpuestosEnum
{
    const CAMPO_DEPRECIACION = 'depreciacion';
    const CAMPO_PERDIDA_EJERCICIOS_ANTERIORES = 'perdida_ejercicios_anteriores';
    const CAMPO_PREDIAL = 'predial';
    const CAMPO_COSTO_VENDIDO_EJERCICIOS_ANTERIORES = 'costo_vendido_ejercicios_anteriores';
    const CAMPO_DEDUCCION_INVERSIONES_EJERCICIOS_ANTERIORES = 'deduccion_inversiones_ejercicios_anteriores';
    const CAMPO_PARTICIPACION_TRABAJADORES_UTILIDADES = 'participacion_trabajadores_utilidades';
    const CAMPO_COEFICIENTE_UTILIDAD = 'coeficiente_utilidad';
    const CAMPO_ANTICIPOS_RENDIMIENTOS_DISTRIBUIDOS_EN_PERIODO = 'anticipos_rendimientos_distribuidos_en_periodo';

    /**
     * Obtener un arreglo de campos editables validos para la determinacion de impuestos
     */
    public static function obtenerCamposValidos(): array
    {
        $constantes = (new ReflectionClass(__CLASS__))->getConstants();

        return array_values($constantes);
    }

    /**
     * Obtener un arreglo de campos editables validos para la determinacion de impuestos
     * desde el objeto request, estos vendran dentro de un arreglo de campos e
     * ignoraran los campos que no esten en el arreglo de campos validos.
     */
    public static function obtenerArregloDesdeRequest(Request $request): array
    {
        $camposValidos = self::obtenerCamposValidos();
        $arreglo = [];

        foreach ($request->get('campos', []) as $campo => $valor) {
            if (!in_array($campo, $camposValidos)) continue;
            $arreglo[$campo] = floatval($valor) ?? 0;
        }

        return $arreglo;
    }
}
