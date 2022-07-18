<?php

namespace App\Contafacil\Ventas\ViewModels;

use App\Models\Factura;
use Carbon\Carbon;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\CompNominaDeduccion;

class ImpuestosViewModel extends ViewModel
{
    private $fechaInicio;
    private $fechaFin;
    private $rfc;

    public function __construct(string $rfc, Carbon $fechaInicio, Carbon $fechaFin)
    {
        $this->rfc = $rfc;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
    }

    /**
     * Calcular las retencioens isr
     *
     * @return float
     */
    public function retencionIsr()
    {
        $retencion = Factura::query()
            ->selectRaw(
                "SUM(retencion_isr) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($this->fechaInicio)->startOfMonth(),
                Carbon::parse($this->fechaFin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $this->rfc)
            ->first();

        return (float) $retencion->total;
    }

    /**
     * Calcular las retencioens iva
     *
     * @return float
     */
    public function retencionIva()
    {
        $retencion = Factura::query()
            ->selectRaw(
                "SUM(retencion_iva) as total"
            )
            ->whereBetween('fecha_emision', [
                $this->fechaInicio,
                $this->fechaFin,
            ])
            ->where('rfc_emisor', $this->rfc)
            ->first();

        return (float) $retencion->total;
    }

    /**
     * Calcular las retencioens ieps
     *
     * @return float
     */
    public function retencionIeps()
    {
        $retencion = Factura::query()
            ->selectRaw(
                "SUM(retencion_ieps) as total"
            )
            ->whereBetween('fecha_emision', [
                $this->fechaInicio,
                $this->fechaFin,
            ])
            ->where('rfc_emisor', $this->rfc)
            ->first();

        return (float) $retencion->total;
    }

    /**
     * Calcular el traslado iva
     *
     * @return float
     */
    public function trasladoIva()
    {
        $traslado = Factura::query()
            ->selectRaw(
                "SUM(traslado_iva) as total"
            )
            ->whereBetween('fecha_emision', [
                $this->fechaInicio,
                $this->fechaFin,
            ])
            ->where('rfc_emisor', $this->rfc)
            ->first();

        return (float) $traslado->total;
    }

    /**
     * Calcular el traslado iva
     *
     * @return float
     */
    public function trasladoIeps()
    {
        $traslado = Factura::query()
            ->selectRaw(
                "SUM(traslado_ieps) as total"
            )
            ->whereBetween('fecha_emision', [
                $this->fechaInicio,
                $this->fechaFin,
            ])
            ->where('rfc_emisor', $this->rfc)
            ->first();

        return (float) $traslado->total;
    }

    /**
     * Calcular la retencion isr arrendamiento
     *
     * @return float
     */
    public function arrendamientoRetencionIsr()
    {
        $calculo = Factura::query()
            ->selectRaw(
                "SUM(retencion_isr) as total"
            )
            ->whereBetween('fecha_emision', [
                Carbon::parse($this->fechaInicio)->startOfMonth(),
                Carbon::parse($this->fechaFin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $this->rfc)
            ->where('regimen_fiscal_emisor', '606')
            ->first();

        return (float) $calculo->total;
    }

    /**
     * Calcular el isr de las facturas de nomina
     *
     * @return float
     */
    public function nominaIsr()
    {
        $facturasNomina = Factura::query()
            ->where('tipo_comprobante', 'N')
            ->whereBetween('fecha_emision', [
                Carbon::parse($this->fechaInicio)->startOfMonth(),
                Carbon::parse($this->fechaFin)->endOfMonth(),
            ])
            ->where('rfc_emisor', $this->rfc)
            ->get();

        $nominaISR = 0;
        foreach ($facturasNomina as $factura) {
            if (!$factura->complementoNomina) continue;
            $calculado = CompNominaDeduccion::query()
                ->selectRaw("SUM(importe) as total")
                ->where('tipo_deduccion', '002')
                ->where('comp_nomina_id', $factura->complementoNomina->id)
                ->first();

            $nominaISR += $calculado->total;
        }

        return $nominaISR;
    }

}
