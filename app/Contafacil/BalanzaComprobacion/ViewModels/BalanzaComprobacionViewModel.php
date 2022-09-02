<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Acciones\NumerosCuentas\ResolverFormulaNumeroCuenta;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;
use Carbon\Carbon;

class BalanzaComprobacionViewModel extends ViewModel
{
    private $clienteId;
    private $fechaInicio;
    private $fechaFin;

    public function __construct(Carbon $fechaInicio, Carbon $fechaFin, $clienteId)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
        $this->clienteId   = $clienteId;
    }

    public function cuentasAutomaticas(): array
    {
        /** @var NumeroCuenta[] $numerosCuenta */
        $numerosCuenta = NumeroCuenta::query()
            ->where('tipo_cuenta', NumeroCuenta::TIPO_GASTO)
            ->where('automatico', true)
            ->get();

        $facturasCliente = FacturaCliente::query()
            ->whereBetween('fecha_emision', [
                $this->fechaInicio,
                $this->fechaFin,
            ])
            ->where('cliente_id', $this->clienteId)
            ->where('considerado', true)
            ->get();

        $cuentas = [];

        foreach ($numerosCuenta as $numeroCuenta) {
            $datos = [
                'id'            => $numeroCuenta->id,
                'numero_cuenta' => $numeroCuenta->numero_cuenta,
                'descripcion'   => $numeroCuenta->descripcion,
                'tipo_cuenta'   => $numeroCuenta->tipo_cuenta,
                'subtipo'       => $numeroCuenta->subtipo,
            ];

            $datos['montos'] = ResolverFormulaNumeroCuenta::ejecutar($numeroCuenta, $facturasCliente);
            $cuentas[] = $datos;
        }

        return $cuentas;
    }

    public function cuentasManuales()
    {
        $cuentas = NumeroCuenta::query()
            ->where('automatico', false)
            ->get();

        $listado = collect();

        foreach ($cuentas as $cuenta) {
            $datos = [
                'id'            => $cuenta->id,
                'numero_cuenta' => $cuenta->numero_cuenta,
                'descripcion'   => $cuenta->descripcion,
                'cargo'         => 0,
                'abono'         => 0,
            ];

            $facturasCliente = FacturaCliente::query()
                ->whereBetween('fecha_emision', [
                    $this->fechaInicio,
                    $this->fechaFin,
                ])
                ->where('numero_cuenta_id', $cuenta->id)
                ->where('cliente_id', $this->clienteId)
                ->where('considerado', true)
                ->get();

            foreach($facturasCliente as $facturaCliente) {
                $datos['cargo'] += $facturaCliente->factura->total;
            }

            $listado->push($datos);
        }

        return $listado->toArray();
    }

}
