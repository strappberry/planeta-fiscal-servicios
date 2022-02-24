<?php

namespace App\Exports;

use App\Exports\Paginas\PaginaFactura;
use App\Reportes\Reporte;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FacturasExport implements WithMultipleSheets
{
    private $reporte;

    public function __construct(Reporte $reporte)
    {
        $this->reporte = $reporte;
    }

    public function sheets(): array
    {
        $paginasArchivo = [];

        $encabezados = $this->reporte->encabezados();
        $paginas = $this->reporte->paginas();

        foreach($paginas as $pagina) {
            array_push(
                $paginasArchivo,
                new PaginaFactura($encabezados, $pagina)
            );
        }

        return $paginasArchivo;
    }

}
