<?php

namespace App\Contafacil\Compartido\Contratos;

interface DeterminacionImpuestosPorRegimen
{
    public function tipoRegimen(): int;
    public function datosDeterminacion(): array;
}
