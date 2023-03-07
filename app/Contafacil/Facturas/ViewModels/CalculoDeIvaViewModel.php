<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\Facturas\CalcularIvaAcreditable;
use App\Acciones\PolizasNominas\Cuentas\PolizaISRAsimiladosYSalarios;
use App\Acciones\PolizasNominas\Cuentas\PolizaISRSueldosYSalarios;
use App\Acciones\SaldosAFavor\Cuentas\SaldoFavorISRArrendamiento;
use App\Acciones\SaldosAFavor\Cuentas\SaldoFavorISRAsimiladosASalarios;
use App\Acciones\SaldosAFavor\Cuentas\SaldoFavorISRServiciosProfesionales;
use App\Acciones\SaldosAFavor\Cuentas\SaldoFavorISRSueldosYSalarios;
use App\Acciones\SaldosAFavor\Cuentas\SaldoFavorIVARetenciones;
use App\Acciones\SaldosAFavor\ResolverAcreditamientosCompensacionesAccion;
use App\Contafacil\Compartido\Datos\SaldosAFavorDatos;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\TipoIngreso;
use App\Models\Cliente;
use App\Models\EloquentCollections\FacturaClienteCollection;
use App\Models\PolizaNomina;
use Carbon\Carbon;

/**
 * Tabla Calculo de IVA
 * Tabla Otros impuestos
 */
