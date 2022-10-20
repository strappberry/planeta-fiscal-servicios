<?php

namespace App\Contafacil\TablaTarifa\ViewModels;

use App\Contafacil\Compartido\ViewModels\ViewModel;
use Illuminate\Support\Collection;

class ConfiguracionTablasViewModel extends ViewModel
{
    public $configuracionesTabla;

    public function __construct(Collection $configuracionesTabla)
    {
        $this->configuracionesTabla = $configuracionesTabla;
    }

    /**
     * Dada una collección de configuración de tablas, se agregá la información
     * de los años en los que se pueden guardar datos.
     */
    public function configuracionTablas(): array
    {
        return $this->configuracionesTabla->map(function($configuracion) {
            $anios = [];
            $fecha = now()->startOfYear();

            do {
                $anios[] = $fecha->year;
                $fecha = $fecha->subYear();
            } while($fecha->year >= $configuracion['desde']);

            $configuracion['anios'] = $anios;
            return $configuracion;
        })->toArray();
    }
}
