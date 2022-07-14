<?php

namespace App\Console\Commands;

use App\Models\Factura;
use App\Sat\Manejadores\ManejadorDescargaXml;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ProcesarTodosLosXmls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'procesar:todos-los-xmls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Guardar la información de los xmls en la base de datos';

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
        $totalFacturas = Factura::count();
        $porPagina = 1000;
        $paginas = ceil($totalFacturas / $porPagina);
        $this->info("Procesando {$totalFacturas} facturas en {$paginas} páginas");

        $manejador = new ManejadorDescargaXml();
        $rutaBase = '/facturas/xmls/';
        for($i = 0; $i < $paginas; $i++) {
            $this->info(" --- Procesando página {$i} --- ");
            $facturas = Factura::skip($i * $porPagina)->take($porPagina)->get();

            foreach($facturas as $factura) {
                $rutaXml = $rutaBase . $factura->uuid . '.xml';
                if (Storage::exists($rutaXml)) {
                    $this->info("Procesando xml {$factura->uuid}");
                    $manejador->procesarXml($factura->uuid, Storage::get($rutaXml));
                }
            }
        }


        return 0;
    }
}
