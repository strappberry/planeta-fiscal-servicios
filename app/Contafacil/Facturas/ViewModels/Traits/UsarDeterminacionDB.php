<?php

namespace App\Contafacil\Facturas\ViewModels\Traits;

use App\Acciones\BalanzaComprobacion\ResolverDeterminacionImpuestosDB;

trait UsarDeterminacionDB
{
    private $determinacionImpuestoDB;

    private function cargarDeterminacionImpuestoDB()
    {
        $this->determinacionImpuestoDB = ResolverDeterminacionImpuestosDB::ejecutar(
            $this->cliente,
            $this->fecha
        );
    }

    public function camposEditables(): array
    {
        return $this->determinacionImpuestoDB ?
            $this->determinacionImpuestoDB->campos_editables : [];
    }

    private function camposEditablesPorRegimen(string $regimen)
    {
        $campos = $this->camposEditables();

        if (isset($campos[$regimen])) {
            return $campos[$regimen];
        }

        return [];
    }
}
