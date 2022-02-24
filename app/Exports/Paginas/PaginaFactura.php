<?php

namespace App\Exports\Paginas;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaginaFactura implements FromView, WithTitle
{
    public $encabezados = [];
    public $paginaReporte = [];

    public function __construct($encabezados, $paginaReporte)
    {
        $this->encabezados = $encabezados;
        $this->paginaReporte = $paginaReporte;
    }

    public function title(): string
    {
        return $this->paginaReporte['titulo'];
    }

    public function view(): View
    {
        return view('exports.paginas.pagina-factura', [
            'encabezados' => $this->encabezados,
            'paginaReporte' => $this->paginaReporte,
        ]);
    }

}
