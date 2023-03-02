<?php

namespace App\Contafacil\PolizasNominas\ViewModels;

use App\Acciones\PolizasNominas\ProcesarDatosExcelAccion;
use App\Contafacil\Compartido\Datos\PolizasNominasDatos;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use Illuminate\Support\Collection;

class CuentasDesdeArchivoViewModel extends ViewModel
{
    private $montosPorSegmento = [];
    private $polizasGeneradas = [];
    private $tieneRegistroPatronal = false;

    public function __construct(
        private array $datos,
        private int $isn = 3,
        private int $isnDocumento = 2,
    ) {
        $this->procesarDatos($this->datos);
        $this->generarPolizasNomina();
    }

    public function firma()
    {
        return uniqid() . date('YmdHis');
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
     * -------------------------------------------------------------------------
     * PÓLIZA DE NÓMINA SUELDOS Y SALARIOS PÓLIZAS SEMI AUTOMÁTICAS
     * -------------------------------------------------------------------------
     *
     * @return void
     */
    public function sueldosYSalariosSemiautomatico()
    {
        return $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS] ?? [];
    }

    public function sueldosYSalariosSemiautomaticoValidacion()
    {
        $sueldosYSalarios = $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS] ?? collect();

        return [
            'segmento' => PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS,
            'descripcion' => 'Validación sueldos y salarios',
            'cargo' => $sueldosYSalarios->sum('cargo'),
            'abono' => $sueldosYSalarios->sum('abono'),
            'es_valido' => (
                $sueldosYSalarios->sum('cargo') - $sueldosYSalarios->sum('abono')
            ) == 0,
        ];
    }

    /**
     * -------------------------------------------------------------------------
     * PÓLIZA DE NÓMINA ASIMILADOS PÓLIZAS SEMI AUTOMÁTICAS
     * -------------------------------------------------------------------------
     *
     * @return void
     */
    public function asimiladosSemiautomatico()
    {
        return $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_ASIMILADOS] ?? [];
    }

    public function asimiladosSemiautomaticoValidacion()
    {
        $asimilados = $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_ASIMILADOS] ?? collect();

        return [
            'segmento' => PolizasNominasDatos::SEGMENTO_ASIMILADOS,
            'descripcion' => 'Validación asimilados a salarios',
            'cargo' => $asimilados->sum('cargo'),
            'abono' => $asimilados->sum('abono'),
            'es_valido' => (
                $asimilados->sum('cargo') - $asimilados->sum('abono')
            ) == 0,
        ];
    }

    /**
     * -------------------------------------------------------------------------
     * PROVISIÓN DE COSTOS PATRONALES EMA Y EBA DE PÓLIZAS SEMI AUTOMÁTICAS
     * -------------------------------------------------------------------------
     *
     * @return void
     */
    public function provisionCostosPatronalesEmaEbaSemiautomatico()
    {
        return $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_PROVISION_COSTOS_PATRONALES_EMA_EBA] ?? [];
    }

    public function provisionCostosPatronalesEmaEbaSemiautomaticoValidacion()
    {
        $provisionCostosPatronalesEmaEba = $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_PROVISION_COSTOS_PATRONALES_EMA_EBA] ?? collect();

        return [
            'segmento' => PolizasNominasDatos::SEGMENTO_PROVISION_COSTOS_PATRONALES_EMA_EBA,
            'descripcion' => 'Validación provisiones de costos patronales EMA y EBA',
            'cargo' => $provisionCostosPatronalesEmaEba->sum('cargo'),
            'abono' => $provisionCostosPatronalesEmaEba->sum('abono'),
            'es_valido' => (
                $provisionCostosPatronalesEmaEba->sum('cargo') - $provisionCostosPatronalesEmaEba->sum('abono')
            ) == 0,
        ];
    }

    /**
     * -------------------------------------------------------------------------
     * PROVISIÓN DE IMPUESTO SOBRE NÓMINA DE PÓLIZAS SEMI AUTOMÁTICAS
     * -------------------------------------------------------------------------
     *
     * @return void
     */
    public function provisionImpuestoSobreNominaSemiautomatico()
    {
        return $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA] ?? [];
    }

    public function provisionImpuestoSobreNominaSemiautomaticoValidacion()
    {
        $provisionImpuestoSobreNomina = $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA] ?? collect();

        return [
            'segmento' => PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA,
            'descripcion' => 'Validación provisiones de impuesto sobre nómina',
            'cargo' => $provisionImpuestoSobreNomina->sum('cargo'),
            'abono' => $provisionImpuestoSobreNomina->sum('abono'),
            'es_valido' => (
                $provisionImpuestoSobreNomina->sum('cargo') - $provisionImpuestoSobreNomina->sum('abono')
            ) == 0,
        ];
    }

    /**
     * Procesa los datos proporcionados y extrae los montos por segmento de cuenta.
     *
     * @param array $datos
     * @return void
     */
    private function procesarDatos($datos)
    {
        $procesado = ProcesarDatosExcelAccion::ejecutar($datos, $this->isnDocumento);
        $this->isnDocumento          = $procesado['isn_documento'];
        $this->montosPorSegmento     = $procesado['resultado'];
        $this->tieneRegistroPatronal = $procesado['tiene_registro_patronal'];
    }

    /**
     * Generar todas las tablas de pólizas de nómina
     *
     * @return void
     */
    private function generarPolizasNomina()
    {
        // ---------------------------------------------------------------------
        // PÓLIZA DE NÓMINA SUELDOS Y SALARIOS PÓLIZAS SEMI AUTOMÁTICAS
        // ---------------------------------------------------------------------
        $sueldosYSalarios = $this->generarTablasGenericasDeCuentas(
            PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS,
            PolizasNominasDatos::SUELDOS_SALARIOS
        );

        $sueldosYSalarios->push([
            'segmento' => PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS,
            'clave' => 'provision_de_sueldos_y_salarios_por_pagar',
            'descripcion' => 'Provisión de sueldos y salarios por pagar',
            'cuenta' => '210-01',
            'columna' => 'abono',
            'cargo' => 0,
            'abono' => $sueldosYSalarios->sum('cargo') - $sueldosYSalarios->sum('abono'),
        ]);

        $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_SUELDOS_SALARIOS] = $sueldosYSalarios;

        // ---------------------------------------------------------------------
        // PÓLIZA DE NÓMINA ASIMILADOS PÓLIZAS SEMI AUTOMÁTICAS
        // ---------------------------------------------------------------------
        $asimilados = $this->generarTablasGenericasDeCuentas(
            PolizasNominasDatos::SEGMENTO_ASIMILADOS,
            PolizasNominasDatos::ASIMILADOS
        );

        $total = $asimilados->sum('cargo') - $asimilados->sum('abono');
        $total = $total > 0 ? round($total, 2): 0;

        $asimilados->push([
            'segmento' => PolizasNominasDatos::SEGMENTO_ASIMILADOS,
            'clave' => 'provision_de_sueldos_y_salarios_por_pagar',
            'descripcion' => 'Provisión de sueldos y salarios por pagar',
            'cuenta' => '210-01',
            'columna' => 'abono',
            'cargo' => 0,
            'abono' => $total,
        ]);

        $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_ASIMILADOS] = $asimilados;

        // ---------------------------------------------------------------------
        // PROVISIÓN DE COSTOS PATRONALES EMA Y EBA DE PÓLIZAS SEMI AUTOMÁTICAS
        // ---------------------------------------------------------------------
        $costosPatronalesEmaEba = $this->generarTablasGenericasDeCuentas(
            PolizasNominasDatos::SEGMENTO_PROVISION_COSTOS_PATRONALES_EMA_EBA,
            PolizasNominasDatos::PROVISION_COSTOS_PATRONALES_EMA_EBA
        );

        $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_PROVISION_COSTOS_PATRONALES_EMA_EBA] = $costosPatronalesEmaEba;

        // ---------------------------------------------------------------------
        // PROVISIÓN DE IMPUESTO SOBRE NÓMINA DE PÓLIZAS SEMI AUTOMÁTICAS
        // ---------------------------------------------------------------------
        $provisionImpuestosSobreNomina = collect();
        $provisionImpuestosSobreNomina->push([
            'segmento'    => PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA,
            'clave'       => 'contribuciones_pagadas_excepto_isr_ietu_impac_iva_e_ieps',
            'cuenta'      => '601-29',
            'descripcion' => 'Contribuciones pagadas excepto ISR, IETU, IMPAC, IVA e IEPS ',
            'columna'     => 'cargo',
            'cargo'       => $this->isn(),
            'abono'       => 0,
        ]);
        $provisionImpuestosSobreNomina->push([
            'segmento'    => PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA,
            'clave'       => 'provision_de_impuesto_estatal_sobre_nomina_por_pagar',
            'cuenta'      => '212-01',
            'descripcion' => 'Provisión de impuesto estatal sobre nómina por pagar ',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => $this->isn(),
        ]);

        $this->polizasGeneradas[PolizasNominasDatos::SEGMENTO_PROVISION_IMPUESTOS_SOBRE_NOMINA] = $provisionImpuestosSobreNomina;
    }

    /**
     * Generar array asociativo con las tablas de cuentas de pólizas de nómina y sus montos
     *
     * @param string $segmento
     * @param array $cuentas
     * @return Collection
     */
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

            if (
                isset($cuenta['registro_patronal']) &&
                $this->tieneRegistroPatronal != $cuenta['registro_patronal']
            ) {
                $resultado->push($informacionCuenta);
                continue;
            }

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
