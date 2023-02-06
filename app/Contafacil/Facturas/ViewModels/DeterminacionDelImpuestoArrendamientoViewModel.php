<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\Facturas\CalcularIvaAcreditableAGasto;
use App\Acciones\TablasTarifas\ResolverTablaTarifaAAplicar;
use App\Contafacil\Compartido\Contratos\DebeTenerBaseMaxima;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Enums\DeterminacionImpuestosEnum;
use App\Enums\RegimenFiscal;
use App\Enums\TipoIngreso;
use App\Models\Cliente;
use Carbon\Carbon;

class DeterminacionDelImpuestoArrendamientoViewModel extends ViewModel implements DebeTenerBaseMaxima
{
    private $ventasCobradas;
    private $camposEditables;

    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha
    ) {
        $this->ventasCobradas = $this->cliente->facturasCliente()
            ->with('factura')
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->tiposIngreso([
                TipoIngreso::ARRENDAMIENTO,
            ])
            ->esVenta()
            ->esConsiderado()
            ->get();

        $this->camposEditables = $cliente->determinacionCamposEditables()
            ->buscarPorRegimen(RegimenFiscal::RESICO)
            ->buscarPorMes($this->fecha)
            ->get();
    }

    public function ingresos()
    {
        return $this->ventasCobradas->calcularIngresos(0);
    }

    public function ingresosAcumulados()
    {
        // $ingresosAnteriores = ($this->determinacionPasada) ? $this->determinacionPasada->ingresos_acumulados : 0;
        $ingresosAnteriores = 0;
        return round($ingresosAnteriores + $this->ingresos(), 0);
    }

    public function deducciones()
    {
        $deducciones = $this->ingresos() * 0.35;

        return round($deducciones, 0);
    }

    public function deduccionesAcumuladas()
    {
        return round($this->deducciones(), 0);
    }

    public function predial()
    {
        $campo = $this->camposEditables->firstWhere('clave', DeterminacionImpuestosEnum::CAMPO_PREDIAL);

        return $campo ? floatval($campo->valor) : 0;
    }

    public function depreciacion()
    {
        $campo = $this->camposEditables->firstWhere('clave', DeterminacionImpuestosEnum::CAMPO_DEPRECIACION);

        return $campo ? floatval($campo->valor) : 0;
    }

    public function totalDeducciones()
    {
        $total = $this->deduccionesAcumuladas() + $this->predial() + $this->depreciacion();

        return round($total, 0);
    }

    public function perdidasEjercicioAnterior()
    {
        $ultimoValor = $this->cliente->determinacionCamposEditables()->buscarUltimoMesConValor(
            DeterminacionImpuestosEnum::CAMPO_PERDIDA_EJERCICIOS_ANTERIORES,
            $this->fecha,
            RegimenFiscal::ARRENDAMIENTO
        )->first();

        return $ultimoValor ? floatval($ultimoValor->valor) : 0;
    }

    public function perdidasFiscalesMesAnterior(): float
    {
        $ultimoValor = $this->cliente->determinacionCamposEditables()->buscarMesPrevioConValor(
            DeterminacionImpuestosEnum::CAMPO_PERDIDA_EJERCICIOS_ANTERIORES,
            $this->fecha,
            RegimenFiscal::ARRENDAMIENTO
        )->first();

        return $ultimoValor ? floatval($ultimoValor->valor) : 0;
    }

    public function baseMaxima(): float
    {
        $base = $this->ingresos() - $this->totalDeducciones();

        return ($base < 0) ? 0: round($base, 2);
    }

    public function base()
    {
        $base = $this->ingresos() - $this->totalDeducciones() - $this->perdidasEjercicioAnterior();

        return ($base < 0) ? 0: round($base, 2);
    }

    public function calculosTarifa()
    {
        $datosTabla = [
            'limite_inferior'      => 0,
            'limite_superior'      => 0,
            'cuota_fija'           => 0,
            'porcentaje_excedente' => 0,
            'excedente'            => 0,
            'importe_marginal'     => 0,
            'isr_arrendamiento'    => 0,
            'impuesto_a_cargo'     => 0,
            'total'                => 0,
        ];
        $base = $this->base();

        if ($base == 0) return $datosTabla;

        $tarifa = ResolverTablaTarifaAAplicar::ejecutar(
            '612', $this->fecha->year, 1,
            $this->base()
        );
        if (!$tarifa) return $datosTabla;

        // Datos de la tabla de tarifa
        $datosTabla['limite_inferior']      = $tarifa->limite_inferior;
        $datosTabla['limite_superior']      = $tarifa->limite_superior;
        $datosTabla['cuota_fija']           = $tarifa->cuota_fija;
        $datosTabla['porcentaje_excedente'] = $tarifa->porcentaje_excedente;

        // EXCEDENTE: Base - Limite Inferior
        $datosTabla['excedente']        = round($base - $datosTabla['limite_inferior'], 2);
        // IMPORTE MARGINAL: Excedente * Porcentaje Excedente
        $datosTabla['importe_marginal'] = round($datosTabla['excedente'] * $datosTabla['porcentaje_excedente'], 2);
        // ISR ACTIVIDAD: Cuota Fija + Importe Marginal
        $datosTabla['isr_arrendamiento']    = round($datosTabla['cuota_fija'] + $datosTabla['importe_marginal'], 2);
        // Impuesto a cargo: ISR arrendamiento
        $datosTabla['impuesto_a_cargo'] = $datosTabla['isr_arrendamiento'];
        // TOTAL: Impuesto a cargo - ISR retenido
        $datosTabla['total'] = round($datosTabla['impuesto_a_cargo'] - $this->isrRetenido(), 2);

        return $datosTabla;
    }

    public function isrRetenido()
    {
        return $this->ventasCobradas->sumatoriaRetencionesIsr(0);
    }
}
