<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FacturaClienteNumeroCuenta extends Pivot
{
    protected $casts = [
        'monto' => 'float',
    ];

    public function facturaCliente()
    {
        return $this->belongsTo(FacturaCliente::class);
    }

    public function numeroCuenta()
    {
        return $this->belongsTo(NumeroCuenta::class);
    }
}
