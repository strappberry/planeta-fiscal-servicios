<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Acciones\BalanzaComprobacion\ResolverFormulaBalanzaComprobacion;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Contafacil\Polizas\ViewModels\PolizasAutomaticasVentasYGastosViewModel;
use App\Models\BalanzaComprobacion;
use App\Models\Cliente;
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

    /** @var array $polizasVentasAutomaticas*/
    private $polizasVentasAutomaticas;
    /** @var array $polizasGastosAutomaticas*/
    private $polizasGastosAutomaticas;

    public function __construct(
        Carbon $fechaInicio,
        Carbon $fechaFin,
        Cliente $cliente,
    )
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
        $this->cliente   = $cliente;

        $this->polizasVentasAutomaticas = (new PolizasAutomaticasVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_VENTAS,
            $fechaInicio,
            $fechaFin,
            $cliente
        ))->toArray();

        $this->polizasGastosAutomaticas = (new PolizasAutomaticasVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_GASTOS,
            $fechaInicio,
            $fechaFin,
            $cliente
        ))->toArray();
    }

    public function balanzaComprobacion(): array
    {
        $ventasPorEmision = collect($this->polizasVentasAutomaticas['fecha_emision']);
        $ventasPorPago    = collect($this->polizasVentasAutomaticas['fecha_pago']);

        $gastosPorEmision = collect($this->polizasGastosAutomaticas['fecha_emision']);
        $gastosPorPago    = collect($this->polizasGastosAutomaticas['fecha_pago']);

        $resultadoBalanza = collect();
        $cuentasMayores = BalanzaComprobacion::where('tipo', BalanzaComprobacion::TIPO_MAYOR)->get();

        foreach($cuentasMayores as $cuentaMayor) {
            $datosCuentaMayor = [
                'id'            => $cuentaMayor->id,
                'numero_cuenta' => $cuentaMayor->numero_cuenta,
                'descripcion'   => $cuentaMayor->descripcion,
                'tipo'          => $cuentaMayor->tipo,
                'cargo'         => 0,
                'abono'         => 0,
                'saldo_inicial' => 0,
                'saldo_final'   => 0,
            ];

            $resultadosAuxiliares = collect();
            $cuentasAuxiliares = $cuentaMayor->auxiliares;

            foreach ($cuentasAuxiliares as $cuentaAuxiliar) {
                $datosCuentaAuxiliar = [
                    'id'            => $cuentaAuxiliar->id,
                    'numero_cuenta' => $cuentaAuxiliar->numero_cuenta,
                    'descripcion'   => $cuentaAuxiliar->descripcion,
                    'tipo'          => $cuentaAuxiliar->tipo,
                    'cargo'         => 0,
                    'abono'         => 0,
                    'saldo_inicial' => 0,
                    'saldo_final'   => 0,
                ];

                $datosCuentaAuxiliar['cargo'] += $ventasPorEmision
                    ->where('numero_cuenta', $cuentaAuxiliar->numero_cuenta)->sum('cargo');
                $datosCuentaAuxiliar['abono'] += $ventasPorEmision
                    ->where('numero_cuenta', $cuentaAuxiliar->numero_cuenta)->sum('abono');
                $datosCuentaAuxiliar['cargo'] += $ventasPorPago
                    ->where('numero_cuenta', $cuentaAuxiliar->numero_cuenta)->sum('cargo');
                $datosCuentaAuxiliar['abono'] += $ventasPorPago
                    ->where('numero_cuenta', $cuentaAuxiliar->numero_cuenta)->sum('abono');

                $datosCuentaAuxiliar['cargo'] += $gastosPorEmision
                    ->where('numero_cuenta', $cuentaAuxiliar->numero_cuenta)->sum('cargo');
                $datosCuentaAuxiliar['abono'] += $gastosPorEmision
                    ->where('numero_cuenta', $cuentaAuxiliar->numero_cuenta)->sum('abono');
                $datosCuentaAuxiliar['cargo'] += $gastosPorPago
                    ->where('numero_cuenta', $cuentaAuxiliar->numero_cuenta)->sum('cargo');
                $datosCuentaAuxiliar['abono'] += $gastosPorPago
                    ->where('numero_cuenta', $cuentaAuxiliar->numero_cuenta)->sum('abono');

                $cuentaBalanzaCliente = $this->cliente->balanzasComprobacion()
                    ->where('balanza_comprobacion_id', $cuentaAuxiliar->id)
                    ->whereMonth('fecha', $this->fechaInicio->format('m'))
                    ->whereYear('fecha', $this->fechaInicio->year)
                    ->first();

                if ($cuentaBalanzaCliente) {
                    $datosCuentaAuxiliar['saldo_inicial'] = $cuentaBalanzaCliente->saldo_inicial;
                }
                $datosCuentaAuxiliar['saldo_final'] = ResolverFormulaBalanzaComprobacion::ejecutar(
                    $cuentaAuxiliar,
                    (float) $datosCuentaAuxiliar['saldo_inicial'],
                    (float) $datosCuentaAuxiliar['cargo'],
                    (float) $datosCuentaAuxiliar['abono']
                );

                $resultadosAuxiliares->push($datosCuentaAuxiliar);
            }

            $datosCuentaMayor['saldo_inicial'] = $resultadosAuxiliares->sum('saldo_inicial');
            $datosCuentaMayor['cargo'] = $resultadosAuxiliares->sum('cargo');
            $datosCuentaMayor['abono'] = $resultadosAuxiliares->sum('abono');
            $datosCuentaMayor['saldo_final'] = $resultadosAuxiliares->sum('saldo_final');

            $resultadoBalanza->push($datosCuentaMayor);
            $resultadoBalanza = $resultadoBalanza->merge(
                $resultadosAuxiliares->toArray()
            );
        }

        return $resultadoBalanza->toArray();
    }

}
