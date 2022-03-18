<?php
namespace App\Reportes;

use App\Enums\TipoPersona;
use App\Models\Cliente;
use App\Models\Factura;
use App\Reportes\Validaciones\ValidacionesFacturasRecibidas;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;

class ReporteElectronica implements ReporteFacturacionPF
{
    /** @var DateTimeImmutable */
    private $fechaInicio;
    /** @var DateTimeImmutable */
    private $fechaFin;

    private $rfc;
    private $cliente;

    public function __construct(
        string $rfc,
        DateTimeImmutable $fechaInicio,
        DateTimeImmutable $fechaFin
    ) {
        $this->rfc = $rfc;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;

        $this->cliente = Cliente::where('rfc', $rfc)->first();
    }

    public function nombreArchivo(): string
    {
        return 'reporte_electronica_' .
            $this->rfc . '_' .
            $this->fechaInicio->format('Y-m-d') . '_' .
            $this->fechaFin->format('Y-m-d') . '_' .
            '.xlsx';
    }

    public function encabezados(): array
    {
        return [];
    }

    public function informacionCliente(): array
    {
        return [
            'nombre' => $this->cliente->razon_social ?? '',
            'rfc' => $this->rfc ?? '',
        ];
    }

    public function paginas(): array
    {
        $paginas = [
            $this->paginaIngresosEmitidos(),
            $this->paginaEgresosEmitidos(),
            $this->paginaPagosEmitidos(),
            $this->paginaIngresosRecibidos(),
            $this->paginaPagosRecibidos(),
            $this->paginaEgresosRecibidos(),
        ];

        return $paginas;
    }

