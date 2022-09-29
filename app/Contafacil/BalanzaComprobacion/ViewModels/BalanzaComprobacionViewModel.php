<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Acciones\BalanzaComprobacion\ResolverFormulaBalanzaComprobacion;
use App\Acciones\NumerosCuentas\ResolverFormulaNumeroCuenta;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Contafacil\Polizas\ViewModels\PolizasAutomaticasVentasYGastosViewModel;
use App\Contafacil\Polizas\ViewModels\PolizasManualesVentasYGastosViewModel;
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

    /** @var array $polizasVentasAutomaticas*/
    private $polizasVentasAutomaticas;
    /** @var array $polizasGastosAutomaticas*/
    private $polizasGastosAutomaticas;
    /** @var array $polizasVentasManuales*/
    private $polizasVentasManuales;
    /** @var array $polizasGastosManuales*/
    private $polizasGastosManuales;

    public function __construct(
        Carbon $fechaInicio,
        Carbon $fechaFin,
        Cliente $cliente,
        $polizasVentasAutomaticas = null,
        $polizasGastosAutomaticas = null,
        $polizasVentasManuales = null,
        $polizasGastosManuales = null
    )
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
        $this->cliente   = $cliente;

        if (!is_array($polizasVentasAutomaticas)) {
            $this->polizasVentasAutomaticas = (new PolizasAutomaticasVentasYGastosViewModel(
                NumeroCuenta::TIPO_POLIZA_VENTAS,
                $fechaInicio,
                $fechaFin,
                $cliente->planetafiscal_id
            ))->toArray();
        } else {
            $this->polizasVentasAutomaticas = $polizasVentasAutomaticas;
        }

        if (!is_array($polizasGastosAutomaticas)) {
            $this->polizasGastosAutomaticas = (new PolizasAutomaticasVentasYGastosViewModel(
                NumeroCuenta::TIPO_POLIZA_GASTOS,
                $fechaInicio,
                $fechaFin,
                $cliente->planetafiscal_id
            ))->toArray();
        } else {
            $this->polizasGastosAutomaticas = $polizasGastosAutomaticas;
        }

        if (!is_array($polizasVentasManuales)) {
            $this->polizasVentasManuales = (new PolizasManualesVentasYGastosViewModel(
                NumeroCuenta::TIPO_POLIZA_VENTAS,
                $fechaInicio,
                $fechaFin,
                $cliente->planetafiscal_id
            ))->toArray();
        } else {
            $this->polizasVentasManuales = $polizasVentasManuales;
        }

        if (!is_array($polizasGastosManuales)) {
            $this->polizasGastosManuales = (new PolizasManualesVentasYGastosViewModel(
                NumeroCuenta::TIPO_POLIZA_GASTOS,
                $fechaInicio,
                $fechaFin,
                $cliente->planetafiscal_id
            ))->toArray();
        } else {
            $this->polizasGastosManuales = $polizasGastosManuales;
        }
    }

    public function balanzaComprobacion(): array
    {
        $ventasPorEmision = collect(array_merge(
            $this->polizasVentasAutomaticas['fecha_emision'],
            $this->polizasVentasManuales['fecha_emision']
        ));
        $ventasPorPago    = collect(array_merge(
            $this->polizasVentasAutomaticas['fecha_pago'],
            $this->polizasVentasManuales['fecha_pago']
        ));

        $gastosPorEmision = collect(array_merge(
            $this->polizasGastosAutomaticas['fecha_emision'],
            $this->polizasGastosManuales['fecha_emision']
        ));
        $gastosPorPago    = collect(array_merge(
            $this->polizasGastosAutomaticas['fecha_pago'],
            $this->polizasGastosManuales['fecha_pago']
        ));

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
                    $cuentaBalanzaCliente['saldo_inicial'] = $cuentaBalanzaCliente->saldo_inicial;
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
