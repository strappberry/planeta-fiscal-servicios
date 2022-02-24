<?php
namespace App\Sat\Utilidades;

use App\Models\Cliente;
use App\Models\Factura;
use Carbon\Carbon;
use PhpCfdi\CfdiSatScraper\Metadata;

class InsertaDatosScraper
{
    
    public static function insertar(Cliente $cliente, Metadata $metadata)
    {
        return $cliente->facturas()->create([
            'uuid' => $metadata->uuid(),
            'rfc_emisor' => $metadata->get('rfcEmisor'),
            'nombre_emisor' => $metadata->get('nombreEmisor'),
            'rfc_receptor' => $metadata->get('rfcReceptor'),
            'nombre_receptor' => $metadata->get('nombreReceptor'),
            'fecha_emision' => Carbon::parse($metadata->get('fechaEmision')),
            'fecha_certificacion' => Carbon::parse($metadata->get('fechaCertificacion')),
            'pac_certifico' => $metadata->get('pacCertifico'),
            'efecto_comprobante' => $metadata->get('efectoComprobante'),
            'estatus_cancelacion' => $metadata->get('estatusCancelacion'),
            'estado_comprobante' => $metadata->get('estadoComprobante'),
            'estatus_proceso_cancelacion' => $metadata->get('estatusProcesoCancelacion'),
            'fecha_proceso_cancelacion' => empty($metadata->get('fechaProcesoCancelacion')) ?
                null : Carbon::parse($metadata->get('fechaProcesoCancelacion')),
        ]);
    }

    public static function actualizarFacturaConScraper(Factura $factura, Metadata $metadata)
    {
        $factura->estado_comprobante = $metadata->get('estadoComprobante');
        $factura->estatus_cancelacion = $metadata->get('estatusCancelacion');
        $factura->estatus_proceso_cancelacion = $metadata->get('estatusProcesoCancelacion');
        $factura->fecha_proceso_cancelacion = empty($metadata->get('fechaProcesoCancelacion')) ?
            null : Carbon::parse($metadata->get('fechaProcesoCancelacion'));
        $factura->save();
    }

}