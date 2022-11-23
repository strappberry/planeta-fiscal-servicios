<?php

namespace App\Acciones\PhpCfdi;

use DateTimeImmutable;
use Exception;
use PhpCfdi\SatWsDescargaMasiva\Service;
use PhpCfdi\SatWsDescargaMasiva\Services\Query\QueryParameters;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTimePeriod;
use PhpCfdi\SatWsDescargaMasiva\Shared\DownloadType;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;

class CrearSolicitudDescargaMasiva
{
    public static function ejecutar(
        Service $servicio,
        DateTimeImmutable $fechaInicio,
        DateTimeImmutable $fechaFin,
        DownloadType $tipoDescarga,
        RequestType $tipoSolicitud
    ): string {
        $peticion = QueryParameters::create(
            DateTimePeriod::createFromValues($fechaInicio, $fechaFin),
            $tipoDescarga,
            $tipoSolicitud
        );

        $resultado = $servicio->query($peticion);
        if (!$resultado->getStatus()->isAccepted()) {
            throw new Exception($resultado->getStatus()->getMessage(), 1);
        }

        return $resultado->getRequestId();
    }
}
