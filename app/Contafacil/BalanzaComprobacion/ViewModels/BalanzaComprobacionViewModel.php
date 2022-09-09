<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Acciones\NumerosCuentas\ResolverFormulaNumeroCuenta;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Contafacil\Polizas\ViewModels\PolizasAutomaticasVentasYGastosViewModel;
use App\Models\BalanzaComprobacion;
use App\Models\BalanzaComprobacionCliente;
use App\Models\Cliente;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;
use Carbon\Carbon;

class BalanzaComprobacionViewModel extends ViewModel
{
    /** @var Cliente $cliente */
    private $cliente;
    /** @var Carbon $fechaInicio */
    private $fechaInicio;
    /** @var Carbon $fechaFin */
    private $fechaFin;

    /** @var array $polizasVentas*/
    private $polizasVentas;
    /** @var array $polizasGastos*/
    private $polizasGastos;

    public function __construct(Carbon $fechaInicio, Carbon $fechaFin, Cliente $cliente)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
        $this->cliente   = $cliente;

        $this->polizasVentas = (new PolizasAutomaticasVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_VENTAS,
            $fechaInicio,
            $fechaFin,
            $cliente->planetafiscal_id
        ))->toArray();

        $this->polizasGastos = (new PolizasAutomaticasVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_GASTOS,
            $fechaInicio,
            $fechaFin,
            $cliente->planetafiscal_id
        ))->toArray();
    }

    public function balanzaComprobacion(): array
    {
        $cuentasBalanza = BalanzaComprobacion::all();

        $ventasPorEmision = collect($this->polizasVentas['fecha_emision']);
        $ventasPorPago    = collect($this->polizasVentas['fecha_pago']);
        $gastosPorEmision = collect($this->polizasGastos['fecha_emision']);
        $gastosPorPago    = collect($this->polizasGastos['fecha_pago']);

        $datos = [];
        foreach ($cuentasBalanza as $cuentaBalanza) {
            $linea = [
                'id'            => $cuentaBalanza->id,
                'numero_cuenta' => $cuentaBalanza->numero_cuenta,
                'descripcion'   => $cuentaBalanza->descripcion,
                'cargo'         => 0,
                'abono'         => 0,
                'saldo_inicial' => 0,
                'saldo_final'   => 0,
            ];

            $linea['cargo'] += $ventasPorEmision->where('numero_cuenta', $cuentaBalanza->numero_cuenta)->sum('cargo');
            $linea['abono'] += $ventasPorEmision->where('numero_cuenta', $cuentaBalanza->numero_cuenta)->sum('abono');
            $linea['cargo'] += $ventasPorPago->where('numero_cuenta', $cuentaBalanza->numero_cuenta)->sum('cargo');
            $linea['abono'] += $ventasPorPago->where('numero_cuenta', $cuentaBalanza->numero_cuenta)->sum('abono');

            $linea['cargo'] += $gastosPorEmision->where('numero_cuenta', $cuentaBalanza->numero_cuenta)->sum('cargo');
            $linea['abono'] += $gastosPorEmision->where('numero_cuenta', $cuentaBalanza->numero_cuenta)->sum('abono');
            $linea['cargo'] += $gastosPorPago->where('numero_cuenta', $cuentaBalanza->numero_cuenta)->sum('cargo');
            $linea['abono'] += $gastosPorPago->where('numero_cuenta', $cuentaBalanza->numero_cuenta)->sum('abono');

            $cuentaBalanzaCliente = $this->cliente->balanzasComprobacion()
                ->where('balanza_comprobacion_id', $cuentaBalanza->id)
                ->whereMonth('fecha', $this->fechaInicio->format('m'))
                ->whereYear('fecha', $this->fechaInicio->year)
                ->first();

            if ($cuentaBalanzaCliente) {
                $linea['saldo_inicial'] = $cuentaBalanzaCliente->saldo_inicial;
            }

            $linea['saldo_final']   = $linea['saldo_inicial'] + $linea['cargo'] - $linea['abono'];
            $datos[] = $linea;
        }

        return $datos;
    }

}
