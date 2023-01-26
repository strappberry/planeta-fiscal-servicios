<?php

namespace App\Models\QueryBuilders;

use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DeterminacionImpuestosQueryBuilder extends Builder
{
    /**
     * Se buscara el ultimo mes con coeficiente de utilidad en el año de la fecha
     *
     * @param Cliente $cliente
     * @param Carbon $fecha
     * @return self
     */
    public function buscarUltimoMesConCoeficienteUtilidad(Cliente $cliente, Carbon $fecha): self
    {
        return $this->where('cliente_id', $cliente->id)
            ->whereYear('mes_trabajo', $fecha->year)
            ->whereMonth('mes_trabajo', '<=', $fecha->month)
            ->whereNotNull('coeficiente_utilidad')
            ->where('coeficiente_utilidad', '>', 0)
            ->orderBy('mes_trabajo', 'desc');
    }
}
