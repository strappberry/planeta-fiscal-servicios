<?php

namespace App\Models\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class SaldoAFavorQueryBuilder extends Builder
{
    public function porOrigen(string $origen): Builder
    {
        return $this->where('origen', $origen);
    }

    public function porOrigenes(array $origenes): Builder
    {
        return $this->whereIn('origen', $origenes);
    }
}
