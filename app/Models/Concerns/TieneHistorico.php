<?php

namespace App\Models\Concerns;

use App\Models\Historico;

trait TieneHistorico
{
    public function historico()
    {
        return $this->morphMany(Historico::class, 'relacionable');
    }
}
