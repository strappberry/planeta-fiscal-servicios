<?php

namespace App\Models\EloquentCollections;

use Illuminate\Database\Eloquent\Collection;

class FacturaClienteCollection extends Collection
{
    /**
     * Calcula los ingresos usando la selección de FacturaCliente
     *
     * ** Solo se debe aplicar sobre FacturaCliente de tipo ventas**
     *
     * Se aplican sobre facturas vigentes
     * Se calcula con los siguientes conceptos:
     * Gravados 16% + Tasa 0 + Exentos
     */
    public function calcularIngresos()
    {
        return $this->sum(function ($facturaCliente) {
            if ($facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->traslado_iva_sobre_dieciseis
                    + $facturaCliente->factura->tasa_cero
                    + $facturaCliente->factura->traslados_exentos
                ;
            }
            return 0;
        });
    }

    /**
     * Suma el ISR retenido de las facturas vigentes
     */
    public function isrRetenido()
    {
        return $this->sum(function ($facturaCliente) {
            if ($facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->retencion_isr;
            }
            return 0;
        });
    }

    /**
     * Calculo de Compras, gastos y devoluciones facturados y pagados,
     * tambien se le conoce como gastos deducibles.
     *
     * **Solo se debe aplicar sobre FacturaCliente de tipo gastos.**
     *
     * Se aplica a facturas de egresos por mes de pago si estan vigentes y se consideran deducibles
     * Se calcula con los siguientes conceptos:
     * Gravados 16% + Tasa 0% + Exentos + IEPS Trasladado + Otros Impuestos
     */
    public function comprasGastosDevolucionesFacturadosPagados()
    {
        return $this->sum(function ($facturaCliente) {
            if ($facturaCliente->deducible && $facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->traslado_iva_sobre_dieciseis
                    + $facturaCliente->factura->tasa_cero
                    + $facturaCliente->factura->traslados_exentos
                    + $facturaCliente->factura->traslado_ieps
                    + $facturaCliente->factura->otros_impuestos
                ;
            }
            return 0;
        });
    }
}
