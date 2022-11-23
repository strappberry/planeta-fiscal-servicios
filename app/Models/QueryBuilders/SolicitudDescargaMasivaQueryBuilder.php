<?php

namespace App\Models\QueryBuilders;

use App\Enums\EstadoSolicitudDescargaMasiva;
use Illuminate\Database\Eloquent\Builder;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;

class SolicitudDescargaMasivaQueryBuilder extends Builder
{
    public function metadata(): self
    {
        return $this->where('tipo_solicitud', (string) RequestType::metadata());
    }

    public function pendientes(): self
    {
        return $this->where('estado_solicitud', EstadoSolicitudDescargaMasiva::EN_PROCESO);
    }
}
