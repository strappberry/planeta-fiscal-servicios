<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\CompNominaDeduccion;
use App\Models\Factura;
use App\Models\FacturaCliente;
use Carbon\Carbon;

class BalanzaImpuestsoViewModel extends ViewModel
{
    private $clienteId;
    private $rfc;
    private $fechaInicio;
    private $fechaFin;
    private $idsFacturasCliente = [];

    public function __construct(
        $clienteId,
        $rfc,
        Carbon $fechaInicio,
        Carbon $fechaFin
    ) {
        $this->clienteId   = $clienteId;
        $this->rfc         = $rfc;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;

        $this->idsFacturasCliente = $this->obtenerIdsFacturasCliente();
    }

    /**
     * Calcular la retencion emitida menos la retencion recibida de ISR
     *
     * @return float
     */
    public function retencionIsr(): float
    {
        $facturasEmitidas = Factura::query()
            ->selectRaw(
                "SUM(retencion_isr) as total"
            )
            ->where('rfc_emisor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $facturasRecibidas = Factura::query()
            ->selectRaw(
                "SUM(retencion_isr) as total"
            )
            ->where('rfc_receptor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $emitido  = (float) $facturasEmitidas->sum('total');
        $recibido = (float) $facturasRecibidas->sum('total');

        return $emitido - $recibido;
    }

    /**
     * Calcular la retencion emitida menos la retencion recibida de IVA
     *
     * @return float
     */
    public function retencionIva(): float
    {
        $facturasEmitidas = Factura::query()
            ->selectRaw(
                "SUM(retencion_iva) as total"
            )
            ->where('rfc_emisor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $facturasRecibidas = Factura::query()
            ->selectRaw(
                "SUM(retencion_iva) as total"
            )
            ->where('rfc_receptor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $emitido = (float) $facturasEmitidas->sum('total');
        $recibido = (float) $facturasRecibidas->sum('total');

        return $emitido - $recibido;
    }

    /**
     * Calcular la retencion emitida menos la retencion recibida de IEPS
     *
     * @return float
     */
    public function retencionIeps(): float
    {
        $facturasEmitidas = Factura::query()
            ->selectRaw(
                "SUM(retencion_ieps) as total"
            )
            ->where('rfc_emisor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $facturasRecibidas = Factura::query()
            ->selectRaw(
                "SUM(retencion_ieps) as total"
            )
            ->where('rfc_receptor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $emitido = (float) $facturasEmitidas->sum('total');
        $recibido = (float) $facturasRecibidas->sum('total');

        return $emitido - $recibido;
    }

    /**
     * Calcular el iva trasladado emitido menos el iva trasladado recibido de IVA
     *
     * @return float
     */
    public function trasladoIva(): float
    {
        $facturasEmitidas = Factura::query()
            ->selectRaw(
                "SUM(traslado_iva) as total"
            )
            ->where('rfc_emisor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $facturasRecibidas = Factura::query()
            ->selectRaw(
                "SUM(traslado_iva) as total"
            )
            ->where('rfc_receptor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $emitido  = (float) $facturasEmitidas->sum('total');
        $recibido = (float) $facturasRecibidas->sum('total');

        return $emitido - $recibido;
    }

    /**
     * Calcular el iva trasladado emitido menos el IEPS trasladado recibido de IEPS
     *
     * @return float
     */
    public function trasladoIeps(): float
    {
        $facturasEmitidas = Factura::query()
            ->selectRaw(
                "SUM(traslado_ieps) as total"
            )
            ->where('rfc_emisor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $facturasRecibidas = Factura::query()
            ->selectRaw(
                "SUM(traslado_ieps) as total"
            )
            ->where('rfc_receptor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->facturaPue()
            ->vigentes()
            ->get();

        $emitido = (float) $facturasEmitidas->sum('total');
        $recibido = (float) $facturasRecibidas->sum('total');

        return $emitido - $recibido;
    }

    /**
     * Calcular el ISR emitido - ISR recibido de ISR
     * Regimen fiscal 606 - arrendamiento
     *
     * @return float
     */
    public function arrendamientoRetencionIsr(): float
    {
        $facturasEmitidas = Factura::query()
            ->selectRaw(
                "SUM(retencion_isr) as total"
            )
            ->where('rfc_emisor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->where('regimen_fiscal_emisor', '606')
            ->facturaPue()
            ->vigentes()
            ->get();

        $facturasRecibidas = Factura::query()
            ->selectRaw(
                "SUM(retencion_isr) as total"
            )
            ->where('rfc_receptor', $this->rfc)
            ->whereIn('id', $this->idsFacturasCliente)
            ->where('regimen_fiscal_emisor', '606')
            ->facturaPue()
            ->vigentes()
            ->get();

        $emitido = (float) $facturasEmitidas->sum('total');
        $recibido = (float) $facturasRecibidas->sum('total');

        return $emitido - $recibido;
    }

    /**
     * Calcular el ISR emitido - ISR recibido de ISR de nomina
     *
     * @return float
     */
    public function nominaIsr(): float
    {
        $isr = 0;

        $facturasEmitidas = Factura::query()
            ->whereIn('id', $this->idsFacturasCliente)
            ->where('rfc_emisor', $this->rfc)
            ->where('tipo_comprobante', 'N')
            ->vigentes()
            ->get();

        foreach ($facturasEmitidas as $factura) {
            if (!$factura->complementoNomina) continue;
            $calculado = CompNominaDeduccion::query()
                ->selectRaw("SUM(importe) as total")
                ->where('tipo_deduccion', '002')
                ->where('comp_nomina_id', $factura->complementoNomina->id)
                ->first();

            $isr += $calculado->total;
        }

        $facturasRecibidas = Factura::query()
            ->whereIn('id', $this->idsFacturasCliente)
            ->where('rfc_receptor', $this->rfc)
            ->where('tipo_comprobante', 'N')
            ->vigentes()
            ->get();

        foreach ($facturasRecibidas as $factura) {
            if (!$factura->complementoNomina) continue;
            $calculado = CompNominaDeduccion::query()
                ->selectRaw("SUM(importe) as total")
                ->where('tipo_deduccion', '002')
                ->where('comp_nomina_id', $factura->complementoNomina->id)
                ->first();

            $isr -= $calculado->total;
        }

        return $isr;
    }

    /**
     * Seleccionar los ids de facturas asociadas a un cliente y que esten consideradas
     * para la declaracion
     *
     * @return int[]
     */
    private function obtenerIdsFacturasCliente(): array
    {
        $facturasClientes = FacturaCliente::query()
            ->where('cliente_id', $this->clienteId)
            ->where('considerado', true)
            ->whereBetween('fecha_emision', [
                $this->fechaInicio,
                $this->fechaFin,
            ])
            ->get();

        return $facturasClientes->pluck('factura_id')->toArray();
    }

}
