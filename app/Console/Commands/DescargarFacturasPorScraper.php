<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Sat\Descarga\DescargaScraperPHPCfdi;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DescargarFacturasPorScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sat:descargar-por-scraper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Descargar facturas emitidas y recibidas por los clientes en los ultimos 30 dias';

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
        $clientes = Cliente::query()
            ->obtenerFacturasAutomaticamente()
            ->get();

        $ayer = now()->subDays(1);
        $mesAnterior = now()->subMonths(3);

        foreach ($clientes as $cliente) {
            

            try {
                (new DescargaScraperPHPCfdi($cliente))
                ->fechaInicial($mesAnterior)
                ->fechaFinal($ayer)
                ->carpeta(
                    Storage::path('/archivos/xmls_procesar/')
                )
                ->comenzar();
            } catch (Exception $e) {
                Log::error($e);
            }
        }
        return 0;
    }
}
