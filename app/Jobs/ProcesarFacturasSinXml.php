<?php

namespace App\Jobs;

use App\Acciones\PhpCfdi\CrearServicioScraper;
use App\Models\Cliente;
use App\Models\Factura;
use App\Sat\Manejadores\ManejadorDescargaXml;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType;
use PhpCfdi\CfdiSatScraper\ResourceType;

class ProcesarFacturasSinXml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private Cliente $cliente
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $facturasEmitidas = Factura::query()
            ->where('cliente_id', $this->cliente->id)
            ->where('xml_procesado', false)
            ->where('rfc_emisor', $this->cliente->rfc)
            ->get();

        $facturasRecibidas = Factura::query()
            ->where('cliente_id', $this->cliente->id)
            ->where('xml_procesado', false)
            ->where('rfc_receptor', $this->cliente->rfc)
            ->get();

        try {
            $servicio = CrearServicioScraper::ejecutar($this->cliente);
            $manejarDescargar = new ManejadorDescargaXml();

            if ($facturasEmitidas->count()) {
                $lista = $servicio->listByUuids(
                    $facturasEmitidas->pluck('uuid')->toArray(),
                    DownloadType::recibidos()
                );
                $servicio->resourceDownloader(ResourceType::xml(), $lista, 50,)->download($manejarDescargar);
            }

            if ($facturasRecibidas->count()) {
                $lista = $servicio->listByUuids(
                    $facturasRecibidas->pluck('uuid')->toArray(),
                    DownloadType::recibidos()
                );
                $servicio->resourceDownloader(ResourceType::xml(), $lista, 50,)->download($manejarDescargar);
            }
        } catch(Exception $e) {
            Log::error(
                "Error al descargar xmls del cliente {$this->cliente->id} {$this->cliente->rfc}. "
                . $e->getMessage()
            );
        }
    }
}
