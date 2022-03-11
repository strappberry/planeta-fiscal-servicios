<?php

namespace App\Exports\Paginas;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaginaReporteSimplificado implements FromView, WithTitle, WithStyles
{
    private $info;
    private $pagina;

    public function __construct(array $info, array $pagina)
    {
        $this->info = $info;
        $this->pagina = $pagina;
    }

    public function title(): string
    {
        return $this->pagina['titulo'];
    }

    public function view(): View
    {
        return view(
            'exports.paginas.pagina-reporte-simplificado',
            [
                'info' => $this->info,
                'encabezados' => $this->pagina['encabezados'],
                'pagina' => $this->pagina,
            ]
        );
    }

    public function styles(Worksheet $sheet)
    {
        // Columnas con tamaño horizontal automatico
        foreach(range('A','Z') as $columna) {
            $sheet->getColumnDimension($columna)->setAutoSize(true);
        }

        // Color fondo informacion cliente
        $sheet->getStyle('A1:A2')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('212770');
        //Color texto informacion cliente
        $sheet->getStyle('A1:A2')
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');

        // Color fondo linea de encabezado
        $sheet->getStyle('A4:U4')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('212770');
        // Color texto encabezados
        $sheet->getStyle('A4:U4')
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');
    }
   
}
