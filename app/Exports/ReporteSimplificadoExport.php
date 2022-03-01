<?php

namespace App\Exports;

use App\Exports\Paginas\PaginaReporteSimplificado;
use App\Reportes\ReporteSimplificado;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReporteSimplificadoExport implements WithMultipleSheets
{
    private $reporte;

    public function __construct(ReporteSimplificado $reporte)
    {
        $this->reporte = $reporte;
    }

    public function sheets(): array
    {
        $paginas = [];

        foreach ($this->reporte->paginas() as $pagina) {
            array_push(
                $paginas,
                new PaginaReporteSimplificado(
                    $this->reporte->informacionCliente(),
                    $pagina
                )
            );
        }

        return $paginas;
    }
}
