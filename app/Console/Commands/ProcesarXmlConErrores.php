<?php

namespace App\Console\Commands;

use App\Sat\Utilidades\FacturaArray;
use DOMDocument;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcesarXmlConErrores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'procesar:xml-errores';

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
        $archivos = File::allFiles(
            Storage::path('/archivos/xml_errores/')
        );

        libxml_use_internal_errors(true);

        foreach ($archivos as $archivo) {
            try {
                $cfdi = FacturaArray::convertirXmlAArray(
                    $archivo->getContents()
                );
                $uuid = $cfdi['Complemento']['TimbreFiscalDigital']['UUID'];

                FacturaArray::guardarCfdiArray($uuid, $cfdi);
                File::delete($archivo->getPathname());
            } catch(Exception $e) {
                Log::error($e->getMessage());
            }
        }

        return 0;
    }
}
