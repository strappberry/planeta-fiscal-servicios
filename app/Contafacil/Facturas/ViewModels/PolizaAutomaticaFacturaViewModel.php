<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Acciones\NumerosCuentas\ResolverFormulaNumeroCuentaFactura;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\FacturaCliente;
use App\Models\FacturaClienteNumeroCuenta;
use App\Models\NumeroCuenta;

class PolizaAutomaticaFacturaViewModel extends ViewModel
{
    public function __construct(
        private FacturaCliente $facturaCliente,
    ) {
    }

    public function fechaEmision(): array
    {
        $resultado = collect();
        /** @var $numerosCuenta NumeroCuenta[] */
        $numerosCuenta = NumeroCuenta::query()
            ->where('tipo_cuenta', $this->resolverTipoPoliza())
            ->where('subtipo', NumeroCuenta::SUBTIPO_FECHA_EMISION)
            ->orderBy('numero_cuenta', 'asc')
            ->orderBy('automatico', 'asc')
            ->get();

        foreach ($numerosCuenta as $numeroCuenta) {
            $lineaNumeroCuenta = [
                'id'              => $numeroCuenta->id,
                'numero_cuenta'   => $numeroCuenta->numero_cuenta,
                'descripcion'     => $numeroCuenta->descripcion,
                'tipo_cuenta'     => $numeroCuenta->tipo_cuenta,
                'subtipo'         => $numeroCuenta->subtipo,
                'automatico'      => $numeroCuenta->automatico,
                'columna_calculo' => $numeroCuenta->columna_calculo,
                'monto'           => 0,
                'cargo'           => 0,
                'abono'           => 0,
            ];

            $numeroCuentaFacturaCliente = FacturaClienteNumeroCuenta::query()
                ->where('factura_cliente_id', $this->facturaCliente->id)
                ->where('numero_cuenta_id', $numeroCuenta->id)
                ->first();

            if ($numeroCuentaFacturaCliente) {
                $lineaNumeroCuenta[$numeroCuenta->columna_calculo] = $numeroCuentaFacturaCliente->monto;
            } else if ($numeroCuenta->automatico) {
                $lineaNumeroCuenta[$numeroCuenta->columna_calculo] = ResolverFormulaNumeroCuentaFactura::ejecutar(
                    $numeroCuenta,
                    $this->facturaCliente
                );
            } else {
                continue;
            }

            $lineaNumeroCuenta['monto'] = $lineaNumeroCuenta[$numeroCuenta->columna_calculo];
            $resultado->push($lineaNumeroCuenta);
        }

        return $resultado->toArray();
    }

    public function fechaPago(): array
    {
        $resultado = collect();
        /** @var $numerosCuenta NumeroCuenta[] */
        $numerosCuenta = NumeroCuenta::query()
            ->where('tipo_cuenta', $this->resolverTipoPoliza())
            ->where('subtipo', NumeroCuenta::SUBTIPO_FECHA_PAGO)
            ->orderBy('numero_cuenta', 'asc')
            ->orderBy('automatico', 'asc')
            ->get();

        foreach ($numerosCuenta as $numeroCuenta) {
            $lineaNumeroCuenta = [
                'id'              => $numeroCuenta->id,
                'numero_cuenta'   => $numeroCuenta->numero_cuenta,
                'descripcion'     => $numeroCuenta->descripcion,
                'tipo_cuenta'     => $numeroCuenta->tipo_cuenta,
                'subtipo'         => $numeroCuenta->subtipo,
                'automatico'      => $numeroCuenta->automatico,
                'columna_calculo' => $numeroCuenta->columna_calculo,
                'monto'           => 0,
                'cargo'           => 0,
                'abono'           => 0,
            ];

            $numeroCuentaFacturaCliente = FacturaClienteNumeroCuenta::query()
                ->where('factura_cliente_id', $this->facturaCliente->id)
                ->where('numero_cuenta_id', $numeroCuenta->id)
                ->first();

            if ($numeroCuentaFacturaCliente) {
                $lineaNumeroCuenta[$numeroCuenta->columna_calculo] = $numeroCuentaFacturaCliente->monto;
            } else if ($numeroCuenta->automatico) {
                $lineaNumeroCuenta[$numeroCuenta->columna_calculo] = ResolverFormulaNumeroCuentaFactura::ejecutar(
                    $numeroCuenta,
                    $this->facturaCliente
                );
            } else {
                continue;
            }

            $lineaNumeroCuenta['monto'] = $lineaNumeroCuenta[$numeroCuenta->columna_calculo];
            $resultado->push($lineaNumeroCuenta);
        }

        return $resultado->toArray();
    }

    private function resolverTipoPoliza()
    {
        $tiposPoliza = [
            FacturaCliente::TIPO_VENTA => NumeroCuenta::TIPO_POLIZA_VENTAS,
            FacturaCliente::TIPO_GASTO => NumeroCuenta::TIPO_POLIZA_GASTOS,
        ];

        return $tiposPoliza[$this->facturaCliente->tipo_factura];
    }
}