class CalculoDeIvaViewModel extends ViewModel
{
    private $decimales = 0;
    /** @var FacturaClienteCollection */
    private $ventasCobradas;
    /** @var FacturaClienteCollection */
    private $gastosPagados;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
    ) {
        $this->ventasCobradas = $this->cliente->facturasCliente()
            ->with('factura')
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->esVenta()
            ->esConsiderado()
            ->get();

        $this->gastosPagados = $this->cliente->facturasCliente()
            ->with('factura')
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->esGasto()
            ->esConsiderado()
            ->get();
    }

    /*
     * Calcular el iva de las ventas
     *
     * Datos de ventas cobradas:
     * * Ventas gravadas: factura->traslado_iva_sobre_dieciseis
     * * Traslado: factura->traslado_iva
     * * Iva retenido: factura->retencion_iva
     * * Ventas al 0%: factura->tasa_cero
     * * Ventas exentas: factura->traslados_exentos
     *
     * Datos de gastos pagados:
     * * Compras gravadas: factura->traslado_iva_sobre_dieciseis
     * * Acreditable: factura->traslado_iva
     * * Compras al 0%: factura->tasa_cero
     * * Compras exentas: factura->traslados_exentos
     * * Iva retenciones: factura->retencion_iva
     *
     * (ventas cobradas son ventas con fecha de pago dentro del mes indicado)
     * (gastos pagados son gastos con fecha de pago dentro del mes indicado)
     *
     * Esto se explicó en una reunión con el contador Juan: https://youtu.be/MjVd-93JSWM
     */
    public function calculosIva(): array
    {
        /* Las claves de de este array corresponden a los titulos usados en el excel
         * usado por planeta fiscal en las a partir de las lineas 248 página "Balanza de comprobación" */
        $calculos = [
            'ventas_gravadas'  => 0,
            'trasladado'       => 0,
            'compras_gravadas' => 0,
            'acreditable'      => 0,
            'iva_retenido'     => 0,

            'ventas_al_cero'   => 0,
            'ventas_exentas'   => 0,
            'compras_al_cero'  => 0,
            'compras_exentas'  => 0,
            // 'iva_retenciones'  => 0,

            'acreditamiento_saldo_favor_iva' => 0,
            'iva_del_periodo'  => 0,
            'iva_por_pagar'    => 0,

            'iva_retenciones' => [
                'retenido' => 0,
                'a_favor'  => 0,
                'total'    => 0,
            ],
        ];

        $calculos['ventas_gravadas'] = $this->ventasCobradas->sumatoriaGravados($this->decimales);
        $calculos['trasladado']      = $this->ventasCobradas->sumatoriaTrasladosIva($this->decimales);
        $calculos['iva_retenido']    = $this->ventasCobradas->sumatoriaRetencionesIva($this->decimales);
        $calculos['ventas_al_cero']  = $this->ventasCobradas->sumatoriaTasaCero($this->decimales);
        $calculos['ventas_exentas']  = $this->ventasCobradas->sumatoriaTrasladosExentos($this->decimales);

        $calculos['acreditable'] = CalcularIvaAcreditable::ejecutar(
            $this->ventasCobradas,
            $this->gastosPagados,
            $this->decimales
        );
        $calculos['compras_gravadas'] = $this->gastosPagados->sumatoriaGravados($this->decimales);
        $calculos['compras_al_cero']  = $this->gastosPagados->sumatoriaTasaCero($this->decimales);
        $calculos['compras_exentas']  = $this->gastosPagados->sumatoriaTrasladosExentos($this->decimales);

        $calculos['iva_retenciones']['retenido']  = $this->gastosPagados->sumatoriaRetencionesIva($this->decimales);
        $calculos['iva_retenciones']['a_favor'] = SaldoFavorIVARetenciones::ejecutar($this->fecha);
        $calculos['iva_retenciones']['total'] = $calculos['iva_retenciones']['retenido'] + $calculos['iva_retenciones']['a_favor'];

        /* Acreditamiento de saldo a favor de IVA
         * Proviene de los saldos a favor - IVA del periodo
         */
        $calculos['acreditamiento_saldo_favor_iva'] = ResolverAcreditamientosCompensacionesAccion::ejecutar(
            $this->fecha,
            SaldosAFavorDatos::IVA_DEL_PERIODO,
            $this->decimales
        );

        /* El iva del periodo de calcula de la siguiente manera
         *   Iva trasladado de las ventas cobradas
         * - Iva acreditable de los gastos pagados
         * - Iva retenido de las ventas cobradas
         */
        $calculos['iva_del_periodo'] = $calculos['trasladado'] - $calculos['acreditable'] - $calculos['iva_retenido'];

        /* El iva por pagar se calcula de la siguiente manera:
         *   Iva del periodo
         * - Acreditamiento de saldo a favor de IVA
         */
        $calculos['iva_por_pagar'] = $calculos['iva_del_periodo'] - $calculos['acreditamiento_saldo_favor_iva'];

        return $calculos;
    }

    /* TODO: pendiente implementar calculos isr para diferentes regimenes */
    public function calculosIsr(): array
    {
        $calculos = [
            'sueldos_salarios' => [
                'retenido' => 0,
                'a_favor'  => 0,
                'total'    => 0,
            ],
            'asimilados_salario' => [
                'retenido' => 0,
                'a_favor'  => 0,
                'total'    => 0,
            ],
            'arrendamiento' => [
                'retenido' => 0,
                'a_favor'  => 0,
                'total'    => 0,
            ],
            'servicios_profesionales' => [
                'retenido' => 0,
                'a_favor'  => 0,
                'total'    => 0,
            ],
        ];

        // Sueldos y salarios
        $calculos['sueldos_salarios']['retenido'] = PolizaISRSueldosYSalarios::ejecutar($this->fecha);
        $calculos['sueldos_salarios']['a_favor'] = SaldoFavorISRSueldosYSalarios::ejecutar($this->fecha);
        $calculos['sueldos_salarios']['total'] = $calculos['sueldos_salarios']['retenido'] - $calculos['sueldos_salarios']['a_favor'];

        // Asimilados a salarios
        $calculos['asimilados_salario']['retenido'] = PolizaISRAsimiladosYSalarios::ejecutar($this->fecha);
        $calculos['asimilados_salario']['a_favor'] = SaldoFavorISRAsimiladosASalarios::ejecutar($this->fecha);
        $calculos['asimilados_salario']['total'] = $calculos['asimilados_salario']['retenido'] - $calculos['asimilados_salario']['a_favor'];

        // Servicios profesionales
        $calculos['servicios_profesionales']['retenido'] = $this->gastosPagados->sumatoriaRetencionesIsr($this->decimales);
        $calculos['servicios_profesionales']['a_favor'] = SaldoFavorISRServiciosProfesionales::ejecutar($this->fecha);
        $calculos['servicios_profesionales']['total'] = $calculos['servicios_profesionales']['retenido'] - $calculos['servicios_profesionales']['a_favor'];

        // Arrendamiento
        // TODO: pendiente implementar calculos isr para arrendamiento
        $calculos['arrendamiento']['retenido'] = 0;
        $calculos['arrendamiento']['a_favor'] = SaldoFavorISRArrendamiento::ejecutar($this->fecha);
        $calculos['arrendamiento']['total'] = $calculos['arrendamiento']['retenido'] - $calculos['arrendamiento']['a_favor'];

        return $calculos;
    }
}
