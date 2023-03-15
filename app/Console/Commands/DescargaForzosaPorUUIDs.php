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
        $tipoDescarga = DownloadType::recibidos();
        $uuid = [
            '2469130b-9f71-4e7d-8ebd-69b35dba1c35',
            '515bd829-7664-4c1c-ba31-0c7cbb666beb',
            '809702a4-92c2-4c73-adc1-237f9f8c263d',
            '30d98bf7-a294-4d8c-925a-819bec27b44d',
            '5085c9c1-d10f-41bc-b1a1-358fb296cc4e',
            '44c524fe-c290-44b1-9350-5bbfaf793abc',
            '5758d654-74d0-4609-b3ed-cf1d7bc921c4',
            '6c71ea71-ec64-4ece-bce3-823d96532002',
            '55a5dfcc-3f45-4bbb-af34-569f50fd6285',
            '038812fd-68d6-42ca-a199-f4a50844422f',
            '5164991a-f0cb-4899-a1f3-0e10889de6ed',
            '0f8a9bc9-302a-464e-affe-fd9558427597',
            'c2cd8c35-ac7c-4ec1-8a51-44c0ae20099d',
            'f0f77361-0f00-4175-ae77-d156c5531b1b',
            '6d3e249b-62a9-43df-bfe1-71243c73955b',
            '9e327acd-9bd4-41ef-904d-b766fd71fc3d',
            '9c66f5b0-fc62-4958-9a89-08ab142f48b0',
            'd8df302b-e310-4e75-ae07-138f757d0964',
            'bbf286da-753a-4397-9a17-6dd3715548df',
            '85679f1b-1abe-4a9b-9784-fd3a07d4429b',
            '4718e584-0dc5-40aa-8a65-8a7859a1489f',
            '58767832-75b9-4aad-b880-4c91ac1cccaf',
            '83e35b19-0469-4638-bd24-cf1d7c8e6a24',
            '7c8e0592-8402-4b6c-a30d-4f4ba2cae458',
            '362e7b2f-69e8-493c-860e-30ca8337e7bc',
            'c48eaa31-858d-4e8b-82eb-a5674d97086f',
            'b42ccc9f-aa6a-44fd-88e6-049f40bf1b6f',
            'c7697c19-41c9-44a2-80cb-2d00c5782c9b',
            '275d1e87-e56f-4d83-a026-ce48c6085009',
            '8851080c-6c19-485a-9f45-dbecc2faa5e4',
            '1d4d6d2a-823f-423f-98e6-a1f1cb2423fc',
            '493560d0-58f3-410f-a8fc-584aa45bbb5b',
            '336b7da3-966c-4cd9-b0e8-70ae2593a236',
            '007e8741-19b4-4d9c-96e0-b9b9be35ab4b',
            '7bde19d7-29a9-4e6b-8a9b-c1ca3264cc7d',
            '480e8bf2-2f0f-4796-938d-8462500127f6',
            'c6f96eab-ee05-4cf6-a742-8b67ba251f98',
            '13095108-2aad-49c5-a665-4bb573da5c0e',
            '122c0c4f-cdc1-49f8-92e5-379942b264bb',
            '5f75fdbb-1d93-47c1-a0c4-dc11fa48a494',
            'a8d74571-cce5-472a-9bbb-9ea698471bd9',
            '16cac49c-0eef-4a28-981f-f4490f0e3852',
            'abe3928d-a2cc-4044-8e92-fd70f7af784b',
            '3ad18890-08e1-4f00-94ac-81586b3a18d1',
            'aa83c812-584e-4cd3-9b55-6d6ed34da23b',
            'afc96aa9-a9d5-4f87-8ea0-5d348454f641',
            '928a28e4-7ead-440a-885b-abb18d9a72c1',
            '6f600e0b-4ea9-43b5-8c85-325c7d49ff99',
            'f864d4c1-534e-4150-9ded-317dedd3630a',
            '87cb2ccf-32d1-4723-8754-92eb6e27f529',
            'a6747e9d-d2fc-4c8e-9103-d29724ae17e8',
            '383a169a-f171-49c6-87f4-3142870b2053',
            'ddcfcfbd-7ae4-42d3-8498-1cdbb9996fea',
            '016b9bda-f22e-4628-9eaf-e9dcd62e4d0c',
            'ae3f0808-a734-4524-998b-b8363466b62a',
            '6c46f665-4efe-427f-8a8e-b6bdc68ba4f7',
            '8d05d8d3-de81-4b21-8b27-63c2c466191d',
            '96dbf19c-b60b-4b13-81ca-03344694a5a6',
            'f7874e92-07e4-49e6-be91-6ffce5ec91b1',
            '0c054873-e774-4674-a999-d133a624f2ec',
            '68828080-3ed5-49e7-a684-e9ade796c180',
            '4776048d-3ada-403c-a953-79d75aba21e9',
            '5e3c8053-7699-4f84-adc6-7242ef11a1da',
            'b1ed5eeb-13d8-407e-be81-f212c2ce56a8',
            '6937d241-14ad-49ef-a8b8-7fee7171c865',
            '92c0d336-fdc3-434d-916f-4ec7bbc1ad49',
            'cf64bb53-351a-447f-a629-a08a1b9fb8ff',
            'cafd71cc-ef31-46d5-bf2c-d5b7b610cdb5',
            'b15d1151-1315-4f68-b3a6-2d89a048a6de',
            '4232e8b7-9694-4dc4-bfb8-93caa3f33c12',
            '2e34bbc8-ee50-4482-b1c8-062048499fb0',
            '70fc945a-3e5d-4938-83f0-855a13c85fd4',
            'd8fb4b7c-21b8-4c5d-8e63-3a8fc115ebc5',
            'cfecd2db-9cc5-46a4-9d57-8abce381b389',
            '3e98336c-1f31-4fbe-b00f-315b64ec5cc9',
            '990c51c9-c76a-42ad-bb88-f234afdc868e',
            'cd1b4b0e-1cf6-4fed-ac2a-29420cd49e30',
            '41f1f4fd-5a95-4ed7-bb9a-efba2bbf493e',
            '356f4dcf-f562-4f44-aba9-d54b1d324eff',
            'f5ef493f-bde4-46a1-a410-8a3673c87026',
            '8ec8619f-4224-4ff8-94d7-aa194fdc50a9',
            '985cfccb-fcdb-42a1-a161-922de3b55282',
            '39c7ed21-9dd9-47e3-86e9-a894a9f2a12d',
            '673697a5-4144-489a-aa0e-83f7d7897262',
            '9a440376-61c3-437f-a1e0-220a9b2c35a9',
            '1c708d09-dacb-471d-9dc1-2ee332b7f2bf',
            '3d8d924c-8923-49f0-8cea-bdb636f80f56',
            '0ad3d7a2-58c0-4ed7-81c9-9c030599da96',
            '81475f8a-2ab7-47cd-be05-e7427deadb0f',
            'efc1b20e-c149-4f82-bdb6-bcba8d8b03f6',
            '9ed1ee55-ccdf-40b4-80ca-96582bc03a20',
            '056106eb-c638-499f-bb9b-e7c38b705ee8',
            '0ead572b-6c6c-4239-9384-a3856da13123',
            '74ba6721-f27b-4e69-9d28-98df7e69fda5',
            '695a995a-abed-4d46-93e2-e688eb8f7c77',
            '1635b3c0-b07b-45a7-bc25-e7b747ae596a',
            'daea0ca7-7729-41ca-b190-b531a3b5a415',
            '4d3057cc-444a-4062-8e53-e31e5a52dce4',
            '8db2f9c0-688b-4a35-a04c-4ead87201cc6',
            '2efb3873-216d-428f-8305-1d470ff09cda',
            'e7459025-a5d9-4c86-b0ee-d8580c04f760',
            '4226d9e6-4291-45ca-bd9a-aeeb6ce561fd',
            '590ed1b7-459c-4bb3-a1bd-b6ff635b44f8',
            '1aac87d5-4bc0-41e1-9d44-58a9ec5278fa',
            '2f387698-f03a-42f4-82ca-ab3e5c7e050a',
            '42f33fe6-79de-46ae-812b-5e04c923c2b4',
            '378319de-fabd-4efd-b0ef-5408b3e3dc69',
            '40a48cb4-0314-4628-b8b9-a536fe0a4343',
            '2be961ad-1b5d-4d8f-875d-495d56bbe17a',
            '0c8a58f9-c3b6-49c6-b1e9-f0b29becdc72',
            '11e2f146-fe6a-4d01-9078-e781ef1047b0',
            '225889d7-ac7a-4009-a049-1278bf1d6949',
            '9e4f3fa5-0367-4efa-90b7-b7b24acbfb6a',
            '04341985-c32f-4b70-82c3-c30032244c08',
            '65af59aa-bcd7-4b1a-a8c8-5960dcd71fdd',
            '5c61139b-3274-40cc-b324-7994c4ffe3a6',
            'b5e05399-98f3-4e6d-9465-872016d6bb96',
            '4b77a8ed-67a0-4fa4-8a6a-27232d5d354d',
            '4e7293bb-20ea-444c-ad77-39aef66c8cfd',
            'ce3c1fcc-e9c2-46be-bf05-2fffcf237aee',
            'bf590c85-0299-4be4-81ab-e5a614205e47',
            'fae85a71-f98a-43f0-ab6c-ff22aca3199a',
            'fde33f21-4865-4f75-a64a-7f6e52ec8bf2',
            '998cb32c-c66a-4ef1-af32-1125d8e12bf2',
            'a0d85fa0-d0b9-4e55-8a01-b2850f1f9110',
            '14746e6d-f49a-4f67-a8c0-969882f7c98f',
            'c926b51e-c537-4793-b202-cd59ae0404b4',
            'd811392c-7770-4794-bb2d-750d1eff7772',
            '85ee41fa-e9ed-4d3a-8938-91170f1178ee',
            '36dc3f64-03c7-48f7-aa59-f6120fe70363',
            '04d4c57b-a3be-45cb-8434-ff6304819cc7',
            '297d5042-262e-43d6-8ff5-805228946240',
            'a7d28aa4-62b5-4828-9489-13f3ace22999',
            'd2a0f35e-1a1d-40f1-bffc-0dc785be205e',
            'f91f6af5-942d-40a3-956a-b92de2452e5d',
            '9f25f572-c761-4dfa-844b-acced18f8f82',
            'd83755a2-3381-4769-aade-a528e8becd34',
            'ee36fcc5-2554-4951-9d9f-1e58728c5db0',
            'c50429a4-27eb-4227-9258-cb6fc085064e',
            '5a2211f4-bf58-4db4-94b6-b4eca4e6da82',
            '76b9c996-d390-4dce-a278-59abae8a3ae9',
            'fa07e9ac-4fbf-4793-80a4-70d929c3e139',
            '0fb3f1b7-b9b5-4e35-983b-24a9647e6d97',
            '123d3e88-5016-452c-99ea-3df302da60d1',
            '2624ec63-21ac-473e-b4e8-f47743ac05a9',
            '62bdffdf-4ae8-4577-b060-e877db410ed8',
            '997d7166-1de7-4385-abca-f1a9a2275b82',
            '59274c4f-edd8-4386-9d22-8354b072a215',
            'a30d7bdf-86b7-4432-923b-4d397d500ded',
            'c8a93a61-dd9b-4a8a-9d4d-889afee3ae79',
            '44397e64-0576-46e8-bc62-5a4c49a0fb60',
            '95cb969e-5db6-4399-b247-7ab5599c51df',
            '2fc7650a-4c5e-4397-82be-fa4e7c60c42d',
            'a866f2dd-62b5-485b-b6c0-308de7943a67',
            '797da957-831e-46f6-904f-96faee0e0a43',
            '55baa46d-3466-40f1-8bc9-c0ba18d70ba0',
            '20480fb0-35a1-44a5-b638-43c3eb937ac7',
            'de925f4a-61d7-4312-ac0d-f3b3f633b32f',
            '179fb896-52db-4d72-889b-ca7678485667',
            '9e3e0114-270a-4405-88fc-f64ac98e5d88',
            '4a045b50-78a4-47db-8136-4097306e74bc',
            '8ad46563-711b-41d8-aa97-be2e5ada84ea',
            '28a8b3f7-a622-4a69-8113-3425c3a3604b',
            'd230e9d1-547a-4305-990a-d4cbae19725b',
            '1c02fabc-4801-483b-9543-ffd90e469418',
            '63012bee-6268-44a3-96cc-74ad84c5c05a',
            '5e138596-823c-4561-ac76-7a776315075a',
            'a9ef4f93-c067-411a-a8fb-56697dcf4143',
            '0dee9791-a147-47ae-a79d-df7b45ff97fa',
            'c6203fce-d68a-477f-9032-c770f7f862f8',
            '5163500f-1f56-4d36-b745-171168bec326',
            'b0f26f2a-03ec-44e4-8428-dfe86e99e359',
            'c8cfb3be-8116-4971-b9d9-d660347f2982',
            '10f4fbb1-f64e-412e-9f99-e85f0330e7fd',
            '79b9a864-b5f6-478b-89e0-af0cdcd05df8',
            'dee73f19-bde6-4510-b183-34ed99ba6a1b',
            '0ee8de8e-4123-4692-9fc7-91a94a2c77f7',
            '52cc6070-4a3f-4795-a649-a3cce20f36cb',
            '78fae8b5-4e21-4bc7-805d-3eb0a190a321',
            'f84c109f-4d76-4150-a0ab-2f458968a6e0',
            '4ac6686f-1413-421c-bf58-2d90cbd08013',
            '367c4a12-ef5b-4f0f-9fb6-902484d28818',
            '6f990350-d4db-4266-bb5a-0bbd75c0a7c5',
            'd109cd14-30ac-11ea-b916-00155d014009',
            'a136750d-30d1-11ea-8181-00155d014007',
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
