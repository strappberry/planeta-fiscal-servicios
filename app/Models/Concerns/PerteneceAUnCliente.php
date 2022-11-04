<?php

namespace App\Models\Concerns;

use App\Models\Cliente;

trait PerteneceAUnCliente
{
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
