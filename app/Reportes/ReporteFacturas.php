<?php
namespace App\Reportes;

use App\Models\Factura;

class ReporteFacturas implements Reporte
{
    private $uuids = [];

    public function __construct(array $uuids)
    {
        $this->uuids = $uuids;
    }

    public function nombreArchivo(): string
    {
        return 'facturas_cliente.xlsx';
    }

    public function encabezados(): array
    {
        return [
            __('dashboard.facturas.uuid'),
            __('dashboard.facturas.rfc_emisor'),
            __('dashboard.facturas.nombre_emisor'),
            __('dashboard.facturas.rfc_receptor'),
            __('dashboard.facturas.nombre_receptor'),
            __('dashboard.facturas.fecha_emision'),
            __('dashboard.facturas.fecha_certificacion'),
            __('dashboard.facturas.estado_comprobante'),
            __('dashboard.facturas.subtotal'),
            __('dashboard.facturas.descuento'),
            __('dashboard.facturas.total'),
        ];
    }

    public function paginas(): array
    {
        $paginas = [];

        $facturas = Factura::whereIn('uuid', $this->uuids)->get();
        $pagina = [
            'titulo' => 'Facturas',
            'lineas' => [],
        ];

        foreach ($facturas as $factura) {
            array_push(
                $pagina['lineas'], 
                $this->generarLineaFactura($factura)
            );
        }

        array_push($paginas, $pagina);
        return $paginas;
    }

    private function generarLineaFactura(Factura $factura): array
    {
        return [
            $factura->uuid,
            $factura->rfc_emisor,
            $factura->nombre_emisor,
            $factura->rfc_receptor,
            $factura->nombre_receptor,
            $factura->fecha_emision,
            $factura->fecha_certificacion,
            $factura->estado_comprobante,
            $factura->subtotal,
            $factura->descuento,
            $factura->total,
        ];
    }

}