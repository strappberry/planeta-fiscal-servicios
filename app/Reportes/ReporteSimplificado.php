<?php
namespace App\Reportes;

use App\Enums\TipoPersona;
use App\Models\Cliente;
use App\Models\ComprobanteXml;
use App\Models\Factura;
use App\Reportes\Helpers\ConvertirMontoAPesos;
use App\Reportes\Validaciones\ValidacionesFacturasRecibidas;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use DateTimeImmutable;
use Illuminate\Support\Str;

class ReporteSimplificado implements ReporteFacturacionPF
{
    /** @var DateTimeImmutable */
    private $fechaInicio;
    /** @var DateTimeImmutable */
    private $fechaFin;

    private $cliente;
    private $rfc;

    public function __construct(
        string $rfc,
        DateTimeImmutable $fechaInicio,
        DateTimeImmutable $fechaFin
    )
    {
        $this->rfc = $rfc;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;

        $this->cliente = Cliente::where('rfc', $rfc)->first();
    }

    public function nombreArchivo(): string
    {
        return 'reporte_simplificado_' .
            $this->fechaInicio->format('Y-m-d') . '_' .
            $this->fechaFin->format('Y-m-d') . '_' .
            '.xlsx';
    }

    public function informacionCliente(): array
    {
        return [
            'nombre' => $this->cliente->razon_social ?? '',
            'rfc' => $this->rfc ?? '',
        ];
    }

    /**
     * Este reporte tendra encazados por cada página.
     * Este método no se implementa en este reporte.
     *
     * @return array
     */
    public function encabezados(): array
    {
        return [];
    }

    public function paginas(): array
    {
        $paginas = [
            $this->paginaIngresosRecibidos(),
            $this->paginaEgresosRecibidos(),
            $this->paginaNominaRecibidos(),
            $this->paginaPagosRecibidos(),
            $this->paginaCartaPorteRecibidos(),
            $this->paginaIngresosEmitidos(),
            $this->paginaEgresosEmitidos(),
            $this->paginaNominaEmitido(),
            $this->paginaPagosEmitidos(),
            $this->paginaCartaPorteEmitidos(),
            $this->paginaFacturasCanceladas(),
        ];

        return $paginas;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de ingreos recibidos.
     * -------------------------------------------------------------------------
     */
    private function paginaIngresosRecibidos(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.ingresos_recibidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.general.fecha'),
                __('dashboard.facturas.serie'),
                __('dashboard.facturas.folio'),
                __('dashboard.facturas.tipo_comprobante'),
                __('dashboard.facturas.rfc_emisor'),
                __('dashboard.facturas.nombre_emisor'),
                __('dashboard.facturas.metodo_pago'),
                __('dashboard.facturas.forma_pago'),
                __('dashboard.facturas.moneda'),
                __('dashboard.facturas.tipo_cambio'),
                __('dashboard.facturas.subtotal'),
                __('dashboard.reportes.impuesto_trasladado_iva'),
                __('dashboard.reportes.impuesto_trasladado_ieps'),
                __('dashboard.reportes.impuesto_retenido_iva'),
                __('dashboard.reportes.impuesto_retenido_isr'),
                __('dashboard.facturas.descuento'),
                __('dashboard.facturas.total'),
                __('dashboard.reportes.primer_concepto'),
                __('dashboard.reportes.iva_tasa_0'),
                __('dashboard.reportes.iva_exento'),
                __('dashboard.reportes.tipo_contribuyente'),
                __('dashboard.reportes.regimen_contribuyente'),
                __('dashboard.reportes.validacion_rfc_emisor'),
                __('dashboard.facturas.uso_cfdi'),
                __('dashboard.reportes.validacion_uso_cfdi'),
                __('dashboard.reportes.validacion_metodo_forma_pago'),
            ],
            'lineas' => [],
        ];

        $ingresos = Factura::query()
            ->where('rfc_receptor', $this->rfc)
            ->where(function($query) {
                return $query
                    ->where('tipo_comprobante', 'I')
                    ->orWhere('tipo_comprobante', 'E');
            })
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($ingresos as $ingreso) {
            array_push(
                $pagina['lineas'],
                array_merge(
                    $this->generarLineaIngresosRecibido($ingreso),
                    $this->generarColumnasValidaciones($ingreso)
                )
            );
        }

