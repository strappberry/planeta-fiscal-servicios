<?php

namespace App\Console\Commands;

use App\Jobs\ProcesarFacturasSinXml;
use App\Models\Cliente;
use App\Models\Factura;
use Illuminate\Console\Command;

class VerificarFacturasSinXml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sat:verificar-facturas-sin-xml';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar las facturas que no tengan xml descargado';

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
        $clientesIds = Factura::select('cliente_id')
            ->where('xml_procesado', false)
            ->groupBy('cliente_id')
            ->get();

        $clientesIds = $clientesIds->pluck('cliente_id')->toArray();
        $clientes = Cliente::query()
            ->whereIn('id', $clientesIds)
            ->get();

        foreach($clientes as $cliente) {
            ProcesarFacturasSinXml::dispatch($cliente);
        }

        return 0;
    }
}
