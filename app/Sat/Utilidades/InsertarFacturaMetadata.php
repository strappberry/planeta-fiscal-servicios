<?php

namespace App\Sat\Utilidades;

use App\Enums\EstadoFactura;
use App\Models\Cliente;
use App\Models\Factura;
use PhpCfdi\SatWsDescargaMasiva\PackageReader\MetadataItem;
use PhpCfdi\SatWsDescargaMasiva\Shared\DocumentStatus;

class InsertarFacturaMetadata
{
    public static function ejecutar(Cliente $cliente, MetadataItem $metadata)
    {
        $estadoDocumento = new DocumentStatus($metadata->estatus);
        Factura::updateOrCreate(
            [
                'cliente_id' => $cliente->id,
                'uuid'       => $metadata->uuid,
            ],
            [
                'rfc_emisor'                  => $metadata->rfcEmisor,
                'nombre_emisor'               => $metadata->nombreEmisor,
                'rfc_receptor'                => $metadata->rfcReceptor,
                'nombre_receptor'             => $metadata->nombreReceptor,
                'fecha_emision'               => $metadata->fechaEmision,
                'fecha_certificacion'         => $metadata->fechaCertificacionSat,
                'efecto_comprobante'          => $metadata->efectoComprobante,
                'fecha_proceso_cancelacion'   => $metadata->fechaCancelacion ? $metadata->fechaCancelacion : null,
                'estado_comprobante' => $estadoDocumento->isActive() ? EstadoFactura::VIGENTE: EstadoFactura::CANCELADO,
            ]
        );
    }
}
