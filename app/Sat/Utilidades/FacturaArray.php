<?php
namespace App\Sat\Utilidades;

use App\Models\Cliente;
use App\Models\Factura;
use Carbon\Carbon;
use DOMDocument;
use Exception;
use Illuminate\Support\Facades\Log;
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

        // Agregar datos de nomina
        try {
            $complementoNomina = self::obtenerComplementoNomina($cfdi);
            if ($complementoNomina !== null) {
                $nomina = $factura->complementoNomina()->first();
                $nomina = $factura->complementoNomina()->updateOrCreate(
                    [
                        'id' => ($nomina) ? $nomina->id : null,
                    ],
                    $complementoNomina['nomina'],
                );

                $nomina->percepciones()->delete();
                foreach ($complementoNomina['percepciones'] as $datosPercepcion) {
                    $nomina->percepciones()->create($datosPercepcion);
                }

                $nomina->deducciones()->delete();
                foreach ($complementoNomina['deducciones'] as $datosDeduccion) {
                    $nomina->deducciones()->create($datosDeduccion);
                }

                $nomina->otrosPagos()->delete();
                foreach ($complementoNomina['otros_pagos'] as $datosOtroPago) {
                    $nomina->otrosPagos()->create($datosOtroPago);
                }
            }
        } catch (Exception $e) {
            Log::error("Error al procesar la nomina {$uuid} " . $e->getMessage());
        }

        // Agregar datos de pagos
        try {
            $complementoPagos = self::obtenerComplementoPagos($cfdi);
            if ($complementoPagos !== null) {
                $compPago = $factura->complementoPagos()->first();
                $compPago = $factura->complementoPagos()->updateOrCreate(
                    [
                        'id' => ($compPago) ? $compPago->id : null,
                    ],
                    $complementoPagos['complemento'],
                );

                $compPago->pagos()->delete();
                foreach($complementoPagos['pagos'] as $datosPago) {
                    $pago = $compPago->pagos()->create($datosPago['pago']);

                    foreach($datosPago['documentos'] as $documento) {
                        $documentoPagado = $pago->documentosRelacionados()->create($documento['documento']);

                        foreach($documento['traslados'] as $traslado) {
                            $documentoPagado->traslados()->create($traslado);
                        }

                        foreach($documento['retenciones'] as $retencion) {
                            $documentoPagado->retenciones()->create($retencion);
                        }
                    }

                    foreach($datosPago['traslados'] as $traslado) {
                        $pago->traslados()->create($traslado);
                    }

                    foreach($datosPago['retenciones'] as $retencion) {
                        $pago->retenciones()->create($retencion);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("Error al procesar los pagos {$uuid} " . $e->getMessage());
        }
    }

    public static function obtenerDatosParaFactura(array $cfdi)
    {
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
            $datos['regimen_fiscal_emisor'] = $cfdi['Emisor']['RegimenFiscal'] ?? '';
        }

        if ($cfdi['Receptor']) {
            $datos['rfc_receptor'] = $cfdi['Receptor']['Rfc'] ?? '';
            $datos['nombre_receptor'] = $cfdi['Receptor']['Nombre'] ?? '';
            $datos['uso_cfdi_receptor'] = $cfdi['Receptor']['UsoCFDI'] ?? '';
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

    public static function obtenerComplementoNomina(array $cfdi): ?array
    {
        if (!isset($cfdi['Complemento'])) return null;
        if (!isset($cfdi['Complemento']['Nomina'])) return null;

        $nomina = $cfdi['Complemento']['Nomina'];
        $complemento = [
            'nomina'       => [
                'version'            => $nomina['Version'] ?? '',
                'tipo_nomina'        => $nomina['TipoNomina'] ?? '',
                'fecha_pago'         => $nomina['FechaPago'] ?? null,
                'fecha_inicial'      => $nomina['FechaInicialPago'] ?? null,
                'fecha_final'        => $nomina['FechaFinalPago'] ?? null,
                'num_dias_pagados'   => $nomina['NumDiasPagados'] ?? 0,
                'total_percepciones' => (float) ($nomina['TotalPercepciones'] ?? 0),
                'total_deducciones'  => (float) ($nomina['TotalDeducciones'] ?? 0),
                'total_otros_pagos'  => (float) ($nomina['TotalOtrosPagos'] ?? 0),
                // Totales nodos percepciones y deducciones
                'percepciones_total_sueldos'          => 0,
                'percepciones_total_gravado'          => 0,
                'percepciones_total_exento'           => 0,
                'deducciones_total_otras_deducciones' => 0,
                'deducciones_total_imp_retenidos'     => 0,
            ],
            'percepciones' => [],
            'deducciones'  => [],
            'otros_pagos'  => [],
        ];

        if (isset($nomina['Percepciones']) && isset($nomina['Percepciones']['Percepcion']))
        {
            $complemento['nomina']['percepciones_total_sueldos'] = (float) ($nomina['Percepciones']['TotalSueldos'] ?? 0);
            $complemento['nomina']['percepciones_total_gravado'] = (float) ($nomina['Percepciones']['TotalGravado'] ?? 0);
            $complemento['nomina']['percepciones_total_exento']  = (float) ($nomina['Percepciones']['TotalExento'] ?? 0);

            foreach($nomina['Percepciones']['Percepcion'] as $percepcion) {
                $complemento['percepciones'][] = [
                    'tipo_percepcion' => $percepcion['TipoPercepcion'] ?? '',
                    'clave'           => $percepcion['Clave'] ?? '',
                    'concepto'        => $percepcion['Concepto'] ?? '',
                    'importe_gravado' => (float) ($percepcion['ImporteGravado'] ?? 0),
                    'importe_exento'  => (float) ($percepcion['ImporteExento'] ?? 0),
                ];
            }
        }

        if (isset($nomina['Deducciones']) && isset($nomina['Deducciones']['Deduccion']))
        {
            $complemento['nomina']['deducciones_total_otras_deducciones'] = (float) ($nomina['Deducciones']['TotalOtrasDeducciones'] ?? 0);
            $complemento['nomina']['deducciones_total_imp_retenidos']     = (float) ($nomina['Deducciones']['TotalImpuestosRetenidos'] ?? 0);

            foreach($nomina['Deducciones']['Deduccion'] as $deduccion) {
                $complemento['deducciones'][] = [
                    'tipo_deduccion' => $deduccion['TipoDeduccion'] ?? '',
                    'clave'          => $deduccion['Clave'] ?? '',
                    'concepto'       => $deduccion['Concepto'] ?? '',
                    'importe'        => (float) ($deduccion['Importe'] ?? 0),
                ];
            }
        }

        if (isset($nomina['OtrosPagos']) && isset($nomina['OtrosPagos']['OtroPago']))
        {
            foreach($nomina['OtrosPagos']['OtroPago'] as $percepcion) {
                $complemento['otros_pagos'][] = [
                    'tipo_otro_pago' => $percepcion['TipoOtroPago'] ?? '',
                    'clave'          => $percepcion['Clave'] ?? '',
                    'concepto'       => $percepcion['Concepto'] ?? '',
                    'importe'        => (float) ($percepcion['Importe'] ?? 0),
                ];
            }
        }

        return $complemento;
    }

    public static function obtenerComplementoPagos(array $cfdi): ?array
    {
        if (!isset($cfdi['Complemento'])) return null;
        if (!isset($cfdi['Complemento']['Pagos'])) return null;

        $pagos = $cfdi['Complemento']['Pagos'];

        $complemento= [
            'complemento' => [
                'version'                         => $pagos['Version'] ?? '',
                'monto_total_pagos'               => 0,
                'total_traslados_base_iva_16'     => 0,
                'total_traslados_impuesto_iva_16' => 0,
            ],
            'pagos' => [],
        ];

        if (isset($pagos['Totales'])) {
            $totales = $pagos['Totales'];
            $complemento['complemento']['total_retenciones_isr'] = (float) ($totales['TotalRetencionesISR'] ?? 0);
            $complemento['complemento']['monto_total_pagos'] = (float) ($totales['MontoTotalPagos'] ?? 0);
            $complemento['complemento']['total_traslados_base_iva_16'] =
                (float) ($totales['TotalTrasladosBaseIVA16'] ?? 0);
            $complemento['complemento']['total_traslados_impuesto_iva_16'] =
                (float) ($totales['TotalTrasladosImpuestoIVA16'] ?? 0);
        }

        // Procesar los nodos de pagos
        foreach($pagos['Pago'] as $pago) {
            $datosPago = [
                'pago'        => [
                    'fecha_pago'  => $pago['FechaPago'] ?? '',
                    'forma_pago'  => $pago['FormaDePagoP'] ?? '',
                    'moneda'      => $pago['MonedaP'] ?? '',
                    'monto'       => (float) ($pago['Monto'] ?? 0),
                    'tipo_cambio' => (float) ($pago['TipoCambioP'] ?? 0),
                ],
                'documentos'  => [],
                'traslados'   => [],
                'retenciones' => [],
            ];
            // Procesar los nodos de documentos relacionados
            if (isset($pago['DoctoRelacionado'])) {
                foreach($pago['DoctoRelacionado'] as $documento) {
                    $datosDocumento = [
                        'equivalencia'           => (float) ($documento['EquivalenciaDR'] ?? 0),
                        'folio'                  => $documento['Folio'] ?? '',
                        'serie'                  => $documento['Serie'] ?? '',
                        'uuid'                   => $documento['IdDocumento'] ?? '',
                        'importe_pagado'         => (float) ($documento['ImpPagado'] ?? 0),
                        'importe_saldo_anterior' => (float) ($documento['ImpSaldoAnt'] ?? 0),
                        'importe_saldo_insoluto' => (float) ($documento['ImpSaldoInsoluto'] ?? 0),
                        'moneda'                 => $documento['MonedaDR'] ?? '',
                        'numero_parcialidad'    => $documento['NumParcialidad'] ?? '',
                        'objeto_impuesto'        => $documento['ObjetoImpDR'] ?? '',
                    ];

                    $datosTraslados   = [];
                    $datosRetenciones = [];

                    // Procesar los nodos de los impuestos del documento relacionado
                    if (isset($documento['ImpuestosDR'])) {
                        if (
                            isset($documento['ImpuestosDR']['TrasladosDR']) &&
                            isset($documento['ImpuestosDR']['TrasladosDR']['TrasladoDR'])
                        ) {
                            foreach($documento['ImpuestosDR']['TrasladosDR']['TrasladoDR'] as $traslado) {
                                $datosTraslados[] = [
                                    'base'        => (float) ($traslado['BaseDR'] ?? 0),
                                    'importe'     => (float) ($traslado['ImporteDR'] ?? 0),
                                    'impuesto'    => $traslado['ImpuestoDR'] ?? '',
                                    'tasa_cuota'  => (float) ($traslado['TasaOCuotaDR'] ?? 0),
                                    'tipo_factor' => $traslado['TipoFactorDR'] ?? '',
                                ];
                            }
                        }

                        if (
                            isset($documento['ImpuestosDR']['RetencionesDR']) &&
                            isset($documento['ImpuestosDR']['RetencionesDR']['RetencionDR'])
                        ) {
                            foreach($documento['ImpuestosDR']['RetencionesDR']['RetencionDR'] as $retencion) {
                                $datosRetenciones[] = [
                                    'base'        => (float) ($retencion['BaseDR'] ?? 0),
                                    'importe'     => (float) ($retencion['ImporteDR'] ?? 0),
                                    'impuesto'    => $retencion['ImpuestoDR'] ?? '',
                                    'tasa_cuota'  => (float) ($retencion['TasaOCuotaDR'] ?? 0),
                                    'tipo_factor' => $retencion['TipoFactorDR'] ?? '',
                                ];
                            }
                        }
                    }

                    $datosPago['documentos'][] = [
                        'documento'   => $datosDocumento,
                        'traslados'   => $datosTraslados,
                        'retenciones' => $datosRetenciones,
                    ];
                }
            }

            // Procesar los nodos de impuestos trasladados del pago
            if (
                isset($pago['ImpuestosP']) &&
                isset($pago['ImpuestosP']['TrasladosP']) &&
                isset($pago['ImpuestosP']['TrasladosP']['TrasladoP'])
            ) {
                $trasladosP = $pago['ImpuestosP']['TrasladosP']['TrasladoP'];
                foreach($trasladosP as $traslado) {
                    $datosPago['traslados'][] = [
                        'base'        => (float) ($traslado['BaseP'] ?? 0),
                        'importe'     => (float) ($traslado['ImporteP'] ?? 0),
                        'impuesto'    => $traslado['ImpuestoP'] ?? '',
                        'tasa_cuota'  => (float) ($traslado['TasaOCuotaP'] ?? 0),
                        'tipo_factor' => $traslado['TipoFactorP'] ?? '',
                    ];
                }
            }

            // Procesar los nodos de impuestos retenidos del pago
            if (
                isset($pago['ImpuestosP']) &&
                isset($pago['ImpuestosP']['RetencionesP']) &&
                isset($pago['ImpuestosP']['RetencionesP']['RetencionP'])
            ) {
                $retencionesP = $pago['ImpuestosP']['RetencionesP']['RetencionP'];
                foreach($retencionesP as $retencion) {
                    $datosPago['retenciones'][] = [
                        'impuesto' => $retencion['ImpuestoP'] ?? '',
                        'importe'  => (float) ($retencion['ImporteP'] ?? 0),
                    ];
                }
            }

            $complemento['pagos'][] = $datosPago;
        }

        return $complemento;
    }
}