    /**
     * -------------------------------------------------------------------------
     * Página de ingreos emitidos.
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
            ],
            'lineas' => [],
        ];

        $facturas = Factura::query()
            ->where('rfc_emisor', $this->rfc)
            ->where('tipo_comprobante', 'I')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($facturas as $factura) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaIngresoEmitido($factura)
            );
        }

        return $pagina;
    }

    private function generarLineaIngresoEmitido(Factura $factura)
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
            $linea = array_merge($linea, [
                '', '', '', '', '', '', '', '', '', '', '', '',
            ]);

            return $linea;
        }

        array_push($linea, $comprobante->comprobante['MetodoPago'] ?? '');
        array_push($linea, $comprobante->comprobante['FormaPago'] ?? '');
        array_push($linea, $comprobante->comprobante['Moneda'] ?? '');
        array_push($linea, $comprobante->comprobante['TipoCambio'] ?? '');

        array_push($linea, $factura->subtotal);

        $impuestosTraslados = $comprobante->obtenerImpuestosTraslados();
        array_push($linea, $impuestosTraslados['iva']);
        array_push($linea, $impuestosTraslados['ieps']);

        $impuestosRetenidos = $comprobante->obtenerImpuestosRetenidos();
        array_push($linea, $impuestosRetenidos['iva']);
        array_push($linea, $impuestosRetenidos['isr']);

        array_push($linea, $factura->descuento);
        array_push($linea, $factura->total);

        array_push($linea, $comprobante->obtenerDescripcionPrimerConcepto());

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
            ],
            'lineas' => [],
        ];

        $facturas = Factura::query()
            ->where('rfc_emisor', $this->rfc)
            ->where('tipo_comprobante', 'E')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($facturas as $factura) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaIngresoEmitido($factura)
            );
        }

        return $pagina;
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

        if (!$comprobante) {
            array_push(
                $lineas,
                array_merge($linea, ['', '', '', '', '', '', '', ''])
            );
            return $lineas;
        }

        $documentosPagados = $comprobante->obtenerDocumentosPagados();
        foreach ($documentosPagados as $documento) {
            array_push(
                $lineas,
                array_merge($linea, $documento)
            );
        }

        return $lineas;
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
                __('dashboard.reportes.tipo_contribuyente'),
                __('dashboard.reportes.regimen_contribuyente'),
                __('dashboard.reportes.validacion_rfc_emisor'),
                __('dashboard.facturas.uso_cfdi'),
                __('dashboard.reportes.validacion_uso_cfdi'),
                __('dashboard.reportes.validacion_metodo_forma_pago'),
                __('dashboard.reportes.uuid_complemento_relacionado'),
                __('dashboard.facturas.mes_pago'),
                __('dashboard.facturas.pagos'),
            ],
            'lineas' => [],
        ];

        $facturas = Factura::query()
            ->where('rfc_receptor', $this->rfc)
            ->where('tipo_comprobante', 'I')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($facturas as $factura) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaIngresoRecibidos($factura)
            );
        }

        return $pagina;
    }

    private function generarLineaIngresoRecibidos(Factura $factura)
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
            $linea = array_merge($linea, [
                '', '', '', '', '', '', '', '', '', '', '', '',
            ]);

            return $linea;
        }

        $formaDePago = $comprobante->obtenerFormaDePago();
        $metodoDePago = $comprobante->obtenerMetodoDePago();
        array_push($linea, $metodoDePago);
        array_push($linea, $formaDePago);
        array_push($linea, $comprobante->comprobante['Moneda'] ?? '');
        array_push($linea, $comprobante->comprobante['TipoCambio'] ?? '');

        array_push($linea, $factura->subtotal);

        $impuestosTraslados = $comprobante->obtenerImpuestosTraslados();
        array_push($linea, $impuestosTraslados['iva']);
        array_push($linea, $impuestosTraslados['ieps']);

        $impuestosRetenidos = $comprobante->obtenerImpuestosRetenidos();
        array_push($linea, $impuestosRetenidos['iva']);
        array_push($linea, $impuestosRetenidos['isr']);

        array_push($linea, $factura->descuento);
        array_push($linea, $factura->total);

        array_push($linea, $comprobante->obtenerDescripcionPrimerConcepto());

        array_push($linea, ucfirst(TipoPersona::obtenerTipoPersona($factura->rfc_emisor)));

        $regimenEmisor = $comprobante->obtenerRegimenEmisor();
        array_push($linea, $regimenEmisor);

        if (
            ValidacionesFacturasRecibidas::validacionRfcContraRegimenFiscal(
                $factura->rfc_emisor,
                $regimenEmisor
            )
        ) {
            array_push($linea, '');
        } else {
            array_push($linea, __('dashboard.reportes.regimen_emisor_invalido'));
        }

        $usoCfdi = $comprobante->obtenerUsoCfdi();
        array_push($linea, $usoCfdi);

        if (ValidacionesFacturasRecibidas::usoCfdiCorrecto($usoCfdi)) {
            array_push($linea, '');
        } else {
            array_push($linea, __('dashboard.reportes.corregir_uso_cfdi'));
        }

        if (
            ($metodoDePago == 'PPD' && $formaDePago != 99) ||
            ($metodoDePago == 'PUE' && $formaDePago == 99)
        ) {
            array_push($linea, __('dashboard.reportes.myf_erroneo'));
        } else {
            array_push($linea, '');
        }

        $uuidsRelacionados = '';
        $totalPagos = null;
        $mesCfdi = null;

        if ($metodoDePago == 'PPD') {
            $facturasRelacionadas = Factura::whereHas('comprobanteXml', function (Builder $query) use ($factura) {
                $query->where('comprobante', "LIKE", "%" . $factura->uuid . "%");
            })->where('tipo_comprobante', 'P')->get();
            $uuidsRelacionados = $facturasRelacionadas->pluck('uuid')->implode(', ');

            $mesesCfdi = $facturasRelacionadas->map(function ($factura) {
                $comprobante = $factura->comprobanteXml;
                $totalPagos = '';

                if ($comprobante) {
                    $documentosPagados = collect($comprobante->obtenerDocumentosPagados());
                    $totalPagos = $documentosPagados->sum('ImpPagado');
                }

                return [
                    'emision' => $factura->fecha_emision->monthName,
                    'total' => $totalPagos,
                ];
            });

            $mesCfdi = $mesesCfdi->pluck('emision')->unique()->implode(', ');
            $totalPagos = $mesesCfdi->pluck('total')->sum();
        } else if ($metodoDePago == 'PUE') {
            $mesCfdi = $factura->fecha_emision->monthName;
        }

        /**
         * Si el Método de pago es PPD buscar el UUID en la base de datos ACUMULADA
         * de cmplementos de pago (emitidos o recibidos según corresponda)
         */
        array_push($linea, $uuidsRelacionados);

