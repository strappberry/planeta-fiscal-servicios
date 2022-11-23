<?php

namespace App\Acciones\PhpCfdi;

use App\Models\Cliente;
use App\Models\Factura;
use App\Sat\Manejadores\ManejadorDescargaXml;
use App\Sat\Utilidades\InsertaDatosScraper;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType;
use PhpCfdi\CfdiSatScraper\MetadataList;
use PhpCfdi\CfdiSatScraper\ResourceType;
use PhpCfdi\CfdiSatScraper\SatScraper;

class ProcesarUuidsPorScrapper
{
    public static function ejecutar(
        Cliente $cliente,
        SatScraper $servicio,
        DownloadType $tipoDescarga,
        array $uuids
    ) {
        $uuids = array_chunk($uuids, 100);
        $cfdisADescargar = [];

        foreach ($uuids as $uuidsPorPaquete) {
            $paqueteCfdis = $servicio->listByUuids($uuidsPorPaquete, $tipoDescarga);

            foreach ($paqueteCfdis as $uuid => $datosFactura) {
                $factura = Factura::where('uuid', $uuid)->first();
                $descargarXml = true;

                if (!$factura) {
                    InsertaDatosScraper::insertar($cliente, $datosFactura);
                } else {
                    $descargarXml = $factura->xml_procesado == false;
                    InsertaDatosScraper::actualizarFacturaConScraper($factura, $datosFactura);
                }

                if ($descargarXml) {
                    array_push($cfdisADescargar, $datosFactura);
                }
            }

            $manejarDescargar = new ManejadorDescargaXml();
            $servicio->resourceDownloader(
                ResourceType::xml(),
                new MetadataList($cfdisADescargar),
                50,
            )->download($manejarDescargar);
        }
    }
}
