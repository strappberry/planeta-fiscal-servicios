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
    public function calcularIngresos(int $decimales = 2)
    {
        $sumatoria =$this->sum(function ($facturaCliente) {
            if ($facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->traslado_iva_sobre_dieciseis
                    + $facturaCliente->factura->tasa_cero
                    + $facturaCliente->factura->traslados_exentos
                ;
            }
            return 0;
        });

        return round($sumatoria, $decimales);
    }

    /**
     * Calcular el iva acreditable a gastos
     *
     * **Solo se debe aplicar sobre FacturaCliente de tipo GASTOS.**
     */
    public function calcularIvaAcreditableAGastos(int $decimales = 2)
    {
        $acreditable = $this->sumatoriaTrasladosIva(0);
        $iva = $this->sumatoriaGravados() * 0.16;

        return round($iva - $acreditable, $decimales);
    }

    /**
     * Calculo de Compras, gastos y devoluciones facturados y pagados,
     * tambien se le conoce como gastos deducibles.
     *
     * **Solo se debe aplicar sobre FacturaCliente de tipo GASTOS.**
     *
     * Se aplica a facturas de egresos por mes de pago si estan vigentes y se consideran deducibles
     * Se calcula con los siguientes conceptos:
     * Gravados 16% + Tasa 0% + Exentos + IEPS Trasladado + Otros Impuestos
     */
    public function comprasGastosDevolucionesFacturadosPagados(int $decimales = 2)
    {
        $sumatoria = $this->sum(function ($facturaCliente) {
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

        return round($sumatoria, $decimales);
    }

    /**
     * Genera la tabla de porcentajes de como estan distribuidos los ingresos
     * de las ventas cobradas.
     *
     * **Solo se debe aplicar sobre FacturaCliente de tipo ventas.**
     */
    public function generarTablaPorcentajeIngresos()
    {
        $tabla = [
            'gravados'  => 0,
            'exentos'   => 0,
            'tasa_cero' => 0,
            'total'     => 0,
        ];

        // TODO: pendiente aplicar ingresos arrendamiento
        $ingresosArrendamiento = 0;
        $ingresosVentas = $this->calcularIngresos(0);
        $totalIngresos = $ingresosArrendamiento + $ingresosVentas;
        $totalIngresos = $totalIngresos == 0 ? 1 : $totalIngresos;

        // GRAVADOS: Gravados 16% / (ingresos ventas + ingresos arrendamiento)
        $gravadosVentas = $this->sumatoriaGravados();
        $tabla['gravados'] = $gravadosVentas / $totalIngresos;

        // EXENTOS: Exentos / (ingresos ventas + ingresos arrendamiento)
        $exentos = $this->sumatoriaTrasladosExentos();
        $tabla['exentos'] = $exentos / $totalIngresos;

        // TASA CERO: Tasa cero / (ingresos ventas + ingresos arrendamiento)
        $tasaCero = $this->sumatoriaTasaCero();
        $tabla['tasa_cero'] = $tasaCero / $totalIngresos;

        $tabla['total'] = $tabla['gravados'] + $tabla['exentos'] + $tabla['tasa_cero'];

        return $tabla;
    }

    public function sumatoriaGravados(int $decimales = 2)
    {
        $sumatoria = $this->sum(function ($facturaCliente) {
            if ($facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->traslado_iva_sobre_dieciseis;
            }
            return 0;
        });

        return round($sumatoria, $decimales);
    }

    public function sumatoriaRetencionesIsr(int $decimales = 2)
    {
        $sumatoria = $this->sum(function ($facturaCliente) {
            if ($facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->retencion_isr;
            }
            return 0;
        });

        return round($sumatoria, $decimales);
    }

    public function sumatoriaRetencionesIva(int $decimales = 2)
    {
        $sumatoria = $this->sum(function ($facturaCliente) {
            if ($facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->retencion_iva;
            }
            return 0;
        });

        return round($sumatoria, $decimales);
    }

    public function sumatoriaTasaCero(int $decimales = 2)
    {
        $sumatoria = $this->sum(function ($facturaCliente) {
            if ($facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->tasa_cero;
            }
            return 0;
        });

        return round($sumatoria, $decimales);
    }

    public function sumatoriaTrasladosExentos(int $decimales = 2)
    {
        $sumatoria = $this->sum(function ($facturaCliente) {
            if ($facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->traslados_exentos;
            }
            return 0;
        });

        return round($sumatoria, $decimales);
    }

    public function sumatoriaTrasladosIva(int $decimales = 2)
    {
        $sumatoria = $this->sum(function ($facturaCliente) {
            if ($facturaCliente->factura->estaVigente) {
                return $facturaCliente->factura->traslado_iva;
            }
            return 0;
        });

        return round($sumatoria, $decimales);
    }
}