        /**
         * Si el Método de pago es PUE: Poner el nombre del mes de emisión.                                                                                         Si el 
         * Método es PPD y se encontró UUID en la validación anterior anotar el mes 
         *  y año correspondiente a la fecha de pago
         * Si el Método es PPD y no hay complemento encontrado arrojar mensaje "Complemento".
         * Si en VAL_MFP Arrojó error entonces arrojar mensjae "MYF Erróneo"
         */
        array_push($linea, $mesCfdi);

        /**
         * Sumar el importe de la columna "Pago" de todos los complementos de pago que coincidan
         * con el UUID identificado con Complemento de pago relacionado y el importe resultante
         * restarlo al TOTAL de la factura original. Mostrar el resultado
         */
        array_push($linea, $totalPagos);

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
                __('dashboard.general.fecha'),
                __('dashboard.facturas.fecha_de_pago'),
                __('dashboard.facturas.uuid_pago'),
                __('dashboard.facturas.serie_pago'),
                __('dashboard.facturas.folio_pago'),
                __('dashboard.facturas.moneda_pago'),
                __('dashboard.facturas.saldo_anterior'),
                __('dashboard.facturas.pago'),
                __('dashboard.facturas.saldo_insoluto'),
                __('dashboard.facturas.mes_pago'),
                __('dashboard.facturas.mes_emision_cfdi'),
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
            array_push(
                $pagina['lineas'],
                $this->generarLineaPagosRecibidos($factura)
            );
        }

        return $pagina;
    }

    private function generarLineaPagosRecibidos(Factura $factura)
    {
        $comprobante = $factura->comprobanteXml;
        $linea = [
            $factura->uuid,
            $factura->rfc_emisor,
            $factura->nombre_emisor,
            $factura->fecha_emision->format('Y-m-d'),
        ];

        if (!$comprobante) {
            return $linea;
        }

        $pagos = $comprobante->obtenerPagosDelComplemento();
        if (count($pagos) == 0) {
            return $linea;
        }

        $primerPago = $pagos[0];
        $fechaPago = Carbon::parse($primerPago['FechaPago']);
        array_push($linea, $fechaPago->format('Y-m-d'));
        array_push($linea, $primerPago['DoctoRelacionado'][0]['IdDocumento'] ?? '');
        array_push($linea, $primerPago['DoctoRelacionado'][0]['Serie'] ?? '');
        array_push($linea, $primerPago['DoctoRelacionado'][0]['Folio'] ?? '');
        array_push($linea, $primerPago['MonedaP'] ?? '');
        array_push($linea, $primerPago['ImpSaldoAnt'] ?? '');
        array_push($linea, $primerPago['ImpPagado'] ?? '');
        array_push($linea, $primerPago['ImpSaldoInsoluto'] ?? '');
        array_push($linea, $fechaPago->monthName);
        $facturaRelacionada = Factura::query()
            ->where('uuid', $primerPago['DoctoRelacionado'][0]['IdDocumento'])
            ->first();

        if ($facturaRelacionada) {
            array_push($linea, $factura->fecha_emision->monthName);
        } else {
            array_push($linea, __('dashboard.reportes.cfdi_no_encontrado'));
        }

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
            ],
            'lineas' => [],
        ];

        $facturas = Factura::query()
            ->where('rfc_receptor', $this->rfc)
            ->where('tipo_comprobante', 'E')
            ->whereDate('fecha_emision', '>=', $this->fechaInicio)
            ->whereDate('fecha_emision', '<=', $this->fechaFin)
            ->vigentes()
            ->orderBy('fecha_emision')
            ->orderBy('serie')
            ->orderBy('folio')
            ->get();

        foreach ($facturas as $factura) {
            array_push(
                $pagina['lineas'],
                $this->generarLineaEgresosRecibidos($factura)
            );
        }

        return $pagina;
    }

    private function generarLineaEgresosRecibidos(Factura $factura)
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

        array_push($linea, $factura->subtotal);

        $impuestosTraslados = $comprobante->obtenerImpuestosTraslados();
        array_push($linea, $impuestosTraslados['iva']);
        array_push($linea, $impuestosTraslados['ieps']);

        $impuestosRetenidos = $comprobante->obtenerImpuestosRetenidos();
        array_push($linea, $impuestosRetenidos['iva']);
        array_push($linea, $impuestosRetenidos['isr']);

        array_push($linea, $factura->descuento);
        array_push($linea, $factura->total);

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

        return $linea;
    }

}