<?php
namespace App\Sat\Descarga;

use App\Models\Cliente;
use App\Models\SolicitudDescarga;
use App\Sat\Manejadores\ManejadorDescargaXml;
use App\Sat\Utilidades\InsertaDatosScraper;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType;
use PhpCfdi\CfdiSatScraper\MetadataList;
use PhpCfdi\CfdiSatScraper\QueryByFilters;
use PhpCfdi\CfdiSatScraper\ResourceType;
use PhpCfdi\CfdiSatScraper\SatScraper;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionManager;
use PhpCfdi\Credentials\Credential;

class DescargaScraperPHPCfdi implements DescargaScraperBuilder
{
    /** @var \App\Models\Cliente */
    private $cliente;

    /** @var \App\Models\SolicitudDescarga */
    private $solicitudDescarga;

    /** @var \Carbon\Carbon */
    private $fechaInicio;

    /** @var \Carbon\Carbon */
    private $fechaFin;

    /** @var string */
    private $carpeta;

    /** @var \PhpCfdi\CfdiSatScraper\SatScraper */
    private $satScraper;

    /** @var \PhpCfdi\CfdiSatScraper\MetadataList[] */
    private $paquetesCfdis = [];
    
    /** @var \PhpCfdi\CfdiSatScraper\Metadata[] */
    private $cfdisADescargar = [];

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function establecerSolicitudDescarga(SolicitudDescarga $solicitudDescarga): self
    {
        $this->solicitudDescarga = $solicitudDescarga;
        return $this;
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

    public function carpeta(string $carpeta) : self
    {
        $this->carpeta = $carpeta;
        return $this;
    }

    public function comenzar()
    {
        $this->crearScraper();
        $this->crearIntervaloDeSemanaYListarFacturas();
        $this->procesarPaquetesCfdis();
    }

    private function crearScraper()
    {
        $claveSat = $this->cliente->clavesSat()
            ->esFiel()
            ->sinCaducar()
            ->latest()
            ->first();

        if (!$claveSat) {
            throw new Exception("El cliente {$this->cliente->rfc} no tiene FIEL SAT valida");
        }

        $credencial = Credential::create(
            Storage::get($claveSat->cer),
            Storage::get($claveSat->key),
            $claveSat->password
        );

        $this->satScraper = new SatScraper(FielSessionManager::create($credencial));
    }

    private function crearIntervaloDeSemanaYListarFacturas()
    {
        $fechaIntervalo = $this->fechaFin->copy();
        $intervalosDeDescarga = [];
        do {
            $fechaFin = $fechaIntervalo->format('Y-m-d');
            $fechaIntervalo->subWeeks(2);

            if ($fechaIntervalo->gt($this->fechaInicio)) {
                $fechaInicio = $fechaIntervalo->format('Y-m-d');
            } else {
                $fechaInicio = $this->fechaInicio->format('Y-m-d');
            }
            $fechaIntervalo->subDay();

            array_push($intervalosDeDescarga, [
                'fechaInicio' => new DateTimeImmutable($fechaInicio),
                'fechaFin' => new DateTimeImmutable($fechaFin),
            ]);
        } while($fechaIntervalo->gte($this->fechaInicio));

        foreach($intervalosDeDescarga as $intervalo) {
            try {
                $this->listarCfdis($intervalo['fechaInicio'], $intervalo['fechaFin'], DownloadType::emitidos());
            } catch(Exception $e) {
                Log::error("[EMITIDOS] Error al listar CFDIs del cliente {$this->cliente->rfc} " . $e->getMessage());
            }
            try {
                $this->listarCfdis($intervalo['fechaInicio'], $intervalo['fechaFin'], DownloadType::recibidos());
            } catch (Exception $e) {
                Log::error("[RECIBIDOS] Error al listar CFDIs del cliente {$this->cliente->rfc} " . $e->getMessage());
            }
        }
    }

    private function listarCfdis(
        DateTimeImmutable $fechaInicio,
        DateTimeImmutable $fechaFin,
        DownloadType $tipoDescarga
    )
    {
        $query = new QueryByFilters($fechaInicio, $fechaFin);
        $query->setDownloadType($tipoDescarga);

        $paqueteCfdis = $this->satScraper->listByPeriod($query);
        array_push($this->paquetesCfdis, $paqueteCfdis);
    }

    public function procesarPaquetesCfdis()
    {
        $conteoCfdis = 0;
        foreach ($this->paquetesCfdis as $paqueteCfdis) {
            $conteoCfdis += count($paqueteCfdis);
            foreach ($paqueteCfdis as $uuid => $datosFactura) {
                $factura = $this->cliente->facturas()->where('uuid', $uuid)->first();
                $descargarXml = true;

                if (!$factura) {
                    InsertaDatosScraper::insertar($this->cliente, $datosFactura);
                } else {
                    $descargarXml = $factura->xml_procesado == false;
                    InsertaDatosScraper::actualizarFacturaConScraper($factura, $datosFactura);
                }

                if ($descargarXml) {
                    array_push($this->cfdisADescargar, $datosFactura);
                }
            }
        }

        if ($this->solicitudDescarga) {
            $this->solicitudDescarga->descargas = $conteoCfdis;
            $this->solicitudDescarga->status = SolicitudDescarga::STATUS_DESCARGANDO;
            $this->solicitudDescarga->save();
        }

        $manejarDescargar = new ManejadorDescargaXml();
        $this->satScraper->resourceDownloader(
            ResourceType::xml(),
            new MetadataList($this->cfdisADescargar),
            50,
        )->download($manejarDescargar);
    }

}