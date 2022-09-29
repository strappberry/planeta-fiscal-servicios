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
        // $cuentasBalanza = BalanzaComprobacion::all();
        $cliente = $this->cliente;
        $fechaInicio = $this->fechaInicio;

        $cuentasMayores = BalanzaComprobacion::where('tipo', BalanzaComprobacion::TIPO_MAYOR)->get();

        $resultado = collect();

        foreach ($cuentasMayores as $cuentaMayor) {
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

            $cuentaBalanzaMayorCliente = $cuentaMayor->cuentasClientes()
                ->where('fecha', $fechaInicio->format('Y-m-d'))
                ->where('cliente_id', $cliente->id)
                ->first();
            if ($cuentaBalanzaMayorCliente) {
                $datosCuentaMayor['saldo_inicial'] = $cuentaBalanzaMayorCliente->saldo_inicial;
                $datosCuentaMayor['cargo']         = $cuentaBalanzaMayorCliente->cargo;
                $datosCuentaMayor['abono']         = $cuentaBalanzaMayorCliente->abono;
                $datosCuentaMayor['saldo_final']   = $cuentaBalanzaMayorCliente->saldo_final;
            }

            $cuentasAuxiliares = $cuentaMayor->auxiliares;
            $cuentasAuxiliares = $cuentasAuxiliares->map(function($cuentaAuxiliar) use($cliente, $fechaInicio) {
                $datosCuenta = [
                    'id'            => $cuentaAuxiliar->id,
                    'numero_cuenta' => $cuentaAuxiliar->numero_cuenta,
                    'descripcion'   => $cuentaAuxiliar->descripcion,
                    'tipo'          => $cuentaAuxiliar->tipo,
                    'cargo'         => 0,
                    'abono'         => 0,
                    'saldo_inicial' => 0,
                    'saldo_final'   => 0,
                ];

                $cuentaBalanzaCliente = $cuentaAuxiliar->cuentasClientes()
                    ->where('fecha', $fechaInicio->format('Y-m-d'))
                    ->where('cliente_id', $cliente->id)
                    ->first();

                if ($cuentaBalanzaCliente) {
                    $datosCuenta['saldo_inicial'] = $cuentaBalanzaCliente->saldo_inicial;
                    $datosCuenta['cargo']         = $cuentaBalanzaCliente->cargo;
                    $datosCuenta['abono']         = $cuentaBalanzaCliente->abono;
                    $datosCuenta['saldo_final']   = $cuentaBalanzaCliente->saldo_final;
                }

                return $datosCuenta;
            });

            $resultado->push($datosCuentaMayor);
            $resultado = $resultado->merge($cuentasAuxiliares);
        }

        return $resultado->toArray();
    }
}
