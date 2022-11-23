<?php

namespace App\Acciones\PhpCfdi;

use App\Models\Cliente;
use App\Sat\Utilidades\InsertarFacturaMetadata;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\SatWsDescargaMasiva\PackageReader\MetadataPackageReader;

class ProcesarPaquetesMetadatos
{
    const CARPETA = '/facturas/paquetes/';

    public static function ejecutar(Cliente $cliente, array $paquetes): array
    {
        $uuids = [];
        foreach ($paquetes as $paquete) {
            try {
                $lector = MetadataPackageReader::createFromContents(
                    Storage::get(self::CARPETA . $paquete)
                );

                foreach($lector->metadata() as $uuid => $metadata) {
                    $uuids[] = $uuid;
                    InsertarFacturaMetadata::ejecutar($cliente, $metadata);
                }

                Storage::delete(self::CARPETA . $paquete);
            } catch (Exception $e) {
                Log::error("Error al procesar paquete {$paquete} del cliente {$cliente->rfc}. " . $e->getMessage());
            }
        }

        return $uuids;
    }
}
