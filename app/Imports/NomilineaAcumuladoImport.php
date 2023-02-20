<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;

class NomilineaAcumuladoImport implements ToArray
{
    public function array(array $array)
    {
        return $array;
    }
}
