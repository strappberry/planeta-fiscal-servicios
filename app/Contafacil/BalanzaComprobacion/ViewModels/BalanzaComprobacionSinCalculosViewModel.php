<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\BalanzaComprobacion;
use App\Models\Cliente;
use Carbon\Carbon;

class BalanzaComprobacionSinCalculosViewModel extends ViewModel
{
    public function __construct(
        private Carbon $fechaInicio,
        private Cliente $cliente,
    ){
    }

    public function balanzaComprobacion()
    {
        $cuentasBalanza = BalanzaComprobacion::all();
        $cliente = $this->cliente;
        $fechaInicio = $this->fechaInicio;

        return $cuentasBalanza->map(function ($cuentaBalanza) use($cliente, $fechaInicio) {
            $datosCuenta = [
                'id'            => $cuentaBalanza->id,
                'numero_cuenta' => $cuentaBalanza->numero_cuenta,
                'descripcion'   => $cuentaBalanza->descripcion,
                'tipo'          => $cuentaBalanza->tipo,
                'cargo'         => 0,
                'abono'         => 0,
                'saldo_inicial' => 0,
                'saldo_final'   => 0,
            ];

            $cuentaBalanzaCliente = $cuentaBalanza->cuentasClientes()
                ->where('fecha', $fechaInicio->format('Y-m-d'))
                ->where('cliente_id', $cliente->id)
                ->first();

            if ($cuentaBalanzaCliente) {
                $datosCuenta['saldo_inicial'] = $cuentaBalanzaCliente->saldo_inicial;
                $datosCuenta['cargo']         = $cuentaBalanzaCliente->cargo;
                $datosCuenta['abono']         = $cuentaBalanzaCliente->abono;
                $datosCuenta['saldo_final'] = $cuentaBalanzaCliente->saldo_final;
            }

            return $datosCuenta;
        });
    }
}
