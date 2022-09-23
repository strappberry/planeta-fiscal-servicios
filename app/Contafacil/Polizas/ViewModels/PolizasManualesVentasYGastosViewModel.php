<?php

namespace App\Contafacil\Polizas\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;
use Carbon\Carbon;

class PolizasManualesVentasYGastosViewModel extends ViewModel
{
    private $fechaInicio;
    private $fechaFin;
    private $cliente;
    private $tipoPoliza;
    private $facturasPorEmision = [];
    private $facturasPorPago = [];
    private $numerosCuentasEmision;
    private $numerosCuentasPago;

    public function __construct(
        string $tipoPoliza,
        Carbon $fechaInicio,
        Carbon $fechaFin,
        string $cliente,
    ) {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
        $this->cliente     = $cliente;
        $this->tipoPoliza  = $tipoPoliza;

        $this->numerosCuentasEmision = NumeroCuenta::query()
            ->where('tipo_cuenta', $this->tipoPoliza)
            ->where('subtipo' , NumeroCuenta::SUBTIPO_FECHA_EMISION)
            ->where('automatico', false)
            ->get();

        $this->numerosCuentasPago = NumeroCuenta::query()
            ->where('tipo_cuenta', $this->tipoPoliza)
            ->where('subtipo' , NumeroCuenta::SUBTIPO_FECHA_PAGO)
            ->where('automatico', false)
            ->get();
    }

    public function fechaEmision(): array
    {
        return $this->numerosCuentasEmision->map(function ($numeroCuenta) {
            $poliza = [
                'id'            => $numeroCuenta->id,
                'numero_cuenta' => $numeroCuenta->numero_cuenta,
                'descripcion'   => $numeroCuenta->descripcion,
                'tipo_cuenta'   => $numeroCuenta->tipo_cuenta,
                'subtipo'       => $numeroCuenta->subtipo,
                'cargo'         => 0,
                'abono'         => 0,
            ];

            $facturasCliente = $numeroCuenta->facturasManuales()
                ->whereBetween('fecha_emision', [
                    $this->fechaInicio,
                    $this->fechaFin,
                ])
                ->where('cliente_id', $this->cliente)
                ->where('considerado', true)
                ->get();

            $poliza[$numeroCuenta->columna_calculo] = $facturasCliente->sum('relacion_numero_cuenta.monto');

            return $poliza;
        })->toArray();
    }

    public function fechaPago(): array
    {
        $resultado = [];

        return $resultado;
    }
}
