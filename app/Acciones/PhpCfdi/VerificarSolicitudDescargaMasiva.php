<?php

namespace App\Acciones\PhpCfdi;

use App\Enums\EstadoSolicitudDescargaMasiva;
use PhpCfdi\SatWsDescargaMasiva\Service;

class VerificarSolicitudDescargaMasiva
{
    public static function ejecutar(
        Service $servicio,
        string $idSolicitud
    ): array {
        $datosVerificacion = [
            'aceptado'         => true,
            'estado_sat'       => '',
            'estado_solicitud' => '',
            'paquetes'         => [],
        ];

        $verificacion = $servicio->verify($idSolicitud);

        if (!$verificacion->getStatus()->isAccepted()) {
            $datosVerificacion['aceptado'] = false;
            $datosVerificacion['estado_solicitud'] = EstadoSolicitudDescargaMasiva::RECHAZADO;
            return $datosVerificacion;
        }

        $estadoSolicitud = $verificacion->getStatusRequest();

        if ($estadoSolicitud->isExpired() || $estadoSolicitud->isFailure() || $estadoSolicitud->isRejected()) {
            $estado = $estadoSolicitud->getEntryValue();
            $datosVerificacion['estado_sat'] = $estado['message'];
            $datosVerificacion['estado_solicitud'] = EstadoSolicitudDescargaMasiva::RECHAZADO;
            return $datosVerificacion;
        }

        if ($estadoSolicitud->isInProgress() || $estadoSolicitud->isAccepted()) {
            $estado = $estadoSolicitud->getEntryValue();
            $datosVerificacion['estado_sat'] = $estado['message'];
            $datosVerificacion['estado_solicitud'] = EstadoSolicitudDescargaMasiva::EN_PROCESO;
            return $datosVerificacion;
        }

        $estado = $estadoSolicitud->getEntryValue();
        $datosVerificacion['estado_sat'] = $estado['message'];

        $datosVerificacion['estado_solicitud'] = EstadoSolicitudDescargaMasiva::COMPLETADO;
        $datosVerificacion['paquetes'] = $verificacion->getPackagesIds();

        return $datosVerificacion;
    }
}
