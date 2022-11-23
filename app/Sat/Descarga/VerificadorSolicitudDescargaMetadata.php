<?php

namespace App\Sat\Descarga;

use App\Acciones\PhpCfdi\CrearServicioDescargaMasiva;
use App\Acciones\PhpCfdi\CrearServicioScraper;
use App\Acciones\PhpCfdi\DescargarPaquetesDescargaMasiva;
use App\Acciones\PhpCfdi\ProcesarPaquetesMetadatos;
use App\Acciones\PhpCfdi\ProcesarUuidsPorScrapper;
use App\Acciones\PhpCfdi\VerificarSolicitudDescargaMasiva;
use App\Models\SolicitudDescargaMasiva;
use Exception;
use Illuminate\Support\Facades\Log;

class VerificadorSolicitudDescargaMetadata
{
    public function __construct(
        private SolicitudDescargaMasiva $solicitud,
    ) {
    }

    public function verificar(): void
    {
        try {
            $servicioDescargaMasiva = CrearServicioDescargaMasiva::ejecutar($this->solicitud->cliente);
        } catch (Exception $e) {
            Log::error("Error al crear servicio de descarga masiva para el cliente {$this->solicitud->cliente->rfc}. " . $e->getMessage());
            return;
        }

        try {
            $resultadoVerificacion = VerificarSolicitudDescargaMasiva::ejecutar(
                $servicioDescargaMasiva,
                $this->solicitud->solicitud_id
            );
            $this->solicitud->estado_sat       = $resultadoVerificacion['estado_sat'];
            $this->solicitud->estado_solicitud = $resultadoVerificacion['estado_solicitud'];
            $this->solicitud->paquetes         = $resultadoVerificacion['paquetes'];
            $this->solicitud->save();
        } catch (Exception $e) {
            Log::error(
                "Error al verificar la solicitud de descarga masiva {$this->solicitud->id}. " . $e->getMessage()
            );
        }

        try {
            if (count($this->solicitud->paquetes)) {
                $paquetes = DescargarPaquetesDescargaMasiva::ejecutar(
                    $servicioDescargaMasiva,
                    $this->solicitud->paquetes
                );
                $uuids = ProcesarPaquetesMetadatos::ejecutar($this->solicitud->cliente, $paquetes);

                $servicioScraper = CrearServicioScraper::ejecutar($this->solicitud->cliente);

                ProcesarUuidsPorScrapper::ejecutar(
                    $this->solicitud->cliente,
                    $servicioScraper,
                    $this->solicitud->tipoBusquedaScraper,
                    $uuids
                );
            }
        } catch (Exception $e) {
            Log::error(
                "Error al procesar los paquetes de la solicitud de descarga masiva {$this->solicitud->id}. " . $e->getMessage()
            );
        }
    }
}
