<?php

namespace App\Console\Commands;

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
        $archivos = Storage::files('/facturas/xmls/');
        $manejador = new ManejadorDescargaXml();

        foreach ($archivos as $archivo) {
            $pathinfo = pathinfo($archivo);
            $uuid = $pathinfo['filename'];

            $manejador->procesarXml($uuid, Storage::get($archivo));
        }
        return 0;
    }
}
