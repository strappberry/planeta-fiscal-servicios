<?php

namespace App\Console\Commands;

use App\Jobs\SolicitarMetadataFacturasJob;
use App\Models\Cliente;
use App\Sat\Descarga\SolicitarDescargaMetadata;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SolicitarMetadataFacturas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sat:solicitar-metadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Solicitar metadata de las facturas';

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
        /** @var Collection<Cliente> */
        $clientes = Cliente::query()
            ->get();

        $fechaFinal = now()->subDays(1)->endOfDay();
        $fechaInicial = $fechaFinal->copy()->subMonths(3)->startOfDay();

        foreach ($clientes as $cliente) {
            try {
                SolicitarMetadataFacturasJob::dispatch($cliente, $fechaInicial, $fechaFinal);
            } catch (Exception $e) {
                Log::error($e);
            }
        }

        return 0;
    }
}
