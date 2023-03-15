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
        $cliente = Cliente::find(376);
        $tipoDescarga = DownloadType::emitidos();
        $uuid = [
            'a136750d-30d1-11ea-8181-00155d014007',
            '0c8a58f9-c3b6-49c6-b1e9-f0b29becdc72',
            '11e2f146-fe6a-4d01-9078-e781ef1047b0',
            'cab45495-14e5-44ce-9c97-cd1b091fce04',
            '276a7f94-01a7-4e05-acb2-5999d71a0ab2',
            '3b5ebbf0-d734-439e-9863-038d1dea6db7',
            'c39c0c0f-1bbf-409e-be98-c29d2bdd8bc1',
            '983c1a42-820a-4579-a56e-d5e3d20b91f5',
            'bd6d6d88-8b21-4e22-b7e0-9a420600bd42',
            '8d0af1c1-6a3f-11ea-95f4-00155d014007',
            '2053723e-f0bc-4231-969a-1839f16b6c9a',
            'fd326fbf-ce0c-4909-bea6-03d2e998d362',
            'ff18c9ed-d3a9-47e0-916b-bbe6cdc911dc',
            '07e16394-950e-3042-bbcd-85f7a969723d',
            '4723d3f9-99d7-11ea-8e5b-00155d014007',
            '93c48640-a8d8-46df-9673-a0592ffa38b1',
            '558eeb9a-91a7-4b14-8e58-038d1dea372f',
            '4cce8da3-de68-8c4b-8861-9ebdcb28e861',
            'fb9f3ca9-0e97-466f-9bef-91e5df28129c',
            '9378e9fb-7646-4cea-b221-6c73a479f8df',
            '818b457d-601e-6e44-a08c-20cc6fb43621',
            '4bd50ca9-d607-11ea-9e8c-00155d014007',
            'ab090e52-099a-48d1-ab67-1cacdfa48874',
            '2f0e00e6-5dfd-4008-a269-569f08263934',
            '8ec938ec-ac85-49d6-a345-a75d411e9cdc',
            '8101aa89-b374-471e-942e-038d1dea7df2',
            '2e845130-aee0-40dd-a04e-04f3680cf300',
            'f1685ac8-130e-11eb-849c-00155d014009',
            '8e59cc09-f563-4844-8da2-5073087e3f5a',
            '8d1c831a-634b-48ad-b2cc-42c29592702a',
            '80a9878c-894e-4289-8af7-046351f9e9fe',
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
