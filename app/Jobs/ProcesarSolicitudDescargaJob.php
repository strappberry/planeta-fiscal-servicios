<?php

namespace App\Jobs;

use App\Models\SolicitudDescarga;
use App\Sat\Descarga\DescargaScraperPHPCfdi;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcesarSolicitudDescargaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $solicitudId;

    public $timeout = 3600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($solicitudId)
    {
        $this->solicitudId = $solicitudId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $solicitud = SolicitudDescarga::find($this->solicitudId);
        $solicitud->status = SolicitudDescarga::STATUS_PROCESANDO;
        $solicitud->save();

        try {
            $resultado = (new DescargaScraperPHPCfdi($solicitud->cliente))
                ->fechaInicial(
                    Carbon::parse($solicitud->fecha_inicio, 'UTC')
                        ->setTimezone(config('app.timezone'))
                        ->startOfDay()
                )
                ->fechaFinal(
                    Carbon::parse($solicitud->fecha_fin, 'UTC')
                        ->setTimezone(config('app.timezone'))
                        ->endOfDay()
                )
                ->establecerSolicitudDescarga($solicitud)
                ->comenzar();

            $solicitud->refresh();

            if ((int) $solicitud->cantidad_facturas === 0) {
                $solicitud->status = SolicitudDescarga::STATUS_ERROR_AL_PROCESAR;
                $solicitud->save();
                return;
            }

            $solicitud->status = SolicitudDescarga::STATUS_PROCESADO;
            $solicitud->save();
        } catch (Exception $e) {
            Log::error($e);
            $solicitud->status = SolicitudDescarga::STATUS_ERROR_AL_PROCESAR;
            $solicitud->save();
        }
    }
}
