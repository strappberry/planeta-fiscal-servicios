<?php

namespace App\Console\Commands;

use App\Models\Factura;
use App\Sat\Utilidades\FacturaArray;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ProcesarComprobantesNulos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'procesar:comprobantes-nulos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar facturas con comprobantes nulo';

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
        libxml_use_internal_errors(true);

        $facturas = Factura::select('id', 'uuid')->get();

        foreach ($facturas as $factura) {
            if (
                !$factura->comprobanteXml()->exists() &&
                Storage::exists('/facturas/xmls/' . $factura->uuid . '.xml')
            ) {
                $cfdi = FacturaArray::convertirXmlAArray(
                    Storage::get('/facturas/xmls/' . $factura->uuid . '.xml')
                );
                FacturaArray::guardarCfdiArray($factura->uuid, $cfdi);
            }
        }

        $total = 0;
        foreach ($facturas as $factura) {
            if (!$factura->comprobanteXml()->exists()) {
                $total++;
            }
        }

        $this->info("Se encontraron {$total} facturas con comprobantes nulos");
    }
}
