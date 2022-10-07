<?php

namespace App\Contafacil\Facturas\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Factura;
use App\Models\FacturaCliente;

/* TODO: Remover esta clase e importaciones */
class FacturaCuentasManualesViewModel extends ViewModel
{
    /** @var Factura $factura */
    public $facturaCliente;

    public function __construct(FacturaCliente $facturaCliente)
    {
        $this->facturaCliente = $facturaCliente;
    }

    public function numerosCuentas()
    {
        return $this->facturaCliente->numerosCuentas->map(function ($numeroCuenta) {
            $cuenta = $numeroCuenta->toArray();
            $cuenta['cargo'] = 0;
            $cuenta['abono'] = 0;
            $cuenta['monto'] = $numeroCuenta->relacion_numero_cuenta->monto;

            $cuenta[$numeroCuenta->columna_calculo] = $numeroCuenta->relacion_numero_cuenta->monto;

            return $cuenta;
        })->toArray();
    }
}
