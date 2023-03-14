<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\Facturas\CalcularIvaAcreditableAGasto;
use App\Acciones\PolizasNominas\DeduccionesSueldosSalariosAsimiladosPolizasNomina;
use App\Acciones\PolizasNominas\DeduccionesImssInfonavitSarIsnPolizasNomina;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use Carbon\Carbon;

/*
 | -------------------------------------------------------------------
 | Generar tabla deducciones
 | -------------------------------------------------------------------
 */
class ColumnasDeduccionesViewModel extends ViewModel
{
    private $ventasCobradas;
    private $gastosPagados;

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
            ->esVenta()
            ->esConsiderado()
            ->get();

        $this->gastosPagados = $this->cliente->facturasCliente()
            ->with('factura')
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->esGasto()
            ->esConsiderado()
            ->get();
    }

    public function conceptos(): array
    {
        $deduccionesCompras = $this->gastosPagados->comprasGastosDevolucionesFacturadosPagados(0);
        $ivaAcreditableAGastos = CalcularIvaAcreditableAGasto::ejecutar(
            $this->ventasCobradas,
            $this->gastosPagados,
            0,
        );

        $conceptos = [
            [
                'id'        => 1,
                'titulo'    => 'Compras, gastos y devoluciones facturados y pagados',
                'importe'   => $deduccionesCompras + $ivaAcreditableAGastos,
                'deducible' => true,
            ],
            [
                'id'        => 2,
                'titulo'    => 'Otros gastos deducibles',
                'importe'   => 0,
                'deducible' => true,
            ],
            [
                'id'        => 3,
                'titulo'    => 'IMSS, INFONAVIT, SAR, ISN',
                'importe'   => DeduccionesImssInfonavitSarIsnPolizasNomina::ejecutar($this->cliente, $this->fecha),
                'deducible' => true,
            ],
            [
                'id'        => 4,
                'titulo'    => 'Sueldos, Salarios y Asimilados',
                'importe'   => DeduccionesSueldosSalariosAsimiladosPolizasNomina::ejecutar($this->cliente, $this->fecha),
                'deducible' => true,
            ],
            [
                'id'        => 5,
                'titulo'    => 'Ingresos exentos',
                'importe'   => 0,
                'deducible' => false,
            ],
            [
                'id'        => 6,
                'titulo'    => 'Ingresos Exentos de los trabajadores Deducibles para el patrón',
                'importe'   => 0,
                'deducible' => true,
            ],
            [
                'id'        => 7,
                'titulo'    => 'Exentos no deducibles',
                'importe'   => 0,
                'deducible' => false,
            ],
            [
                'id'        => 8,
                'titulo'    => 'Gastos no deducibles',
                'importe'   => 0,
                'deducible' => false,
            ],
            [
                'id'        => 9,
                'titulo'    => 'Amarre Compras y gastos Declarados VS Contables (sólo AEP)',
                'importe'   => 0,
                'deducible' => false,
            ],
        ];

        return $conceptos;
    }
}
