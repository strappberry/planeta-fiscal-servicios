<?php

namespace App\Models\QueryBuilders;

use App\Models\CampoEditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class CampoEditableQueryBuilder extends Builder
{
    public function campo(string $campo): Builder
    {
        return $this->where('campo', $campo);
    }

    public function impuestosFederales(): Builder
    {
        return $this->modulo(CampoEditable::MODULO_IMPUESTOS_FEDERALES);
    }

    public function mesTrabajo(Carbon $mesTrabajo): Builder
    {
        return $this->whereDate('mes_trabajo', $mesTrabajo);
    }

    public function modulo(string $modulo): Builder
    {
        return $this->where('modulo', $modulo);
    }
}
