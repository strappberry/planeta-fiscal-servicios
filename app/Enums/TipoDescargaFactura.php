<?php

namespace App\Enums;

use Exception;
use PhpCfdi\SatWsDescargaMasiva\Shared\DownloadType as MasivaDownloadType;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType as ScraperDownloadType;

class TipoDescargaFactura
{
    const EMITIDO  = 'RfcEmisor';
    const RECIBIDO = 'RfcReceptor';

    public static function resolverTipoDescargaMasiva($tipoDescarga): MasivaDownloadType
    {
        if (!in_array($tipoDescarga, MasivaDownloadType::toArray())) {
            throw new Exception('Tipo de descarga no soportado');
        }

        return new MasivaDownloadType($tipoDescarga);
    }

    /**
     * Se obtiene el tipo de descarga que se usará en la búsqueda de facturas
     * por medio del scraper a partir del tipo de busqueda de facturas masivas
     */
    public static function resolverTipoBusquedaUUIDS(string $tipoDescarga): ScraperDownloadType
    {
        $tipoDescargaMasiva = self::resolverTipoDescargaMasiva($tipoDescarga);

        if (MasivaDownloadType::received()->value() === $tipoDescargaMasiva->value()) {
            return ScraperDownloadType::recibidos();
        }
        if (MasivaDownloadType::issued()->value() === $tipoDescargaMasiva->value()) {
            return ScraperDownloadType::emitidos();
        }

        throw new Exception("Error no se puede resolver el tipo de busqueda a partir del tipo de descarga", 1);
    }
}
