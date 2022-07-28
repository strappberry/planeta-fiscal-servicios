<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;

class BalanzaComprobacionViewModel extends ViewModel
{
    private $clienteId;

    public function __construct($clienteId)
    {
        $this->clienteId = $clienteId;
    }

    public function cuentasManuales()
    {
        $cuentas = NumeroCuenta::all();
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
