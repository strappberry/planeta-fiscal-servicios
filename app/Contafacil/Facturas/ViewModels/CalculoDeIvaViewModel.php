<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use Carbon\Carbon;

class CalculoDeIvaViewModel extends ViewModel
{
    private $ventasCobradas;
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

            'acreditamiento_saldo_favor_iva' => 0, // TODO: Pendiente de implementar
            'iva_del_periodo'  => 0,
            'iva_por_pagar'    => 0,
        ];

        $calculos['ventas_gravadas'] = $this->ventasCobradas->sumatoriaGravados(0);
        $calculos['trasladado']      = $this->ventasCobradas->sumatoriaTrasladosIva(0);
        $calculos['iva_retenido']    = $this->ventasCobradas->sumatoriaRetencionesIva(0);
        $calculos['ventas_al_cero']  = $this->ventasCobradas->sumatoriaTasaCero(0);
        $calculos['ventas_exentas']  = $this->ventasCobradas->sumatoriaTrasladosExentos(0);

        $calculos['compras_gravadas'] = $this->gastosPagados->sumatoriaGravados(0);
        $calculos['acreditable']      = $this->gastosPagados->sumatoriaTrasladosIva(0);
        $calculos['compras_al_cero']  = $this->gastosPagados->sumatoriaTasaCero(0);
        $calculos['compras_exentas']  = $this->gastosPagados->sumatoriaTrasladosExentos(0);
        $calculos['iva_retenciones']  = $this->gastosPagados->sumatoriaRetencionesIva(0);

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
            'retenidos_isr_sueldos_salarios'        => 0,
            'retenidos_isr_asimilados_salario'      => 0,
            'retenidos_isr_arrendamiento'           => 0,
            'retenidos_isr_servicios_profesionales' => 0,
        ];

        foreach($this->gastosPagados as $gastoPagado) {
            $calculos['retenidos_isr_servicios_profesionales'] += $gastoPagado->factura->retencion_isr;
        }

        return $calculos;
    }
}
