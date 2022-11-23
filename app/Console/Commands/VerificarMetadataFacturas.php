<?php

namespace App\Console\Commands;

use App\Acciones\PhpCfdi\CrearServicioDescargaMasiva;
use App\Acciones\PhpCfdi\CrearServicioScraper;
use App\Acciones\PhpCfdi\DescargarPaquetesDescargaMasiva;
use App\Acciones\PhpCfdi\ProcesarPaquetesMetadatos;
use App\Acciones\PhpCfdi\ProcesarUuidsPorScrapper;
use App\Acciones\PhpCfdi\VerificarSolicitudDescargaMasiva;
use App\Enums\EstadoSolicitudDescargaMasiva;
use App\Jobs\VerificarSolicitudMetadataJob;
use App\Models\Cliente;
use App\Models\SolicitudDescargaMasiva;
use Illuminate\Console\Command;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType;

class VerificarMetadataFacturas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sat:verificar-solicitudes-metadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica las solicitudes de descarga masiva de metadatos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $solicitudes = SolicitudDescargaMasiva::query()
            ->metadata()
            ->pendientes()
            ->get();

        foreach($solicitudes as $solicitud) {
            VerificarSolicitudMetadataJob::dispatch($solicitud);
        }

        return 0;
    }
}
