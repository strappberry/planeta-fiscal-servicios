<?php

namespace App\Contafacil\PolizasNominas\ViewModels;

use App\Contafacil\Compartido\Datos\PolizasNominasDatos;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use Illuminate\Support\Collection;

class CuentasDesdeArchivoViewModel extends ViewModel
{
    private $montosPorSegmento = [];
    public function __construct(
        private array $datos,
        private int $isn = 3,
        private int $isnDocumento = 2,
    ) {
        $this->datos = array_slice($datos, 8);
        $this->procesarDatos($this->datos);
    }

    public function base()
    {
        if (isset($this->montosPorSegmento['820'])) {
            return round(
                $this->montosPorSegmento['820']->sum() / ($this->isnDocumento / 100),
                2
            );
        }

        return 0;
    }

    public function isn()
    {
        return round(
            $this->base() * ($this->isn / 100),
            2
        );
    }

    /**
     * Genera tabla: PÓLIZA DE NÓMINA SUELDOS Y SALARIOS PÓLIZAS SEMI AUTOMÁTICAS
     */
    public function sueldosYSalariosSemiautomatico()
    {
        $resultado = $this->generarTablasGenericasDeCuentas(
            PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS,
            PolizasNominasDatos::SUELDOS_SALARIOS
        );

        $resultado->push([
            'segmento' => PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS,
            'clave' => 'provision_de_sueldos_y_salarios_por_pagar',
            'descripcion' => 'Provisión de sueldos y salarios por pagar',
            'cuenta' => '210-01',
            'columna' => 'abono',
            'cargo' => 0,
            'abono' => $resultado->sum('cargo') - $resultado->sum('abono'),
        ]);

        return $resultado;
    }

    /**
     * Genera tabla: PÓLIZA DE NÓMINA ASIMILADOS PÓLIZAS SEMI AUTOMÁTICAS
     */
    public function asimiladosSemiautomatico()
    {
        $resultado = $this->generarTablasGenericasDeCuentas(
            PolizasNominasDatos::SEGMENTO_ASIMILADOS,
            PolizasNominasDatos::ASIMILADOS
        );

        $total = $resultado->sum('cargo') - $resultado->sum('abono');
        $total = $total > 0 ? round($total, 2): 0;

        $resultado->push([
            'segmento' => PolizasNominasDatos::SEGMENTO_ASIMILADOS,
            'clave' => 'provision_de_sueldos_y_salarios_por_pagar',
            'descripcion' => 'Provisión de sueldos y salarios por pagar',
            'cuenta' => '210-01',
            'columna' => 'abono',
            'cargo' => 0,
            'abono' => $total,
        ]);

        return $resultado;
    }

    /**
     * Genera tabla: PROVISIÓN DE COSTOS PATRONALES EMA Y EBA DE PÓLIZAS SEMI AUTOMÁTICAS
     */
    public function provisionCostosPatronalesEmaEbaSemiautomatico()
    {
        $resultado = $this->generarTablasGenericasDeCuentas(
            PolizasNominasDatos::SEGMENTO_PROVISION_COSTOS_PATRONALES_EMA_EBA,
            PolizasNominasDatos::PROVISION_COSTOS_PATRONALES_EMA_EBA
        );

        return $resultado;
    }

    /**
     * Genera tabla: PROVISIÓN DE IMPUESTO SOBRE NÓMINA DE PÓLIZAS SEMI AUTOMÁTICAS
     */
    public function provisionImpuestoSobreNominaSemiautomatico()
    {
        return [
            [
                'segmento'    => PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA,
                'clave'       => 'contribuciones_pagadas_excepto_isr_ietu_impac_iva_e_ieps',
                'cuenta'      => '601-29',
                'descripcion' => 'Contribuciones pagadas excepto ISR, IETU, IMPAC, IVA e IEPS ',
                'columna'     => 'cargo',
                'cargo'       => $this->isn(),
                'abono'       => 0,
            ],
            [
                'segmento'    => PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA,
                'clave'       => 'provision_de_impuesto_estatal_sobre_nomina_por_pagar',
                'cuenta'      => '212-01',
                'descripcion' => 'Provisión de impuesto estatal sobre nómina por pagar ',
                'columna'     => 'abono',
                'cargo'       => 0,
                'abono'       => $this->isn(),
            ],
        ];
    }

    private function procesarDatos($datos)
    {
        // $patronCuenta = '/^\d+:\s[A-Z\s]+$/';
        $patronCuenta = '/^\d+:(.+)+$/';
        $patronExtraerCuenta = '/^(\d+):/';
        $patronMonto  = '/^\d+(\.\d+)?$/';

        $encabezados = array_shift($datos);

        foreach($encabezados as $index => $valor) {
            if (!preg_match($patronCuenta, $valor)) continue;
            preg_match($patronExtraerCuenta, $valor, $numeroCuenta);
            if (!isset($this->montosPorSegmento[$numeroCuenta[1]])) {
                $this->montosPorSegmento[$numeroCuenta[1]] = collect();
            }

            foreach($datos as $fila) {
                if (!preg_match($patronMonto, $fila[$index])) continue;
                if (preg_match('/Total/', $fila[1])) break;
                $this->montosPorSegmento[$numeroCuenta[1]]->push($fila[$index]);
            }
        }
    }

    private function generarTablasGenericasDeCuentas(string $segmento, array $cuentas): Collection
    {
        $resultado = collect();
        foreach($cuentas as $cuenta)
        {
            $informacionCuenta = [
                'segmento'    => $segmento,
                'clave'       => $cuenta['clave'],
                'cuenta'      => $cuenta['cuenta'],
                'descripcion' => $cuenta['descripcion'],
                'columna'     => $cuenta['columna'],
                'cargo'       => 0,
                'abono'       => 0,
            ];
            $monto = 0;

            foreach($cuenta['formula'] as $lineaFormula) {
                if (!isset($this->montosPorSegmento[$lineaFormula['cuenta']])) continue;

                if ($lineaFormula['accion'] == 'suma') {
                    $monto += $this->montosPorSegmento[$lineaFormula['cuenta']]->sum();
                } else if ($lineaFormula['accion'] == 'resta') {
                    $monto -= $this->montosPorSegmento[$lineaFormula['cuenta']]->sum();
                }
            }

            $monto = round($monto, 2);
            $informacionCuenta[$informacionCuenta['columna']] = $monto;

            $resultado->push($informacionCuenta);
        }

        return $resultado;
    }
}