        return $pagina;
    }

    private function generarLineaIngresosRecibido(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;
        $linea = [
            $factura->uuid,
            $factura->fecha_emision->format('Y-m-d'),
            $factura->serie,
            $factura->folio,
            $factura->tipo_comprobante,
            $factura->rfc_emisor,
            $factura->nombre_emisor,
        ];

        if (!$comprobante) {
            return $linea;
        }

        array_push($linea, $comprobante->comprobante['MetodoPago'] ?? '');
        array_push($linea, $comprobante->comprobante['FormaPago'] ?? '');
        array_push($linea, $comprobante->comprobante['Moneda'] ?? '');
        array_push($linea, $comprobante->comprobante['TipoCambio'] ?? '');

        $multiplicador = $factura->tipo_comprobante === 'I' ? 1 : -1;

        array_push($linea, ConvertirMontoAPesos::convertir(
            $factura->subtotal,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);

        $impuestosTraslados = $comprobante->obtenerImpuestosTraslados();
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosTraslados['iva'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosTraslados['ieps'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);

        $impuestosRetenidos = $comprobante->obtenerImpuestosRetenidos();
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosRetenidos['iva'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosRetenidos['isr'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);

        array_push($linea, ConvertirMontoAPesos::convertir(
            $factura->descuento,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);
        array_push($linea, ConvertirMontoAPesos::convertir(
            $factura->total,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);

        if (
            $comprobante &&
            isset($comprobante->comprobante['Conceptos']) &&
            isset($comprobante->comprobante['Conceptos']['Concepto']) &&
            isset($comprobante->comprobante['Conceptos']['Concepto'][0])
        ) {
            array_push(
                $linea,
                Str::of($comprobante->comprobante['Conceptos']['Concepto'][0]['Descripcion'] ?? '')
                    ->replace('(', '')
                    ->replace(')', '')
            );
        } else {
            array_push($linea, '');
        }

        $columnasIvaExentoY0 = $this->obtenerColumnasIva0YExento($comprobante);
        array_push($linea, $columnasIvaExentoY0['iva_0']);
        array_push($linea, $columnasIvaExentoY0['iva_exento']);

        return $linea;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de egresos recibidos.
     * -------------------------------------------------------------------------
     */
    private function paginaEgresosRecibidos(): array
    { 
        $pagina = [
            'titulo' => __('dashboard.reportes.egresos_recibidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.general.fecha'),
                __('dashboard.facturas.serie'),
                __('dashboard.facturas.folio'),
                __('dashboard.facturas.tipo_comprobante'),
                __('dashboard.facturas.rfc_emisor'),
                __('dashboard.facturas.nombre_emisor'),
                __('dashboard.facturas.metodo_pago'),
                __('dashboard.facturas.forma_pago'),
                __('dashboard.facturas.moneda'),
                __('dashboard.facturas.tipo_cambio'),
                __('dashboard.facturas.subtotal'),
                __('dashboard.reportes.impuesto_trasladado_iva'),
                __('dashboard.reportes.impuesto_trasladado_ieps'),
                __('dashboard.reportes.impuesto_retenido_iva'),
                __('dashboard.reportes.impuesto_retenido_isr'),
                __('dashboard.facturas.descuento'),
                __('dashboard.facturas.total'),
                __('dashboard.reportes.primer_concepto'),
                __('dashboard.reportes.iva_tasa_0'),
                __('dashboard.reportes.iva_exento'),
            ],
            'lineas' => [],
        ];

        $ingresos = Factura::query()
            ->where('rfc_receptor', $this->rfc)
            ->where('tipo_comprobante', 'E')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($ingresos as $ingreso) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaEgresosRecibido($ingreso)
            );
        }

        return $pagina;
    }

    private function generarLineaEgresosRecibido(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;
        $linea = [
            $factura->uuid,
            $factura->fecha_emision->format('Y-m-d'),
            $factura->serie,
            $factura->folio,
            $factura->tipo_comprobante,
            $factura->rfc_emisor,
            $factura->nombre_emisor,
        ];

        if (!$comprobante) {
            return $linea;
        }

        array_push($linea, $comprobante->comprobante['MetodoPago'] ?? '');
        array_push($linea, $comprobante->comprobante['FormaPago'] ?? '');
        array_push($linea, $comprobante->comprobante['Moneda'] ?? '');
        array_push($linea, $comprobante->comprobante['TipoCambio'] ?? '');

        $subtotal = ConvertirMontoAPesos::convertir(
            $factura->subtotal,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        );
        array_push(
            $linea,
            $subtotal
        );

        $impuestosTraslados = $comprobante->obtenerImpuestosTraslados();
        $ivaTrasladado = ConvertirMontoAPesos::convertir(
            $impuestosTraslados['iva'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        );
        array_push($linea, $ivaTrasladado);

        $iepsTrasladado = ConvertirMontoAPesos::convertir(
            $impuestosTraslados['ieps'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        );
        array_push($linea, $iepsTrasladado);

        $impuestosRetenidos = $comprobante->obtenerImpuestosRetenidos();
        $ivaRetenido = ConvertirMontoAPesos::convertir(
            $impuestosRetenidos['iva'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        );
        array_push($linea, $ivaRetenido);

        $isrRetenido = ConvertirMontoAPesos::convertir(
            $impuestosRetenidos['isr'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        );
        array_push($linea, $isrRetenido);

        $descuento = ConvertirMontoAPesos::convertir(
            $factura->descuento,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        );
        array_push($linea, $descuento);
        $total = ConvertirMontoAPesos::convertir(
            $factura->total,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        );
        array_push($linea, $total);

        if (
            $comprobante &&
            isset($comprobante->comprobante['Conceptos']) &&
            isset($comprobante->comprobante['Conceptos']['Concepto']) &&
            isset($comprobante->comprobante['Conceptos']['Concepto'][0])
        ) {
            array_push(
                $linea,
                Str::of($comprobante->comprobante['Conceptos']['Concepto'][0]['Descripcion'] ?? '')
                    ->replace('(', '')
                    ->replace(')', '')
            );
        } else {
            array_push($linea, '');
        }

        $columnasIvaExentoY0 = $this->obtenerColumnasIva0YExento($comprobante);
        array_push($linea, $columnasIvaExentoY0['iva_0']);
        array_push($linea, $columnasIvaExentoY0['iva_exento']);

        return $linea;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de nomina recibidos.
     * -------------------------------------------------------------------------
     */
    private function paginaNominaRecibidos(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.nomina_recibidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.facturas.registro_patronal'),
                __('dashboard.facturas.fecha_inicial_pago'),
                __('dashboard.facturas.fecha_final_pago'),
                __('dashboard.facturas.subsidio_al_empleo'),
                __('dashboard.reportes.impuesto_retenido_de_importe'),
            ],
            'lineas' => [],
        ];

        $facturas = Factura::query()
            ->where('rfc_receptor', $this->rfc)
            ->where('tipo_comprobante', 'N')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();
        
        foreach($facturas as $factura) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaNominaRecibido($factura)
            );
        }

        return $pagina;
    }

    private function generarLineaNominaRecibido(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;

        $linea = [
            $factura->uuid,
        ];

        if (
            $comprobante &&
            isset($comprobante->comprobante['Complemento']) &&
            isset($comprobante->comprobante['Complemento']['Nomina'])
        ) {
            $nomina = $comprobante->comprobante['Complemento']['Nomina'];
            array_push(
                $linea,
                isset($nomina['Emisor']['RegistroPatronal']) ? $nomina['Emisor']['RegistroPatronal'] : ''
            );

            array_push(
                $linea,
                isset($nomina['FechaInicialPago']) ? $nomina['FechaInicialPago'] : ''
            );
            array_push(
                $linea,
                isset($nomina['FechaFinalPago']) ? $nomina['FechaFinalPago'] : ''
            );

            if (isset($nomina['OtrosPagos'])) {
                $subsidioAlEmpleo = 0;
                foreach ($nomina['OtrosPagos']['OtroPago'] as $pago) {
                    if ($pago['TipoOtroPago'] == ComprobanteXml::OTRO_PAGO_SUBSIDIO_AL_EMPLEO) {
                        $subsidioAlEmpleo = floatval($pago['Importe']);
                    }
                }
                array_push($linea, $subsidioAlEmpleo);
            } else {
                array_push($linea, 0);
            }

            if (isset($nomina['Deducciones'])) {
                array_push(
                    $linea,
                    isset($nomina['Deducciones']['TotalImpuestosRetenidos']) ? 
                        $nomina['Deducciones']['TotalImpuestosRetenidos'] : 0
                );
            } else {
                array_push($linea, '');
            }

        } else {
            array_push($linea, '');
            array_push($linea, '');
            array_push($linea, '');
            array_push($linea, '');
            array_push($linea, '');
        }

        return $linea;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de pagos recibidos.
     * -------------------------------------------------------------------------
     */
    private function paginaPagosRecibidos(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.pagos_recibidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.facturas.rfc_emisor'),
                __('dashboard.facturas.nombre_emisor'),
                __('dashboard.facturas.fecha_de_pago'),
                __('dashboard.facturas.uuid_pago'),
                __('dashboard.facturas.serie'),
                __('dashboard.facturas.folio'),
                __('dashboard.facturas.moneda'),
                __('dashboard.facturas.saldo_anterior'),
                __('dashboard.facturas.pago'),
                __('dashboard.facturas.saldo_insoluto'),
            ],
            'lineas' => [],
        ];

        $facturas = Factura::query()
            ->where('rfc_receptor', $this->rfc)
            ->where('tipo_comprobante', 'P')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($facturas as $factura) {
            $pagina['lineas'] = array_merge(
                $pagina['lineas'],
                $this->generarLineaPagosRecibidos($factura)
            );
        }

        return $pagina;
    }

    private function generarLineaPagosRecibidos(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;
        $lineas = [];
        $linea = [
            $factura->uuid,
            $factura->rfc_emisor,
            $factura->nombre_emisor,
        ];

        if (
            $comprobante &&
            isset($comprobante->comprobante['Complemento']) &&
            isset($comprobante->comprobante['Complemento']['Pagos'])
        ) {
            $pagos = $comprobante->comprobante['Complemento']['Pagos']['Pago'];
            $documentos = [];

            foreach($pagos as $pago) {
                if (!isset($pago['DoctoRelacionado'])) {
                    continue;
                }
                foreach($pago['DoctoRelacionado'] as $documento) {
                    $documento = [
                        substr($pago['FechaPago'], 0, 10) ?? '',
                        $documento['IdDocumento'] ?? '',
                        $documento['Serie'] ?? '',
                        $documento['Folio'] ?? '',
                        $documento['MonedaDR'] ?? '',
                        $documento['ImpSaldoAnt'] ?? '',
                        $documento['ImpPagado'] ?? '',
                        $documento['ImpSaldoInsoluto'] ?? '',
                    ];

                    array_push($documentos, $documento);
                }
            }

            foreach($documentos as $documento) {
                array_push(
                    $lineas,
                    array_merge($linea, $documento)
                );
            }
        } else {
            array_push(
                $lineas,
                array_merge($linea, ['', '', '', '', '', '', '', ''])
            );
        }

        return $lineas;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de carta porte recibidos.
     * -------------------------------------------------------------------------
     */
    private function paginaCartaPorteRecibidos(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.carta_porte_recibidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.general.fecha'),
                __('dashboard.facturas.serie'),
                __('dashboard.facturas.folio'),
                __('dashboard.facturas.tipo_comprobante'),
                __('dashboard.facturas.rfc_emisor'),
                __('dashboard.facturas.nombre_emisor'),
                __('dashboard.facturas.metodo_pago'),
                __('dashboard.facturas.forma_pago'),
                __('dashboard.facturas.moneda'),
                __('dashboard.facturas.tipo_cambio'),
                __('dashboard.facturas.subtotal'),
                __('dashboard.reportes.impuesto_trasladado_iva'),
                __('dashboard.reportes.impuesto_trasladado_ieps'),
                __('dashboard.reportes.impuesto_retenido_iva'),
                __('dashboard.reportes.impuesto_retenido_isr'),
                __('dashboard.facturas.descuento'),
                __('dashboard.facturas.total'),
                __('dashboard.reportes.primer_concepto'),
            ],
            'lineas' => [],
        ];

        $ingresos = Factura::query()
            ->where('rfc_receptor', $this->rfc)
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->where('complementos','like','%CartaPorte%')
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($ingresos as $ingreso) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaIngresosRecibido($ingreso)
            );
        }

        return $pagina;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de egresos emitidos.
     * -------------------------------------------------------------------------
     */
    private function paginaIngresosEmitidos(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.ingresos_emitidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.general.fecha'),
                __('dashboard.facturas.serie'),
                __('dashboard.facturas.folio'),
                __('dashboard.facturas.tipo_comprobante'),
                __('dashboard.facturas.rfc_receptor'),
                __('dashboard.facturas.nombre_receptor'),
                __('dashboard.facturas.metodo_pago'),
                __('dashboard.facturas.forma_pago'),
                __('dashboard.facturas.moneda'),
                __('dashboard.facturas.tipo_cambio'),
                __('dashboard.facturas.subtotal'),
                __('dashboard.reportes.impuesto_trasladado_iva'),
                __('dashboard.reportes.impuesto_trasladado_ieps'),
                __('dashboard.reportes.impuesto_retenido_iva'),
                __('dashboard.reportes.impuesto_retenido_isr'),
                __('dashboard.facturas.descuento'),
                __('dashboard.facturas.total'),
                __('dashboard.reportes.primer_concepto'),
                __('dashboard.reportes.iva_tasa_0'),
                __('dashboard.reportes.iva_exento'),
                __('dashboard.reportes.tipo_contribuyente'),
                __('dashboard.reportes.regimen_contribuyente'),
                __('dashboard.reportes.validacion_rfc_emisor'),
                __('dashboard.facturas.uso_cfdi'),
                __('dashboard.reportes.validacion_uso_cfdi'),
                __('dashboard.reportes.validacion_metodo_forma_pago'),
                
            ],
            'lineas' => [],
        ];

        $ingresos = Factura::query()
            ->where('rfc_emisor', $this->rfc)
            ->where(function ($query) {
                return $query
                    ->where('tipo_comprobante', 'I')
                    ->orWhere('tipo_comprobante', 'E');
            })
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($ingresos as $ingreso) {
            array_push(
                $pagina['lineas'],
                array_merge(
                    $this->generarLineaIngresosEmitidos($ingreso),
                    $this->generarColumnasValidaciones($ingreso)
                )
            );
        }

        return $pagina;
    }

    private function generarLineaIngresosEmitidos(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;
        $linea = [
            $factura->uuid,
            $factura->fecha_emision->format('Y-m-d'),
            $factura->serie,
            $factura->folio,
            $factura->tipo_comprobante,
            $factura->rfc_receptor,
            $factura->nombre_receptor,
        ];

        if (!$comprobante) {
            return $linea;
        }

        array_push($linea, $comprobante->comprobante['MetodoPago'] ?? '');
        array_push($linea, $comprobante->comprobante['FormaPago'] ?? '');
        array_push($linea, $comprobante->comprobante['Moneda'] ?? '');
        array_push($linea, $comprobante->comprobante['TipoCambio'] ?? '');

        $multiplicador = $factura->tipo_comprobante == 'I' ? 1 : -1;

        array_push($linea, ConvertirMontoAPesos::convertir(
            $factura->subtotal,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);

        $impuestosTraslados = $comprobante->obtenerImpuestosTraslados();
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosTraslados['iva'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosTraslados['ieps'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);

        $impuestosRetenidos = $comprobante->obtenerImpuestosRetenidos();
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosRetenidos['iva'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosRetenidos['isr'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);

        array_push($linea, ConvertirMontoAPesos::convertir(
            $factura->descuento,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);
        array_push($linea, ConvertirMontoAPesos::convertir(
            $factura->total,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ) * $multiplicador);

        if (
            $comprobante &&
            isset($comprobante->comprobante['Conceptos']) &&
            isset($comprobante->comprobante['Conceptos']['Concepto']) &&
            isset($comprobante->comprobante['Conceptos']['Concepto'][0])
        ) {
            array_push($linea, $comprobante->comprobante['Conceptos']['Concepto'][0]['Descripcion'] ?? '');
        } else {
            array_push($linea, '');
        }

        $columnasIvaExentoY0 = $this->obtenerColumnasIva0YExento($comprobante);
        array_push($linea, $columnasIvaExentoY0['iva_0']);
        array_push($linea, $columnasIvaExentoY0['iva_exento']);

        return $linea;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de egresos emitidos.
     * -------------------------------------------------------------------------
     */
    private function paginaEgresosEmitidos(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.egresos_emitidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.general.fecha'),
                __('dashboard.facturas.serie'),
                __('dashboard.facturas.folio'),
                __('dashboard.facturas.tipo_comprobante'),
                __('dashboard.facturas.rfc_receptor'),
                __('dashboard.facturas.nombre_receptor'),
                __('dashboard.facturas.metodo_pago'),
                __('dashboard.facturas.forma_pago'),
                __('dashboard.facturas.moneda'),
                __('dashboard.facturas.tipo_cambio'),
                __('dashboard.facturas.subtotal'),
                __('dashboard.reportes.impuesto_trasladado_iva'),
                __('dashboard.reportes.impuesto_trasladado_ieps'),
                __('dashboard.reportes.impuesto_retenido_iva'),
                __('dashboard.reportes.impuesto_retenido_isr'),
                __('dashboard.facturas.descuento'),
                __('dashboard.facturas.total'),
                __('dashboard.reportes.primer_concepto'),
                __('dashboard.reportes.iva_tasa_0'),
                __('dashboard.reportes.iva_exento'),
            ],
            'lineas' => [],
        ];

        $ingresos = Factura::query()
            ->where('rfc_emisor', $this->rfc)
            ->where('tipo_comprobante', 'E')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($ingresos as $ingreso) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaEgresosEmitidos($ingreso)
            );
        }

        return $pagina;
    }

    private function generarLineaEgresosEmitidos(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;
        $linea = [
            $factura->uuid,
            $factura->fecha_emision->format('Y-m-d'),
            $factura->serie,
            $factura->folio,
            $factura->tipo_comprobante,
            $factura->rfc_receptor,
            $factura->nombre_receptor,
        ];

        if (!$comprobante) {
            return $linea;
        }

        array_push($linea, $comprobante->comprobante['MetodoPago'] ?? '');
        array_push($linea, $comprobante->comprobante['FormaPago'] ?? '');
        array_push($linea, $comprobante->comprobante['Moneda'] ?? '');
        array_push($linea, $comprobante->comprobante['TipoCambio'] ?? '');

        array_push($linea, ConvertirMontoAPesos::convertir(
            $factura->subtotal,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ));

        $impuestosTraslados = $comprobante->obtenerImpuestosTraslados();
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosTraslados['iva'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ));
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosTraslados['ieps'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ));

        $impuestosRetenidos = $comprobante->obtenerImpuestosRetenidos();
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosRetenidos['iva'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ));
        array_push($linea, ConvertirMontoAPesos::convertir(
            $impuestosRetenidos['isr'],
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ));

        array_push($linea, ConvertirMontoAPesos::convertir(
            $factura->descuento,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ));
        array_push($linea, ConvertirMontoAPesos::convertir(
            $factura->total,
            $comprobante->comprobante['Moneda'] ?? '',
            $comprobante->comprobante['TipoCambio'] ?? 1
        ));

        if (
            $comprobante &&
            isset($comprobante->comprobante['Conceptos']) &&
            isset($comprobante->comprobante['Conceptos']['Concepto']) &&
            isset($comprobante->comprobante['Conceptos']['Concepto'][0])
        ) {
            array_push($linea, $comprobante->comprobante['Conceptos']['Concepto'][0]['Descripcion'] ?? '');
        } else {
            array_push($linea, '');
        }

        $columnasIvaExentoY0 = $this->obtenerColumnasIva0YExento($comprobante);
        array_push($linea, $columnasIvaExentoY0['iva_0']);
        array_push($linea, $columnasIvaExentoY0['iva_exento']);

        return $linea;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de nomina emitidos.
     * -------------------------------------------------------------------------
     */
    private function paginaNominaEmitido(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.nomina_emitidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.facturas.registro_patronal'),
                __('dashboard.facturas.fecha_inicial_pago'),
                __('dashboard.facturas.fecha_final_pago'),
                __('dashboard.facturas.subsidio_al_empleo'),
                __('dashboard.reportes.impuesto_retenido_de_importe'),
            ],
            'lineas' => [],
        ];

        $facturas = Factura::query()
            ->where('rfc_emisor', $this->rfc)
            ->where('tipo_comprobante', 'N')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();
        
        foreach($facturas as $factura) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaNominaEmitido($factura)
            );
        }

        return $pagina;
    }

    private function generarLineaNominaEmitido(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;

        $linea = [
            $factura->uuid,
        ];

        if (
            $comprobante &&
            isset($comprobante->comprobante['Complemento']) &&
            isset($comprobante->comprobante['Complemento']['Nomina'])
        ) {
            $nomina = $comprobante->comprobante['Complemento']['Nomina'];
            array_push(
                $linea,
                isset($nomina['Emisor']['RegistroPatronal']) ? $nomina['Emisor']['RegistroPatronal'] : ''
            );

            array_push(
                $linea,
                isset($nomina['FechaInicialPago']) ? $nomina['FechaInicialPago'] : ''
            );
            array_push(
                $linea,
                isset($nomina['FechaFinalPago']) ? $nomina['FechaFinalPago'] : ''
            );

            if (isset($nomina['OtrosPagos'])) {
                $subsidioAlEmpleo = 0;
                foreach ($nomina['OtrosPagos']['OtroPago'] as $pago) {
                    if ($pago['TipoOtroPago'] == ComprobanteXml::OTRO_PAGO_SUBSIDIO_AL_EMPLEO) {
                        $subsidioAlEmpleo = floatval($pago['Importe']);
                    }
                }
                array_push($linea, $subsidioAlEmpleo);
            } else {
                array_push($linea, 0);
            }

            if (isset($nomina['Deducciones'])) {
                array_push(
                    $linea,
                    isset($nomina['Deducciones']['TotalImpuestosRetenidos']) ? 
                        $nomina['Deducciones']['TotalImpuestosRetenidos'] : 0
                );
            } else {
                array_push($linea, '');
            }

        } else {
            array_push($linea, '');
            array_push($linea, '');
            array_push($linea, '');
            array_push($linea, '');
            array_push($linea, '');
        }

        return $linea;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de pagos emitidos.
     * -------------------------------------------------------------------------
     */
    private function paginaPagosEmitidos(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.pagos_emitidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.facturas.rfc_receptor'),
                __('dashboard.facturas.nombre_receptor'),
                __('dashboard.facturas.fecha_de_pago'),
                __('dashboard.facturas.uuid_pago'),
                __('dashboard.facturas.serie'),
                __('dashboard.facturas.folio'),
                __('dashboard.facturas.moneda'),
                __('dashboard.facturas.saldo_anterior'),
                __('dashboard.facturas.pago'),
                __('dashboard.facturas.saldo_insoluto'),
            ],
            'lineas' => [],
        ];

        $facturas = Factura::query()
            ->where('rfc_emisor', $this->rfc)
            ->where('tipo_comprobante', 'P')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($facturas as $factura) {
            $pagina['lineas'] = array_merge(
                $pagina['lineas'],
                $this->generarLineaPagosEmitidos($factura)
            );
        }

        return $pagina;
    }

    private function generarLineaPagosEmitidos(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;
        $lineas = [];
        $linea = [
            $factura->uuid,
            $factura->rfc_receptor,
            $factura->nombre_receptor,
        ];

        if (
            $comprobante &&
            isset($comprobante->comprobante['Complemento']) &&
            isset($comprobante->comprobante['Complemento']['Pagos'])
        ) {
            $pagos = $comprobante->comprobante['Complemento']['Pagos']['Pago'];
            $documentos = [];

            foreach($pagos as $pago) {
                foreach($pago['DoctoRelacionado'] as $documento) {
                    $documento = [
                        substr($pago['FechaPago'], 0, 10) ?? '',
                        $documento['IdDocumento'] ?? '',
                        $documento['Serie'] ?? '',
                        $documento['Folio'] ?? '',
                        $documento['MonedaDR'] ?? '',
                        $documento['ImpSaldoAnt'] ?? '',
                        $documento['ImpPagado'] ?? '',
                        $documento['ImpSaldoInsoluto'] ?? '',
                    ];

                    array_push($documentos, $documento);
                }
            }

            foreach($documentos as $documento) {
                array_push(
                    $lineas,
                    array_merge($linea, $documento)
                );
            }
        } else {
            array_push(
                $lineas,
                array_merge($linea, ['', '', '', '', '', '', '', ''])
            );
        }

        return $lineas;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de carta porte emitidos.
     * -------------------------------------------------------------------------
     */
    private function paginaCartaPorteEmitidos(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.carta_porte_emitidos'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.general.fecha'),
                __('dashboard.facturas.serie'),
                __('dashboard.facturas.folio'),
                __('dashboard.facturas.tipo_comprobante'),
                __('dashboard.facturas.rfc_emisor'),
                __('dashboard.facturas.nombre_emisor'),
                __('dashboard.facturas.metodo_pago'),
                __('dashboard.facturas.forma_pago'),
                __('dashboard.facturas.moneda'),
                __('dashboard.facturas.tipo_cambio'),
                __('dashboard.facturas.subtotal'),
                __('dashboard.reportes.impuesto_trasladado_iva'),
                __('dashboard.reportes.impuesto_trasladado_ieps'),
                __('dashboard.reportes.impuesto_retenido_iva'),
                __('dashboard.reportes.impuesto_retenido_isr'),
                __('dashboard.facturas.descuento'),
                __('dashboard.facturas.total'),
                __('dashboard.reportes.primer_concepto'),
            ],
            'lineas' => [],
        ];

        $facturas = Factura::query()
            ->where('rfc_emisor', $this->rfc)
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->where('complementos','like','%CartaPorte%')
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($facturas as $factura) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaIngresosRecibido($factura)
            );
        }

        return $pagina;
    }

    private function obtenerColumnasIva0YExento($comprobante)
    {
        $columnas = [
            'iva_0' => 0,
            'iva_exento' => 0,
        ];

        $impuestosConceptos = $comprobante->obtenerImpuestosTrasladosDeConceptos();

        foreach ($impuestosConceptos as $impuesto) {
            if ($impuesto['Impuesto'] == ComprobanteXml::IMPUESTO_IVA) {
                if ($impuesto['TipoFactor']  == ComprobanteXml::TIPO_FACTOR_EXENTO) {
                    $columnas['iva_exento'] = 1;
                }
                if (
                    isset($impuesto['TasaOCuota']) &&
                    $impuesto['TasaOCuota'] == ComprobanteXml::TIPO_FACTOR_TASA &&
                    $impuesto['TipoFactor'] == 0
                ) {
                    $columnas['iva_0'] = 1;
                }
            }
        }

        return $columnas;
    }

    private function generarColumnasValidaciones(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;
        $linea = [];

        $regimenEmisor = $comprobante->obtenerRegimenEmisor();
        $formaDePago = $comprobante->obtenerFormaDePago();
        $metodoDePago = $comprobante->obtenerMetodoDePago();

        array_push($linea, ucfirst(TipoPersona::obtenerTipoPersona($factura->rfc_emisor)));
        array_push($linea, $regimenEmisor);

        array_push(
            $linea,
            ValidacionesFacturasRecibidas::validacionRfcContraRegimenFiscal(
                $factura->rfc_emisor,
                $regimenEmisor
            ) ? '' : __('dashboard.reportes.regimen_emisor_invalido')
        );

        $usoCfdi = $comprobante->obtenerUsoCfdi();
        array_push($linea, $usoCfdi);

        array_push(
            $linea,
            ValidacionesFacturasRecibidas::usoCfdiCorrecto($usoCfdi) ? '':
                __('dashboard.reportes.corregir_uso_cfdi')
        );

        if (
            ($metodoDePago == 'PPD' && $formaDePago != 99) ||
            ($metodoDePago == 'PUE' && $formaDePago == 99)
        ) {
            array_push($linea, __('dashboard.reportes.myf_erroneo'));
        } else {
            array_push($linea, '');
        }

        return $linea;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de facturas canceladas en el año
     * -------------------------------------------------------------------------
     */
    private function paginaFacturasCanceladas(): array
    {
        $pagina = [
            'titulo' => __('dashboard.reportes.canceladas'),
            'encabezados' => [
                __('dashboard.facturas.uuid'),
                __('dashboard.general.fecha'),
                __('dashboard.facturas.serie'),
                __('dashboard.facturas.folio'),
                __('dashboard.facturas.tipo_comprobante'),
                __('dashboard.facturas.rfc_emisor'),
                __('dashboard.facturas.nombre_emisor'),
                __('dashboard.facturas.rfc_receptor'),
                __('dashboard.facturas.nombre_receptor'),
                __('dashboard.facturas.estatus_cancelacion'),
                __('dashboard.facturas.fecha_proceso_cancelacion'),
                __('dashboard.facturas.estatus_proceso_cancelacion'),
            ],
            'lineas' => [],
        ];

        $rfc = $this->rfc;
        $desde = now()->startOfYear();
        $hasta = now()->endOfYear();

        $facturas = Factura::query()
            ->where(function ($query) use($rfc) {
                return $query
                    ->where('rfc_emisor', $rfc)
                    ->orWhere('rfc_receptor', $rfc);
            })
            ->whereDate('fecha_emision', '>=', $desde)
            ->whereDate('fecha_emision', '<=', $hasta)
            ->cancelados()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($facturas as $factura) {
            $pagina['lineas'][] = $this->generarLineaCanceladas($factura);
        }

        return $pagina;
    }

    private function generarLineaCanceladas(Factura $factura)
    {
        $linea = [];

        array_push($linea, $factura->uuid);
        array_push($linea, $factura->fecha_emision->format('Y-m-d'));
        array_push($linea, $factura->serie);
        array_push($linea, $factura->folio);
        array_push($linea, $factura->tipo_comprobante);
        array_push($linea, $factura->rfc_emisor);
        array_push($linea, $factura->nombre_emisor);
        array_push($linea, $factura->rfc_receptor);
        array_push($linea, $factura->nombre_receptor);
        array_push($linea, $factura->estatus_cancelacion);
        array_push(
            $linea,
            $factura->fecha_proceso_cancelacion ?
                $factura->fecha_proceso_cancelacion->format('Y-m-d') : ''
        );
        array_push($linea, $factura->estatus_proceso_cancelacion);

        return $linea;
    }

}