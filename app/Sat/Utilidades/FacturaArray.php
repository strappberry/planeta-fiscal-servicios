<?php
namespace App\Sat\Utilidades;

use App\Models\Cliente;
use App\Models\Factura;
use Carbon\Carbon;
use DOMDocument;
use PhpCfdi\CfdiToJson\Factory;

class FacturaArray
{

    public static function convertirXmlAArray(string $xml): array
    {
        $cfdi = new DOMDocument();
        $cfdi->loadXML($xml);

        $factory = new Factory();
        $conversorData = $factory->createConverter();

        $rootNode = $conversorData->convertXmlDocument($cfdi);
        $cfdiArray = $rootNode->toArray();

        unset($cfdiArray['xsi:schemaLocation']);
        unset($cfdiArray['Sello']);
        unset($cfdiArray['Certificado']);

        if (
            isset($cfdiArray['Complemento']) &&
            isset($cfdiArray['Complemento'][0])
        ) {
            $cfdiArray['Complemento'] = $cfdiArray['Complemento'][0];

            if (isset($cfdiArray['Complemento']['TimbreFiscalDigital'])) {
                unset($cfdiArray['Complemento']['TimbreFiscalDigital']['SelloSAT']);
                unset($cfdiArray['Complemento']['TimbreFiscalDigital']['SelloCFD']);
                unset($cfdiArray['Complemento']['TimbreFiscalDigital']['xsi:schemaLocation']);
            }
        }

        return $cfdiArray;
    }


    public static function guardarCfdiArray(string $uuid, array $cfdi): void
    {
        $factura = Factura::query()
            ->where('uuid', $uuid)
            ->first();
            
        $datos = self::obtenerDatosParaFactura($cfdi);
        $data['uuid'] = $uuid;

        if ($factura) {
            $factura->update($datos);
        }
    }

    public static function obtenerDatosParaFactura(array $cfdi) {
        $datos = [
            'total' => $cfdi['Total'] ?? 0,
            'subtotal' => $cfdi['SubTotal'] ?? 0,
            'descuento' => $cfdi['Descuento'] ?? 0,
            'complementos' => array_keys($cfdi['Complemento']),
            'serie' => $cfdi['Serie'] ?? '',
            'folio' => $cfdi['Folio'] ?? '',
            'tipo_comprobante' => $cfdi['TipoDeComprobante'] ?? '',
            'moneda' => $cfdi['Moneda'] ?? '',
            'fecha_emision' => Carbon::parse($cfdi['Fecha'])->format('Y-m-d'),
            'xml_procesado' => true,
        ];

        if ($cfdi['Emisor']) {
            $datos['rfc_emisor'] = $cfdi['Emisor']['Rfc'] ?? '';
            $datos['nombre_emisor'] = $cfdi['Emisor']['Nombre'] ?? '';
        }

        if ($cfdi['Receptor']) {
            $datos['rfc_emisor'] = $cfdi['Receptor']['Rfc'] ?? '';
            $datos['nombre_emisor'] = $cfdi['Receptor']['Nombre'] ?? '';
        }

        if (isset($cfdi['Complemento']['TimbreFiscalDigital'])) {
            $datos['fecha_certificacion'] = Carbon::parse(
                $cfdi['Complemento']['TimbreFiscalDigital']['FechaTimbrado']
            ) ?? '';
            $datos['pac_certifico'] = $cfdi['Complemento']['TimbreFiscalDigital']['RfcProvCertif'] ?? '';
        }

        return $datos;
    }
}