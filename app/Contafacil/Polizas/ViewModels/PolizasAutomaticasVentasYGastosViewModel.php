<?php

namespace App\Contafacil\Polizas\ViewModels;

use App\Acciones\NumerosCuentas\ResolverFormulaNumeroCuenta;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;
use Carbon\Carbon;

class PolizasAutomaticasVentasYGastosViewModel extends ViewModel
{
    private $fechaInicio;
    private $fechaFin;
    private $cliente;
    private $facturasPorEmision = [];
    private $facturasPorPago = [];

    public function __construct(Carbon $fechaInicio, Carbon $fechaFin, string $cliente)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
        $this->cliente     = $cliente;

        $this->facturasPorEmision = FacturaCliente::query()
            ->whereBetween('fecha_emision', [
                $this->fechaInicio,
                $this->fechaFin,
            ])
            ->where('cliente_id', $this->cliente)
            ->where('considerado', true)
            ->orderBy('fecha_emision')
            ->get();

        $this->facturasPorPago = FacturaCliente::query()
            ->whereBetween('fecha_pago', [
                $this->fechaInicio,
                $this->fechaFin,
            ])
            ->where('cliente_id', $this->cliente)
            ->where('considerado', true)
            ->orderBy('fecha_pago')
            ->get();
    }

    public function fechaEmision(): array
    {
        /** @var NumeroCuenta[] $numerosCuenta */
        $numerosCuenta = NumeroCuenta::query()
            ->where('tipo_cuenta', NumeroCuenta::TIPO_POLIZA)
            ->where('subtipo' , NumeroCuenta::SUBTIPO_FECHA_EMISION)
            ->where('automatico', true)
            ->get();

        $resultado = [];

        foreach ($numerosCuenta as $numeroCuenta) {
            $poliza = [
                'id'            => $numeroCuenta->id,
                'numero_cuenta' => $numeroCuenta->numero_cuenta,
                'descripcion'   => $numeroCuenta->descripcion,
                'tipo_cuenta'   => $numeroCuenta->tipo_cuenta,
                'subtipo'       => $numeroCuenta->subtipo,
            ];

            $poliza['montos'] = ResolverFormulaNumeroCuenta::ejecutar($numeroCuenta, $this->facturasPorEmision);

            $resultado[] = $poliza;
        }

        return $resultado;
    }

    public function fechaPago(): array
    {
        /** @var NumeroCuenta[] $numerosCuenta */
        $numerosCuenta = NumeroCuenta::query()
            ->where('tipo_cuenta', NumeroCuenta::TIPO_POLIZA)
            ->where('subtipo' , NumeroCuenta::SUBTIPO_FECHA_PAGO)
            ->where('automatico', true)
            ->get();

        $resultado = [];

        foreach ($numerosCuenta as $numeroCuenta) {
            $poliza = [
                'id'            => $numeroCuenta->id,
                'numero_cuenta' => $numeroCuenta->numero_cuenta,
                'descripcion'   => $numeroCuenta->descripcion,
                'tipo_cuenta'   => $numeroCuenta->tipo_cuenta,
                'subtipo'       => $numeroCuenta->subtipo,
            ];

            $poliza['montos'] = ResolverFormulaNumeroCuenta::ejecutar($numeroCuenta, $this->facturasPorPago);

            $resultado[] = $poliza;
        }

        return $resultado;
    }
}
