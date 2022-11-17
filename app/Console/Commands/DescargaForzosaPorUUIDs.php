<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Factura;
use App\Sat\Manejadores\ManejadorDescargaXml;
use App\Sat\Utilidades\InsertaDatosScraper;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType;
use PhpCfdi\CfdiSatScraper\MetadataList;
use PhpCfdi\CfdiSatScraper\ResourceType;
use PhpCfdi\CfdiSatScraper\SatHttpGateway;
use PhpCfdi\CfdiSatScraper\SatScraper;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionManager;
use PhpCfdi\Credentials\Credential;

class DescargaForzosaPorUUIDs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forzar:descarga-uuid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $cliente = Cliente::find(185);
        $tipoDescarga = DownloadType::recibidos();
        $uuid = [
            'DE5AF956-E50F-48F6-8729-ADC2A1351701',
            '257105FA-858B-4222-9524-21A55412AB35',
            '6DE416D9-C33C-4680-AF16-A42405C4F96A',
            'DB8CE6AB-DF4E-4DAE-A553-DF6F4D4BDE92',
            'F6ED65FE-20A9-4B5E-B3BD-962C14E3D6D6',
            '72462361-7325-471E-A9ED-DF15377C392E',
            'B0FDA796-98A6-48BA-BA63-04D79B809E03',
            '0D257838-80CA-45F7-8626-55FB29E3AB03',
            '0782A460-D22D-47B8-AAD0-1EB852EE6778',
            '46DA9B0E-F136-4F8F-99DC-1B4F241390FC',
            '517519AF-D3E5-4937-BC0D-878ADBFDFE30',
            '13891906-5885-11ED-AE01-C5C9079D3BB9',
        ];
        $cfdisADescargar = [];

        $claveSat = $cliente->clavesSat()
            ->esFiel()
            ->sinCaducar()
            ->latest()
            ->first();
        $credencial = Credential::create(
            Storage::get($claveSat->cer),
            Storage::get($claveSat->key),
            $claveSat->password
        );

        $client = new Client([
            'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
        ]);
        $satScraper = new SatScraper(FielSessionManager::create($credencial),  new SatHttpGateway($client));
        $paqueteCfdis = $satScraper->listByUuids($uuid, $tipoDescarga);

        foreach ($paqueteCfdis as $uuid => $datosFactura) {
            $factura = Factura::where('uuid', $uuid)->first();
            $descargarXml = true;

            if (!$factura) {
                InsertaDatosScraper::insertar($cliente, $datosFactura);
            } else {
                $descargarXml = $factura->xml_procesado == false;
                InsertaDatosScraper::actualizarFacturaConScraper($factura, $datosFactura);
            }

            if ($descargarXml) {
                array_push($cfdisADescargar, $datosFactura);
            }
        }

        $manejarDescargar = new ManejadorDescargaXml();
        $satScraper->resourceDownloader(
            ResourceType::xml(),
            new MetadataList($cfdisADescargar),
            50,
        )->download($manejarDescargar);

        return 0;
    }
}
