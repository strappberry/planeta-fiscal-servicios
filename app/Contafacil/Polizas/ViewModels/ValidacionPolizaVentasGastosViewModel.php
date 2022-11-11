<?php

namespace App\Contafacil\Polizas\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;

class ValidacionPolizaVentasGastosViewModel extends ViewModel
{
    private $montosFechaEmision = [];
    private $montosFechaPago = [];
    private $validaciones = [
        'emision'    => false,
        'pago'       => false,
        'validacion' => false,
    ];

    public function __construct(
        private PolizasAutomaticasVentasYGastosViewModel $modelo
    ) {
        $this->generarValidacion();
    }

    private function generarValidacion()
    {
        $modelo = $this->modelo->toArray();
        $cuentasFechaEmision = collect($modelo['fecha_emision']);
        $cuentaFechaPago = collect($modelo['fecha_pago']);

        $this->montosFechaEmision = [
            'cargo' => $this->redondearMonto($cuentasFechaEmision->sum('cargo')),
            'abono' => $this->redondearMonto($cuentasFechaEmision->sum('abono')),
        ];
        $this->montosFechaPago = [
            'cargo' => $this->redondearMonto($cuentaFechaPago->sum('cargo')),
            'abono' => $this->redondearMonto($cuentaFechaPago->sum('abono')),
        ];

        $this->validaciones['emision']    = $this->montosFechaEmision['cargo'] === $this->montosFechaEmision['abono'];
        $this->validaciones['pago']       = $this->montosFechaPago['cargo'] === $this->montosFechaPago['abono'];
        $this->validaciones['validacion'] = $this->validaciones['emision'] && $this->validaciones['pago'];
    }

    public function totalesFechaEmision(): array
    {
        return $this->montosFechaEmision;
    }

    public function totalesFechaPago(): array
    {
        return $this->montosFechaPago;
    }

    public function validaciones(): array
    {
        return $this->validaciones;
    }

    private function redondearMonto($monto)
    {
        return round($monto, 2);
    }
}
