<?php

namespace App\Acciones\PolizasNominas;

class ProcesarDatosExcelAccion
{
    /**
     * Procesar los datos de los archivos de nomilinea.
     *
     * @param array $datos
     * @param integer $isnDocumento
     * @return array
     */
    public static function ejecutar(array $datos, int $isnDocumento = 2): array
    {
        $patronCuenta        = '/^\d+:(.+)+$/';
        $patronExtraerCuenta = '/^(\d+):/';
        $patronMonto         = '/^\d+(\.\d+)?$/';
        $patronIsn           = '/\d+(?:\.\d+)?\s*%/';

        $comienzaEncabezado = 0;
        foreach ($datos as $linea) {
            if (strtolower($linea[0]) == 'sucursal') break;
            $comienzaEncabezado++;
        }
        $datos = array_slice($datos, $comienzaEncabezado);

        $encabezados = array_shift($datos);
        $resultado = [];
        $tieneRegistroPatronal = false;

        foreach($datos as $fila) {
            if (preg_match('/patronal/', strtolower($fila[0]))) {
                $tieneRegistroPatronal = strpos($fila[0], '--') === false;
                break;
            }
        }

        foreach($encabezados as $index => $valor) {
            if (!preg_match($patronCuenta, $valor)) continue;
            preg_match($patronExtraerCuenta, $valor, $numeroCuenta);
            $cuenta = $numeroCuenta[1];
            if (!isset($resultado[$cuenta])) {
                $resultado[$cuenta] = collect();
            }
            if ($cuenta == '820') {
                preg_match($patronIsn, $valor, $isn);
                if (count($isn)) {
                    $isn = trim(str_replace('%', '', $isn[0]));
                    $isnDocumento = intval($isn);
                }
            }

            foreach($datos as $fila) {
                if (preg_match('/Total/', $fila[0])) continue;
                if (preg_match('/Total/', $fila[1])) continue;
                if (!preg_match($patronMonto, $fila[$index])) continue;
                $resultado[$cuenta]->push($fila[$index]);
            }
        }

        return [
            'isn_documento'           => $isnDocumento,
            'resultado'               => $resultado,
            'tiene_registro_patronal' => $tieneRegistroPatronal,
        ];
    }
}
