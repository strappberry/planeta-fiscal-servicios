<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Factura;
use App\Models\FacturaCliente;

class BalanzaImpuestsoViewModel extends ViewModel
{
    private $clienteId;

    public function __construct($clienteId)
    {
        $this->clienteId = $clienteId;
    }

    public function retencionIsr()
    {
        $facturasClientes = FacturaCliente::query()
            ->where('cliente_id', $this->clienteId)
            ->where('considerado', true)
            ->get();

        $retencion = Factura::query()
            ->selectRaw(
                "SUM(retencion_isr) as total"
            )
            ->where('id', $facturasClientes->pluck('factura_id')->toArray())
            ->get();

        return (float) $retencion->total;
    }

    public function retencionIva()
    {
        return 0;
    }

    public function retencionIeps()
    {
        return 0;
    }

    public function trasladoIva()
    {
        return 0;
    }

    public function trasladoIeps()
    {
        return 0;
    }

    public function arrendamientoRetencionIsr()
    {
        return 0;
    }

    public function nominaIsr()
    {
        return 0;
    }

}
