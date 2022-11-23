<?php

namespace App\Jobs;

use App\Models\SolicitudDescargaMasiva;
use App\Sat\Descarga\VerificadorSolicitudDescargaMetadata;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerificarSolicitudMetadataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private SolicitudDescargaMasiva $solicitud,
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $verificador = new VerificadorSolicitudDescargaMetadata($this->solicitud);
        $verificador->verificar();
    }
}
