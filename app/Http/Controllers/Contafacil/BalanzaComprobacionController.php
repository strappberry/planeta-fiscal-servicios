<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Contafacil\BalanzaComprobacion\ViewModels\BalanzaComprobacionViewModel;
use App\Contafacil\BalanzaComprobacion\ViewModels\BalanzaImpuestsoViewModel;
use App\Contafacil\Polizas\ViewModels\PolizasAutomaticasVentasYGastosViewModel;
use App\Contafacil\Polizas\ViewModels\PolizasManualesVentasYGastosViewModel;
use App\Http\Controllers\Controller;
use App\Models\BalanzaComprobacionCliente;
use App\Models\NumeroCuenta;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class BalanzaComprobacionController extends Controller
{

    public function balanza(int $cliente, string $fecha)
    {
        $fechaInicio = Carbon::parse($fecha)->startOfMonth();
        $fechaFin    = Carbon::parse($fecha)->endOfMonth();

        $cliente = ResolverClientePlanetaFiscal::ejecutar($cliente);
        $balanzaModelo = new BalanzaComprobacionViewModel($fechaInicio, $fechaFin, $cliente);

        return response()->json(
            $balanzaModelo->toArray()
        );
    }

    public function actualizarSaldosBalanza(Request $request)
    {
        $this->validate($request, [
            'cliente' => 'required|integer',
            'fecha'   => 'required|date',
            'cuentas' => 'required|array',
        ]);

        $fecha = Carbon::parse($request->fecha)->startOfMonth();
        $cliente = ResolverClientePlanetaFiscal::ejecutar($request->cliente);

        foreach ($request->cuentas as $cuenta) {
            BalanzaComprobacionCliente::updateOrCreate(
                [
                    'balanza_comprobacion_id' => $cuenta['id'],
                    'cliente_id'              => $cliente->id,
                    'fecha'                   => $fecha,
                ],
                [
                    'saldo_inicial' => floatval($cuenta['saldo_inicial']),
                ]
            );
        }

        return response()->json([
            'message' => 'Saldos actualizados correctamente',
        ]);
    }

    public function impuestos(Request $request, int $cliente)
    {
        $this->validate($request, [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date',
            'rfc'          => 'required|string',
        ]);

        $viewModel = new BalanzaImpuestsoViewModel(
            $cliente,
            $request->rfc,
            Carbon::parse($request->fecha_inicio)->startOfMonth(),
            Carbon::parse($request->fecha_fin)->endOfMonth()
        );

        return response()->json([
            'impuestos' => $viewModel->toArray(),
        ]);
    }

    public function polizasAutomaticasGastosYVentas(Request $request, string $cliente, string $fecha)
    {
        $fechaInicio = Carbon::parse($fecha)->startOfMonth();
        $fechaFin    = Carbon::parse($fecha)->endOfMonth();
        $clienteId   = $cliente;

        $polizasVentas = new PolizasAutomaticasVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_VENTAS,
            $fechaInicio,
            $fechaFin,
            $clienteId
        );
        $polizasGastos = new PolizasAutomaticasVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_GASTOS,
            $fechaInicio,
            $fechaFin,
            $clienteId
        );

        $polizasVentasManuales = new PolizasManualesVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_VENTAS,
            $fechaInicio,
            $fechaFin,
            $clienteId
        );
        $polizasGastosManuales = new PolizasManualesVentasYGastosViewModel(
            NumeroCuenta::TIPO_POLIZA_GASTOS,
            $fechaInicio,
            $fechaFin,
            $clienteId
        );

        return response()->json([
            'poliza_automatica_ventas' => $polizasVentas->toArray(),
            'poliza_automatica_gastos' => $polizasGastos->toArray(),
            'poliza_manual_ventas'     => $polizasVentasManuales->toArray(),
            'poliza_manual_gastos'     => $polizasGastosManuales->toArray(),
        ]);
    }

    public function polizasAutomaticasGastosYVentasAnual(string $cliente, string $fecha)
    {
        Carbon::setLocale('es');

        $clienteId   = $cliente;
        $cliente = ResolverClientePlanetaFiscal::ejecutar($cliente);
        $periodo = CarbonInterval::month(1)
            ->toPeriod(
                Carbon::parse($fecha)->startOfYear(),
                Carbon::parse($fecha)->endOfYear()
            );

        $polizasAnuales = [];

        foreach ($periodo as $mes) {
            $fechaInicio = Carbon::parse($mes)->startOfMonth();
            $fechaFin    = Carbon::parse($mes)->endOfMonth();

            $polizasVentasAutomaticas = (new PolizasAutomaticasVentasYGastosViewModel(
                NumeroCuenta::TIPO_POLIZA_VENTAS,
                $fechaInicio,
                $fechaFin,
                $clienteId
            ))->toArray();
            $polizasGastosAutomaticas = (new PolizasAutomaticasVentasYGastosViewModel(
                NumeroCuenta::TIPO_POLIZA_GASTOS,
                $fechaInicio,
                $fechaFin,
                $clienteId
            ))->toArray();

            $polizasVentasManuales = (new PolizasManualesVentasYGastosViewModel(
                NumeroCuenta::TIPO_POLIZA_VENTAS,
                $fechaInicio,
                $fechaFin,
                $clienteId
            ));
            $polizasGastosManuales = (new PolizasManualesVentasYGastosViewModel(
                NumeroCuenta::TIPO_POLIZA_GASTOS,
                $fechaInicio,
                $fechaFin,
                $clienteId
            ))->toArray();

            $balanza = new BalanzaComprobacionViewModel(
                $fechaInicio,
                $fechaFin,
                $cliente,
                $polizasVentasAutomaticas,
                $polizasGastosAutomaticas,
                $polizasVentasManuales,
                $polizasGastosManuales
            );

            array_push($polizasAnuales, [
                'mes'   => $mes->monthName,
                'anio'  => $mes->year,
                'desde' => $fechaInicio->format('Y-m-d'),
                'hasta' => $fechaFin->format('Y-m-d'),
                'poliza_automatica_ventas' => $polizasVentasAutomaticas,
                'poliza_automatica_gastos' => $polizasGastosAutomaticas,
                'poliza_manual_ventas'     => $polizasVentasManuales,
                'poliza_manual_gastos'     => $polizasGastosManuales,
                'balanza_comprobacion' => $balanza->toArray(),
            ]);
        }

        return response()->json([
            'polizas_anuales' => $polizasAnuales,
        ]);
    }

}
