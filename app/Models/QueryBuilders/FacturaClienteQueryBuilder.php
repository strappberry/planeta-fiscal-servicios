<?php

namespace App\Models\QueryBuilders;

use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;
use Illuminate\Database\Eloquent\Builder;

class FacturaClienteQueryBuilder extends Builder
{
    /**
     * Aplicar busqueda a partir de un tipo de número de cuenta,
     * para las poliazas de ventas se aplica el tipo venta,
     * para las polizas de gastos se aplica el tipo gasto.
     */
    public function aplicarTipoNumeroCuenta(string $tipoNumeroCuenta)
    {
        if ($tipoNumeroCuenta === NumeroCuenta::TIPO_POLIZA_VENTAS) {
            return $this->where('tipo_factura', FacturaCliente::TIPO_VENTA);
        }
        if ($tipoNumeroCuenta === NumeroCuenta::TIPO_POLIZA_GASTOS) {
            return $this->where('tipo_factura', FacturaCliente::TIPO_GASTO);
        }
        return $this;
    }

    /** Dentro un rango de fechas de emision */
    public function dentroFechaEmision($fechaInicio, $fechaFin)
    {
        return $this->whereBetween('fecha_emision', [$fechaInicio, $fechaFin]);
    }

    /** Dentro de un rango de fechas de pago */
    public function dentroFechaPago($fechaInicio, $fechaFin)
    {
        return $this->whereBetween('fecha_pago', [$fechaInicio, $fechaFin]);
    }

    public function esVenta()
    {
        return $this->where('tipo_factura', FacturaCliente::TIPO_VENTA);
    }

    public function esGasto()
    {
        return $this->where('tipo_factura', FacturaCliente::TIPO_GASTO);
    }

    public function esConsiderado()
    {
        return $this->where('considerado', true);
    }

    public function noEsConsiderado()
    {
        return $this->where('considerado', false);
    }
}
