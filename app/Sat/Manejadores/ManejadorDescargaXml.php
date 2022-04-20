<?php
namespace App\Sat\Manejadores;

use App\Sat\Utilidades\FacturaArray;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\CfdiSatScraper\Exceptions\ResourceDownloadError;
use PhpCfdi\CfdiSatScraper\Contracts\ResourceDownloadHandlerInterface;
use PhpCfdi\CfdiSatScraper\Exceptions\ResourceDownloadRequestExceptionError;
use PhpCfdi\CfdiSatScraper\Exceptions\ResourceDownloadResponseError;
use Psr\Http\Message\ResponseInterface;

class ManejadorDescargaXml implements ResourceDownloadHandlerInterface
{
    public function onSuccess(string $uuid, string $content, ResponseInterface $response): void
    {
        try {
            $this->guardarXml($uuid, $content, '/facturas/xmls/');
            libxml_use_internal_errors(true);
            $cfdi = FacturaArray::convertirXmlAArray($content);
            FacturaArray::guardarCfdiArray($uuid, $cfdi);
        } catch (Exception $e) {
            Log::error("[PROCESAR_XML] Error al procesar el xml descargado ({$uuid}): {$e->getMessage()}");
        }
    }

    public function onError(ResourceDownloadError $error) : void
    {
        if ($error instanceof ResourceDownloadRequestExceptionError) {
            Log::error("Error al solicitar descargar el xml de la factura {$error->getUuid()}");
        }
        else if ($error instanceof ResourceDownloadResponseError) {
            Log::error("Error al manejar la respuesta el xml de la factura {$error->getUuid()}");
        }
        else {
            Log::error("Error desconocido al descargar el xml de la factura {$error->getUuid()}");
        }
    }

    private function guardarXml($uuid, $content, $carpeta = '/archivos/xml_errores/')
    {
        try {
            Storage::put($carpeta . $uuid . '.xml', $content);
        } catch(Exception $e) {
            Log::error("[GUARDAR_XML_ERRORES] Error al guardar el xml de la factura {$uuid}: {$e->getMessage()}");
        }
    }
}