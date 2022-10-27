<?php

namespace App\Models\QueryBuilders;

use App\Models\ConceptoSat;
use Illuminate\Database\Eloquent\Builder;

class ConceptosSatQueryBuilder extends Builder
{
    public function paraVentas()
    {
        return $this->where('tipo_factura', ConceptoSat::TIPO_VENTA);
    }

    public function paraGastos()
    {
        return $this->where('tipo_factura', ConceptoSat::TIPO_GASTO);
    }
}
