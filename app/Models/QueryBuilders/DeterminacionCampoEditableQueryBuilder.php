<?php

namespace App\Models\QueryBuilders;

use App\Enums\DeterminacionImpuestosEnum;
use App\Enums\RegimenFiscal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DeterminacionCampoEditableQueryBuilder extends Builder
{
    public function buscarPorClave(string $clave): self
    {
        return $this->where('clave', $clave);
    }

    public function buscarPorMes(Carbon $fecha): self
    {
        return $this->where('mes_trabajo', $fecha);
    }

    public function buscarPorRegimen($regimen): self
    {
        return $this->where('regimen', $regimen);
    }

    public function buscarUltimoMesConValor(string $clave, Carbon $fecha, $regimen): self
    {
        return $this
            ->buscarPorClave($clave)
            ->buscarPorRegimen($regimen)
            ->whereYear('mes_trabajo', $fecha->year)
            ->whereMonth('mes_trabajo', '<=', $fecha->month)
            ->mayorACero()
            ->orderBy('mes_trabajo', 'desc')
            ;
    }

    public function buscarMesPrevioConValor(string $clave, Carbon $fecha, $regimen): self
    {
        return $this
            ->buscarPorClave($clave)
            ->buscarPorRegimen($regimen)
            ->whereYear('mes_trabajo', $fecha->year)
            ->whereMonth('mes_trabajo', '<', $fecha->month)
            ->mayorACero()
            ->orderBy('mes_trabajo', 'desc')
            ;
    }

    public function mayorACero(): self
    {
        return $this->where('valor', '>', 0);
    }

}
