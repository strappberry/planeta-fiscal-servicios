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
            if ($factura->comprobanteXml) {
                $factura->comprobanteXml->update([
                    'comprobante' => $cfdi
                ]);
            } else {
                $factura->comprobanteXml()->create([
                    'comprobante' => $cfdi
                ]);
            }
        }
    }

    public static function obtenerDatosParaFactura(array $cfdi) {
        $datos = [
            'total'            => $cfdi['Total'] ?? 0,
            'subtotal'         => $cfdi['SubTotal'] ?? 0,
            'descuento'        => $cfdi['Descuento'] ?? 0,
            'complementos'     => array_keys($cfdi['Complemento']),
            'serie'            => $cfdi['Serie'] ?? '',
            'folio'            => $cfdi['Folio'] ?? '',
            'tipo_comprobante' => $cfdi['TipoDeComprobante'] ?? '',
            'moneda'           => $cfdi['Moneda'] ?? '',
            'fecha_emision'    => Carbon::parse($cfdi['Fecha'])->format('Y-m-d'),
            'xml_procesado'    => true,
            'forma_pago'       => $cfdi['FormaPago'] ?? '',
            'metodo_pago'      => $cfdi['MetodoPago'] ?? '',
            'moneda'           => $cfdi['Moneda'] ?? '',
            'tipo_cambio'      => $cfdi['TipoCambio'] ?? 1,
        ];

        if ($cfdi['Emisor']) {
            $datos['rfc_emisor'] = $cfdi['Emisor']['Rfc'] ?? '';
            $datos['nombre_emisor'] = $cfdi['Emisor']['Nombre'] ?? '';
        }

        if ($cfdi['Receptor']) {
            $datos['rfc_receptor'] = $cfdi['Receptor']['Rfc'] ?? '';
            $datos['nombre_receptor'] = $cfdi['Receptor']['Nombre'] ?? '';
        }

        if (isset($cfdi['Complemento']['TimbreFiscalDigital'])) {
            $datos['fecha_certificacion'] = Carbon::parse(
                $cfdi['Complemento']['TimbreFiscalDigital']['FechaTimbrado']
            ) ?? '';
            $datos['pac_certifico'] = $cfdi['Complemento']['TimbreFiscalDigital']['RfcProvCertif'] ?? '';
            $datos['complementos'] = array_keys($cfdi['Complemento']);
        }

        $trasladoIva  = 0;
        $trasladoIeps = 0;
        $retencionIsr = 0;
        $retencionIva = 0;
        $retencionIeps = 0;

        // Procesar los impuestos trasladados y retenidos
        if (isset($cfdi['Impuestos'])) {
            if (isset($cfdi['Impuestos']['Traslados']) && isset($cfdi['Impuestos']['Traslados']['Traslado'])) {
                foreach($cfdi['Impuestos']['Traslados']['Traslado'] as $impuesto) {
                    if ($impuesto['Impuesto'] == '002') {
                        $trasladoIva += (float) ($impuesto['Importe'] ?? 0);
                    } elseif ($impuesto['Impuesto'] == '003') {
                        $trasladoIeps += (float) ($impuesto['Importe'] ?? 0);
                    }
                }
            }

            if (isset($cfdi['Impuestos']['Retenciones']) && isset($cfdi['Impuestos']['Retenciones']['Retencion'])) {
                foreach($cfdi['Impuestos']['Retenciones']['Retencion'] as $impuesto) {
                    if ($impuesto['Impuesto'] == '001') {
                        $retencionIsr += (float) ($impuesto['Importe'] ?? 0);
                    } elseif ($impuesto['Impuesto'] == '002') {
                        $retencionIva += (float) ($impuesto['Importe'] ?? 0);
                    } elseif ($impuesto['Impuesto'] == '003') {
                        $retencionIeps += (float) ($impuesto['Importe'] ?? 0);
                    }
                }
            }
        }

        $datos['retencion_isr']  = $retencionIsr;
        $datos['retencion_iva']  = $retencionIva;
        $datos['retencion_ieps'] = $retencionIeps;
        $datos['traslado_ieps']  = $trasladoIeps;
        $datos['traslado_iva']   = $trasladoIva;


        return $datos;
    }
}