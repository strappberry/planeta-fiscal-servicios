<?php

namespace App\Sat\Descarga;

use App\Acciones\PhpCfdi\CrearServicioDescargaMasiva;
use App\Acciones\PhpCfdi\CrearSolicitudDescargaMasiva;
use App\Enums\EstadoSolicitudDescargaMasiva;
use App\Models\Cliente;
use App\Models\SolicitudDescarga;
use App\Models\SolicitudDescargaMasiva;
use Carbon\Carbon;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpCfdi\SatWsDescargaMasiva\Shared\DownloadType;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;

class SolicitarDescargaMetadata
{
    public $cliente;
    /** @var Carbon */
    public $fechaInicio;
    /** @var Carbon */
    public $fechaFin;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function fechaInicial(Carbon $fecha) : self
    {
        $this->fechaInicio = $fecha;
        return $this;
    }

    public function fechaFinal(Carbon $fecha) : self
    {
        $this->fechaFin = $fecha;
        return $this;
    }

    public function solicitar()
    {
        try {
            $servicio = CrearServicioDescargaMasiva::ejecutar($this->cliente);
        } catch (Exception $e) {
            Log::error("Error al crear servicio de descarga masiva para el cliente {$this->cliente->rfc}. " . $e->getMessage());

            return;
        }

        try {
            $id = CrearSolicitudDescargaMasiva::ejecutar(
                $servicio,
                new DateTimeImmutable($this->fechaInicio),
                new DateTimeImmutable($this->fechaFin),
                DownloadType::issued(),
                RequestType::metadata()
            );
            SolicitudDescargaMasiva::create([
                'fecha_inicial'          => $this->fechaInicio,
                'fecha_final'            => $this->fechaFin,
                'solicitud_id'           => $id,
                'tipo_solicitud'         => (string) RequestType::metadata(),
                'estado_solicitud'       => EstadoSolicitudDescargaMasiva::EN_PROCESO,
                'estado_sat'             => '',
                'tipo_descarga_facturas' => (string) DownloadType::issued(),
                'cliente_id'             => $this->cliente->id,
            ]);
        } catch (Exception $e) {
            Log::error("Error al solicitar emitidos {$this->cliente->rfc}. " . $e->getMessage());
        }

        try {
            $id = CrearSolicitudDescargaMasiva::ejecutar(
                $servicio,
                new DateTimeImmutable($this->fechaInicio->format('Y-m-d H:i:s')),
                new DateTimeImmutable($this->fechaFin->format('Y-m-d H:i:s')),
                DownloadType::received(),
                RequestType::metadata()
            );
            SolicitudDescargaMasiva::create([
                'fecha_inicial'          => $this->fechaInicio,
                'fecha_final'            => $this->fechaFin,
                'solicitud_id'           => $id,
                'tipo_solicitud'         => (string) RequestType::metadata(),
                'estado_solicitud'       => EstadoSolicitudDescargaMasiva::EN_PROCESO,
                'estado_sat'             => '',
                'tipo_descarga_facturas' => (string) DownloadType::received(),
                'cliente_id'             => $this->cliente->id,
            ]);
        } catch (Exception $e) {
            Log::error("Error al solicitar recibidos {$this->cliente->rfc}. " . $e->getMessage());
        }
    }
}
