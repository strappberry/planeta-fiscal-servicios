<?php

namespace App\Jobs;

use App\Models\Cliente;
use App\Sat\Descarga\SolicitarDescargaMetadata;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SolicitarMetadataFacturasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Cliente $cliente,
        public Carbon $fechaInicial,
        public Carbon $fechaFinal,
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $solicitador = new SolicitarDescargaMetadata($this->cliente);
        $solicitador
            ->fechaInicial($this->fechaInicial)
            ->fechaFinal($this->fechaFinal)
            ->solicitar();
    }
}
