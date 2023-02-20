<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SoloPrimeraPaginaImport implements WithMultipleSheets
{
    public function __construct(
        private string $claseImportadora
    ) {
    }

    public function sheets(): array
    {
        return [
            0 => new $this->claseImportadora,
        ];
    }
}
