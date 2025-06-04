<?php

namespace App\Exports\Paginas;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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
        for ($i = 1; $i <= 40; $i++) {
            $col = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
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
        $sheet->getStyle('A4:AN4')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('212770');
        // Color texto encabezados
        $sheet->getStyle('A4:AN4')
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');
    }

}