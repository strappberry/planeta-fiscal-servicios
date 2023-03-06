<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\Facturas\CalcularIvaAcreditable;
use App\Acciones\SaldosAFavor\ResolverAcreditamientosCompensacionesAccion;
use App\Contafacil\Compartido\Datos\SaldosAFavorDatos;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\TipoIngreso;
use App\Models\Cliente;
use App\Models\EloquentCollections\FacturaClienteCollection;
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
            'iva_retenciones'  => 0,

            'acreditamiento_saldo_favor_iva' => 0,
            'iva_del_periodo'  => 0,
            'iva_por_pagar'    => 0,
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
        $calculos['iva_retenciones']  = $this->gastosPagados->sumatoriaRetencionesIva($this->decimales);

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
        $arrendamiento = $this->cliente->facturasCliente()
            ->with('factura')
            ->dentroFechaPago(
                $this->fecha->copy()->startOfMonth(),
                $this->fecha->copy()->endOfMonth()
            )
            ->tiposIngreso([
                TipoIngreso::ARRENDAMIENTO,
            ])
            ->esVenta()
            ->esConsiderado()
            ->get();

        $calculos = [
            'retenidos_isr_sueldos_salarios'        => 0,
            'retenidos_isr_asimilados_salario'      => 0,
            'retenidos_isr_arrendamiento'           => $arrendamiento->sumatoriaRetencionesIsr(0),
            'retenidos_isr_servicios_profesionales' => 0,
        ];

        $calculos['retenidos_isr_servicios_pr'] = $this->gastosPagados->sumatoriaRetencionesIsr($this->decimales);

        return $calculos;
    }
}
