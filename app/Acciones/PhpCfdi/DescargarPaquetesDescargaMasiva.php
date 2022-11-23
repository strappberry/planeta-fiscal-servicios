<?php

namespace App\Acciones\PhpCfdi;

use Illuminate\Support\Facades\Storage;
use PhpCfdi\SatWsDescargaMasiva\Service;

class DescargarPaquetesDescargaMasiva
{
    const CARPETA = '/facturas/paquetes/';

    public static function ejecutar(Service $servicio, array $paquetes): array
    {
        $paquetesDescargados = [];
        foreach ($paquetes as $paquete) {
            $descarga = $servicio->download($paquete);
            if ($descarga->getStatus()->isAccepted()) {
                $archivoPaquete = $paquete . '.zip';
                $paquetesDescargados[] = $archivoPaquete;
                Storage::put(
                    self::CARPETA . $archivoPaquete,
                    $descarga->getPackageContent()
                );
            }
        }

        return $paquetesDescargados;
    }
}
